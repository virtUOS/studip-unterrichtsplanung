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

class ExportPdf extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $summaries = Summary::findBySQL('plans_id = ? ORDER BY structures_id ASC', [$args['plans_id']]);
        $plan      = Plans::find($args['plans_id']);

        $document = new \ExportPDF();
        $document->setTitle('Unterrichtsplan: '. $plan->name);
        $document->addPage();
        $document->writeHTML('<h1>Unterrichtsplan: '. $plan->name .'</h1>');
        $document->addContent("\n");

        foreach ($summaries as $entry) {
            $document->writeHTML('<h2>'. ucfirst($entry->structures->name) .'</h2>');
            $document->addContent("\n");

            $document->writeHTML($entry['text']);
            $document->addContent("\n");
        }

        foreach ($plan->schedules as $sched) {
            $schedule = json_decode($sched->content, true);

            if (!empty($schedule)) {
                $document->writeHTML('<h2>Verlaufsplan</h2>');
                $document->addContent("\n");

                $html = '<table border="1" cellpadding="3">';
                $html .= '<tr>'
                    . '<th width="12%" style="font-weight: bold">Zeit</th>'
                    . '<th width="22%" style="font-weight: bold">Phase</th>'
                    . '<th width="22%" style="font-weight: bold">Handlungsschritte</th>'
                    . '<th width="22%" style="font-weight: bold">Methodik</th>'
                    . '<th width="22%" style="font-weight: bold">Medien</th>'
                    . '</tr>';


                foreach ($schedule as $entry) {
                    $html .= '<tr>';
                    foreach ($entry as $val) {

                         $html .= '<td>'. $val .'</td>';
                    }
                    $html .= '</tr>';
                }
                $html .= '</table>';

                $document->writeHTML($html);
            }
        }

        $document->dispatch('Unterrichtsplan_' . $plan->name);
        die;
    }
}
