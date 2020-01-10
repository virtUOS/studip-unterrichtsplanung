<?php

namespace Unterrichtsplanung\Routes\Textfields;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Textfields;

class TextfieldsGetByPlanAndStructureId extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $textfields = Textfields::findBySQL('structures_id = ?
            AND plans_id = ?', [$args['structures_id'], $args['plans_id']]);

        if (!empty($textfields)) {
            return $this->createResponse($this->toArray($textfields), $response);
        } else {
            return $this->createEmptyResponse($response);
        }
    }
}
