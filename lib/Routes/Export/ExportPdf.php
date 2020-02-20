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

        $document->dispatch('Unterrichtsplan_' . $plan->name);
        die;
    }
}
