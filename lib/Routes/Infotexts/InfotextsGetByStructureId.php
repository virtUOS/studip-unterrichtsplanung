<?php

namespace Unterrichtsplanung\Routes\Infotexts;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Infotexts;

class InfotextsGetByStructureId extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $infotexts = Infotexts::findBySQL('structures_id = ?', [$args['structures_id']]);

        if (!empty($infotexts)) {
            return $this->createResponse($this->toArray($infotexts), $response);
        } else {
            return $this->createEmptyResponse($response);
        }
    }
}
