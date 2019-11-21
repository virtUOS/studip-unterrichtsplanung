<?php

namespace Unterrichtsplanung\Routes\Structures;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Structures;

class StructuresDelete extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $structure = Structures::find($args['id']);

        if ($structure) {
            $structure->delete();

            return $this->createResponse($structure->toArray(), $response);
        } else {
            throw new Error('Template not found', 404);
        }
    }
}
