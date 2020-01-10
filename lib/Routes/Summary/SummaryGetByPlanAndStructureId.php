<?php

namespace Unterrichtsplanung\Routes\Summary;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Summary;

class SummaryGetByPlanAndStructureId extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $summarys = Summary::findBySQL('structures_id = ?
            AND plans_id = ?', [$args['structures_id'], $args['plans_id']]);

        if (!empty($summarys)) {
            return $this->createResponse($this->toArray($summarys), $response);
        } else {
            return $this->createEmptyResponse($response);
        }
    }
}
