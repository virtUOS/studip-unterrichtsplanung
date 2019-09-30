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
        $json = $this->getRequestData($request, ['name']);

        $interdep = Interdeps::find($args['id']);

        if ($interdep) {
            $interdep->setData([
                'name'     => $json['name']
            ]);

            $interdep->store();

            return $this->createResponse($interdep->toArray(), $response);
        } else {
            throw new Error('Template not found', 404);
        }
    }
}
