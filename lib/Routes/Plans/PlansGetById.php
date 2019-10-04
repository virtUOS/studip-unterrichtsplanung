<?php

namespace Unterrichtsplanung\Routes\Plans;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Plans;

class PlansGetById extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $plan = Plans::find($args['id']);

        if ($plan) {
            if ($plan->user_id != $user->id) {
                throw new Error('Access denied!', 403);
            }

            return $this->createResponse($this->transform($plan), $response);
        } else {
            throw new Error('Textfield not found', 404);
        }
    }
}
