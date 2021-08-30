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

        $data = json_decode($plan->metadata, true);

        $document->writeHTML('<b>Schulform:</b> '. $data['typeOfSchool']);
        $document->writeHTML('<b>Klassenstufe:</b> '. $data['gradeLevel']);
        $document->writeHTML('<b>Anzahl Schüler*innen:</b> '. $data['numberOfPupils']);
        $document->writeHTML('<b>Fach:</b> '. $data['subject']);
        $document->writeHTML('<b>Unterrichtseinheit:</b> '. $data['lesson']);
        $document->writeHTML('<b>Thema der Unterrichtsstunde:</b> '. $data['topic']);
        $document->writeHTML('<b>Datum und Uhrzeit:</b> '
            . date('d.m.Y, H:i', strtotime($data['date'] .' '. $data['time']))
            . ' Uhr'
        );
        $document->addContent("\n");

        foreach ($summaries as $entry) {
            $document->addPage();
            $document->writeHTML('<h2>'. ucfirst($entry->structures->name) .'</h2>');

            $document->writeHTML($entry['text']);
        }

        foreach ($plan->schedules as $sched) {
            $schedule = json_decode($sched->content, true);

            if (!empty($schedule)) {
                $document->addPage('L');
                $document->writeHTML('<h2>Verlaufsplan</h2>');
                $document->addContent("\n");

                $html = '<table border="1" cellpadding="3">';

                $html .= '<tr>';
                foreach ($schedule['titles'] as $key => $title) {
                    $html .=  '<th width="14%" style="font-weight: bold">' . $title .'</th>';
                }
                $html .= '</tr>';

                foreach ($schedule['rows'] as $row) {
                    $html .= '<tr>';
                    foreach ($row as $val) {
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
