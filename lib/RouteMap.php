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
            ->add(new Middlewares\Authentication($container[StudipServices::AUTHENTICATOR]))
            ->add(new Middlewares\RemoveTrailingSlashes);

        $this->app->group('', [$this, 'adminRoutes'])
            ->add(new Middlewares\Authentication($container[StudipServices::AUTHENTICATOR]))
            ->add(new Middlewares\RemoveTrailingSlashes)
            ->add(new Middlewares\AdminPerms);
    }

    public function authenticatedRoutes()
    {
        $this->app->get('/user', Routes\Users\UsersShow::class);

        $this->app->get('/plans', Routes\Plans\PlansList::class);
        $this->app->post('/plans', Routes\Plans\PlansCreate::class);
        $this->app->put('/plans/{id}', Routes\Plans\PlansUpdate::class);
    }

    public function adminRoutes()
    {
        $this->app->get('/templates', Routes\Templates\TemplatesList::class);
        $this->app->post('/templates', Routes\Templates\TemplatesCreate::class);
        $this->app->put('/templates/{id}', Routes\Templates\TemplatesUpdate::class);
    }
}
