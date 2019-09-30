<?php

namespace Unterrichtsplanung\Routes\Structures;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Structures;

class StructuresCreate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $json = $this->getRequestData($request, ['name']);

        // check, if parent_id exists
        if ($json['parent_id'] && $json['parent_id'] != 0) {
            $structure = Structures::find($json['parent_id']);

            if (!$structure) {
                throw new Error('Parent structure not found!', 404);
            }
        }

        $structure = Structures::create([
            'name'      => $json['name'],
            'parent_id' => $json['parent_id'] ?: 0
        ]);

        return $this->createResponse($structure->toArray(), $response);
    }
}
