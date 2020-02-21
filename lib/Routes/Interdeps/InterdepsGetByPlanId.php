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

class InterdepsGetByPlanId extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $interdeps = Interdeps::findBySQL('plans_id = ?', [
            $args['id']
        ]);

        $plan = Plans::find($args['id']);

        if ($plan->user_id != $user->id) {
            throw new Error('Access denied!', 403);
        }

        if (!empty($interdeps)) {
            $ret = [];
            foreach ($interdeps as $dep) {
                $ret[$dep->structures_id] = json_decode($dep->references);
            }

            return $this->createResponse($ret, $response);
        } else {
            $this->createResponse([], $response);
        }

    }
}
