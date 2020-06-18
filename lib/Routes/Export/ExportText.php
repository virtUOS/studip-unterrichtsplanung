<?php

namespace Unterrichtsplanung\Routes\Export;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Summary;
use Unterrichtsplanung\Models\Plans;

class ExportText extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $summaries = Summary::findBySQL('plans_id = ? ORDER BY structures_id ASC', [$args['plans_id']]);
        $plan      = Plans::find($args['plans_id']);
        $breaks    = array("<br />","<br>","<br/>");

        $text = 'Unterrichtsplan: '. $plan->name ."\r\n\r\n";
        $data = json_decode($plan->metadata, true);

        $text .= 'Schulform: '. $data['typeOfSchool'] ."\r\n";
        $text .= 'Klassenstufe: '. $data['gradeLevel'] ."\r\n";
        $text .= 'Fach: '. $data['subject'] ."\r\n";
        $text .= 'Thema der Unterrichtsstunde: '. $data['topic'] ."\r\n";
        $text .= 'Datum und Uhrzeit: '
            . date('d.m.Y, H:i', strtotime($data['date'] .' '. $data['time']))
            . ' Uhr' . "\r\n";
        $text .= "\r\n";

        foreach ($summaries as $entry) {
            $text .= ucfirst($entry->structures->name) ."\r\n\r\n";

            $text .= html_entity_decode(
                strip_tags(
                    str_ireplace($breaks, "\r\n", $entry['text'])
                )
            );

            $text .= "\r\n";
        }

        return $response->withHeader(
            'Content-Type', 'text/plain'
        )->withHeader(
            'Content-Disposition', 'attachment; filename="Unterrichtsplan_'. $plan->name  .'.txt"'
        )->write($text);
    }
}
