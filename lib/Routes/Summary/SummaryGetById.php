<?php

namespace Unterrichtsplanung\Routes\Summary;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Summary;

class SummaryGetById extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $textfield = Summary::find($args['id']);

        if ($textfield) {
            if ($textfield->plans->user_id != $user->id) {
                throw new Error('Access denied!', 403);
            }

            return $this->createResponse($textfield->toArray(), $response);
        } else {
            throw new Error('Textfield not found', 404);
        }
    }
}
