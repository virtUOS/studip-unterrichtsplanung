<?php

namespace Unterrichtsplanung\Routes\Templates;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Templates;

class TemplatesCreate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $json = $this->getRequestData($request, ['name']);

        $template = Templates::create([
            'name'         => $json['name']
        ]);

        return $this->createResponse($template->toArray(), $response);
    }
}
