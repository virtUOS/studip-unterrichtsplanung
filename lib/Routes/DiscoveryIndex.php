<?php

namespace Unterrichtsplanung\Routes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\UnterrichtsplanungTrait;

class DiscoveryIndex extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        $routes = $this->container->get('router')->getRoutes();

        foreach ($routes as $id => $route) {
            $data[] = [
                'type' => 'slim-routes',
                'id'   => $id,
                'attributes' => [
                    'methods' => $route->getMethods(),
                    'pattern' => $route->getPattern()
                ]
            ];
        }
        return $this->createResponse(['data' => $data], $response);
    }
}
