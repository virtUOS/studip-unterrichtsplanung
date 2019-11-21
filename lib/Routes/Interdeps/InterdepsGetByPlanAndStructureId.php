<?php

namespace Unterrichtsplanung\Routes\Interdeps;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Interdeps;
use Unterrichtsplanung\Models\Plans;

class InterdepsGetByPlanAndStructureId extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $interdeps = Interdeps::findBySQL('structures_id = ? AND plans_id = ?', [
            $args['structures_id'], $args['plans_id']
        ]);

        $plan = Plans::find($args['plans_id']);

        if ($plan->user_id != $user->id) {
            throw new Error('Access denied!', 403);
        }

        if (!empty($interdeps)) {
            return $this->createResponse($this->toArray($interdeps), $response);
        } else {
            $this->createResponse([], $response);
        }

    }
}
