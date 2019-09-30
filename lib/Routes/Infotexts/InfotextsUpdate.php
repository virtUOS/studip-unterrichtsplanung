<?php

namespace Unterrichtsplanung\Routes\Infotexts;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Infotexts;

class InfotextsUpdate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $json = $this->getRequestData($request, ['structures_id', 'templates_id']);

        $infotext = Infotexts::find($args['id']);

        if ($infotext) {
            $infotext->setData([
                'structures_id' => $json['structures_id'],
                'templates_id'  => $json['templates_id'],
                'text'          => $json['text'] ?: ''
            ]);

            $infotext->store();

            return $this->createResponse($infotext->toArray(), $response);
        } else {
            throw new Error('Template not found', 404);
        }
    }
}
