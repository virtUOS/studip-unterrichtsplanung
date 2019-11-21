<?php

namespace Unterrichtsplanung\Routes\Infotexts;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Infotexts;

class InfotextsDelete extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $infotext = Infotexts::find($args['id']);

        if ($infotext) {
            $infotext->delete();

            return $this->createResponse($infotext->toArray(), $response);
        } else {
            throw new Error('Template not found', 404);
        }
    }
}
