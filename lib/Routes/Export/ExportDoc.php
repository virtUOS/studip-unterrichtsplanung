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

class ExportDoc extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $summaries = Summary::findBySQL('plans_id = ? ORDER BY structures_id ASC', [$args['plans_id']]);
        $plan      = Plans::find($args['plans_id']);
        $data      = json_decode($plan->metadata, true);

        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontSize(12);
        $phpWord->setDefaultFontName('Arial');

        $phpWord->addTitleStyle(1, [
            'size' => 18
        ]);
        $phpWord->addTitleStyle(2, [
            'size' => 16
        ]);
        $phpWord->addTitleStyle(3, [
            'size' => 14
        ]);

        $phpWord->addTitleStyle(4, [
            'size' => 12,
            'bold' => true
        ]);

        $phpWord->addTitleStyle(5, [
            'size' => 12,
            'bold' => true
        ]);

        $phpWord->addTitleStyle(6, [
            'size' => 12,
            'italic' => true
        ]);

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $section = $phpWord->addSection();

        $html = '<h1>Unterrichtsplan: '. $plan->name .'</h1>';

        $html .= '<p>';
        $html .= '<b>Schulform:</b> '. $data['typeOfSchool'] . '<br/>';
        $html .= '<b>Klassenstufe:</b> '. $data['gradeLevel'] . '<br/>';
        $html .= '<b>Anzahl Schüler*innen:</b> '. $data['numberOfPupils'] . '<br/>';
        $html .= '<b>Fach:</b> '. $data['subject'] . '<br/>';
        $html .= '<b>Unterrichtseinheit:</b> '. $data['lesson'] . '<br/>';
        $html .= '<b>Thema der Unterrichtsstunde:</b> '. $data['topic'] . '<br/>';
        $html .= '<b>Datum und Uhrzeit:</b> '
            . date('d.m.Y, H:i', strtotime($data['date'] .' '. $data['time']))
            . ' Uhr<br/>';
        $html .= '</p>';

        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

        foreach ($summaries as $entry) {
            $section = $phpWord->addSection();
            $html = '<h2>'. ucfirst($entry->structures->name) .'</h2>';
            $html .= $entry['text'];
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
        }

        // add header for schedule
        $html = '<h2>Verlaufsplan</h2>';

        $section = $phpWord->addSection(['orientation' => 'landscape']);
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);



        foreach ($plan->schedules as $sched) {
            $tableStyle =[
                'borderSize'  => 6,
                'cellMargin'  => 50
            ];

            $table_header = ['bold' => true];

            $phpWord->addTableStyle('verlaufsplan', $tableStyle);
            $table = $section->addTable('verlaufsplan');

            $schedule = json_decode($sched->content, true);

            $table->addRow();
            $table->addCell(1400)->addText('Zeit', $table_header);
            $table->addCell(3100)->addText('Phase', $table_header);
            $table->addCell(3100)->addText('Handlungsschritte', $table_header);
            $table->addCell(3100)->addText('Methodik', $table_header);
            $table->addCell(3100)->addText('Medien', $table_header);

            foreach ($schedule as $entry) {
                $table->addRow();
                foreach ($entry as $val) {
                     $table->addCell()->addText($val);
                }
            }
        }

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        ob_start();
        $objWriter->save('php://output');
        $data = ob_get_clean();

        return $response->withHeader(
            'Content-Type', 'application/docx'
        )->withHeader(
            'Content-Disposition', 'attachment; filename="Unterrichtsplan_'. $plan->name  .'.docx"'
        )->write($data);
    }
}
