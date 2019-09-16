<?php

namespace Unterrichtsplanung;

use Unterrichtsplanung\Providers\StudipServices;

class RouteMap
{
    public function __construct(\Slim\App $app)
    {
        $this->app = $app;
    }

    public function __invoke()
    {
        $container = $this->app->getContainer();

        $this->app->group('', [$this, 'authenticatedRoutes'])
            ->add(new Middlewares\Authentication($container[StudipServices::AUTHENTICATOR]));
    }

    public function authenticatedRoutes()
    {
        $this->app->get('/plans', Routes\Plans\PlansList::class);
        $this->app->post('/plans', Routes\Plans\PlansCreate::class);
    }
}
