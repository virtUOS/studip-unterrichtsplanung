<?php

namespace Unterrichtsplanung\Routes\Interdeps;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Interdeps;

class InterdepsUpdate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['references']);

        $interdep = Interdeps::findOneBySQL('structures_id = ? AND plans_id = ?', [
            $args['structures_id'], $args['plans_id']
        ]);

        if (!$interdep) {
            $interdep = new Interdeps;
            $interdep->structures_id = $args['structures_id'];
            $interdep->plans_id       = $args['plans_id'];
        }

        if ($interdep) {
            if ($interdep->plans->user_id != $user->id) {
                throw new Error('Access denied!', 403);
            }

            $interdep->setData([
                'structures_id' => $args['structures_id'],
                'plans_id'      => $args['plans_id'],
                'references'    => $json['references']
            ]);

            $interdep->store();

            return $this->createResponse($interdep->toArray(), $response);
        } else {
            throw new Error('Interdep not found', 404);
        }
    }
}
