<?php

namespace Unterrichtsplanung\Routes\Plans;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Plans;

class PlansCreate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['templates_id', 'name']);

        $plan = Plans::create([
            'user_id'      => $user->id,
            'name'         => $json['name'],
            'templates_id' => $json['templates_id']
        ]);

        return $this->createResponse($plan->toArray(), $response);
    }
}
