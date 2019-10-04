<?php

namespace Unterrichtsplanung\Routes\Textfields;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Textfields;
use Unterrichtsplanung\Models\Plans;

class TextfieldsGetByPlansId extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $plan = Plans::find($args['id']);

        if ($plan && sizeof($plan->textfields)) {
            if ($plan->user_id != $user->id) {
                throw new Error('Access denied!', 403);
            }

            return $this->createResponse($this->toArray($plan->textfields), $response);
        } else {
            return $this->createEmptyResponse($response);
        }
    }
}
