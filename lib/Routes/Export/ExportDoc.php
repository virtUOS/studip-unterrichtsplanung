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

        $html = '<h1>Unterrichtsplan: '. $plan->name .'</h1>';

        foreach ($summaries as $entry) {
            $html .= '<h2>'. ucfirst($entry->structures->name) .'</h2>';
            $html .= $entry['text'];
        }

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

        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

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
