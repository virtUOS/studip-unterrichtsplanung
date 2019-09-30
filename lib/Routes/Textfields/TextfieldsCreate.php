<?php

namespace Unterrichtsplanung\Routes\Textfields;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Textfields;

class TextfieldsCreate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['structures_id', 'text']);

        $textfield = Textfields::create([
            'structures_id' => $json['structures_id'],
            'text'          => $json['text'],
            'user_id'       => $user->id
        ]);

        return $this->createResponse($textfield->toArray(), $response);
    }
}
