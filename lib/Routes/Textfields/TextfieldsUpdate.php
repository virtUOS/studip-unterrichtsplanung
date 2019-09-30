<?php

namespace Unterrichtsplanung\Routes\Textfields;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Textfields;

class TextfieldsUpdate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['structures_id', 'text']);

        $textfield = Textfields::find($args['id']);

        if ($textfield) {
            if ($textfield->user_id != $user->id) {
                throw new Error('Access denied!', 403);
            }

            $textfield->setData([
                'structures_id' => $json['structures_id'],
                'text'          => $json['text']
            ]);

            $textfield->store();

            return $this->createResponse($textfield->toArray(), $response);
        } else {
            throw new Error('Textfield not found', 404);
        }
    }
}
