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

class TextfieldsCreate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['structures_id', 'text', 'plan_id']);

        $plan = Plans::find($json['plan_id']);

        if ($plan->user_id != $user->id) {
                throw new Error('Access denied!', 403);
        }

        $textfield = Textfields::create([
            'structures_id' => $json['structures_id'],
            'text'          => $json['text'],
            'plans_id'      => $json['plan_id']
        ]);

        return $this->createResponse($textfield->toArray(), $response);
    }
}
