<?php

namespace Unterrichtsplanung\Routes\Summary;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Summary;

class SummaryUpdate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['structures_id', 'text', 'plans_id']);

        $summary = Summary::find($args['id']);

        if ($summary) {
            if ($summary->plans->user_id != $user->id) {
                throw new Error('Access denied!', 403);
            }

            $summary->setData([
                'structures_id' => $json['structures_id'],
                'text'          => $json['text'],
                'plans_id'      => $json['plans_id'],
                'metadata'      => $json['metadata'] ?: null
            ]);

            $summary->store();

            return $this->createResponse($summary->toArray(), $response);
        } else {
            throw new Error('Textfield not found', 404);
        }
    }
}
