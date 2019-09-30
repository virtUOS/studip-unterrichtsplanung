<?php

namespace Unterrichtsplanung\Routes\Structures;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Structures;

class StructuresUpdate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $json = $this->getRequestData($request, ['name']);

        $structure = Structures::find($args['id']);

        if ($structure) {
            $structure->setData([
                'name'     => $json['name']
            ]);

            $structure->store();

            return $this->createResponse($structure->toArray(), $response);
        } else {
            throw new Error('Template not found', 404);
        }
    }
}
