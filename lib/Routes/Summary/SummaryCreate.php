<?php

namespace Unterrichtsplanung\Routes\Summary;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Summary;
use Unterrichtsplanung\Models\Plans;

class SummaryCreate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['structures_id', 'text', 'plans_id']);

        $plan = Plans::find($json['plans_id']);

        if ($plan->user_id != $user->id) {
                throw new Error('Access denied!', 403);
        }

        $summary = Summary::create([
            'structures_id' => $json['structures_id'],
            'text'          => $json['text'],
            'plans_id'      => $json['plans_id'],
            'metadata'      => $json['metadata'] ?: null
        ]);

        return $this->createResponse($summary->toArray(), $response);
    }
}
