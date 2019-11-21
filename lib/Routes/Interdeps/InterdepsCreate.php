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

class InterdepsCreate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['references']);

        $plan = Plans::find($args['plans_id']);

        if ($plan->user_id != $user->id) {
            throw new Error('Access denied!', 403);
        }

        $plan = Plans::find($args['plans_id']);

        if ($plan->user_id != $user->id) {
            throw new Error('Access denied!', 403);
        }

        $interdep = Interdeps::create([
            'structures_id' => $args['structures_id'],
            'plans_id'      => $args['plans_id'],
            'references'    => $json['references']
        ]);

        return $this->createResponse($interdep->toArray(), $response);
    }
}
