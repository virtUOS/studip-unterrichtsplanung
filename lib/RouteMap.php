<?php

namespace Unterrichtsplanung;

class RouteMap
{
    public function __construct(\Slim\App $app)
    {
        $this->app = $app;
    }

    public function __invoke()
    {
        $this->app->group('', [$this, 'authenticatedRoutes'])
            ->add(new Middlewares\Authentication());
    }

    public function authenticatedRoutes()
    {
        $this->app->get('/areas', Routes\Areas\AreasList::class);
    }
}
