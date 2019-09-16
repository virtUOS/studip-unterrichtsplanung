<?php

namespace DSM;

class Routemap
{
    public function __construct(\Slim\App $app)
    {
        $this->app = $app;
    }

    public function __invoke()
    {
        $this->app->group('', [$this, 'authenticatedRoutes'])
            ->add(new Middlewares\Authentication())
            ->add(new Middlewares\Authority());
        // $this->app->group('', [$this, 'unauthenticatedRoutes']);
    }

    public function authenticatedRoutes()
    {
        $this->app->get('/', Routes\Areas\AresList::class);
    }

    public function unauthenticatedRoutes()
    {

    }
}
