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
            ->add(new Middlewares\AdminPerms($container))
            ->add(new Middlewares\Authentication($container[StudipServices::AUTHENTICATOR]))
            ->add(new Middlewares\RemoveTrailingSlashes);
    }

    public function authenticatedRoutes()
    {
        $this->app->get('/user', Routes\Users\UsersShow::class);

        $this->app->get('/plans', Routes\Plans\PlansList::class);
        $this->app->post('/plans', Routes\Plans\PlansCreate::class);
        $this->app->put('/plans/{id}', Routes\Plans\PlansUpdate::class);

        $this->app->get('/templates', Routes\Templates\TemplatesList::class);

        $this->app->get('/structures', Routes\Structures\StructuresList::class);

        $this->app->get('/structures/{structure_id}/textfields', Routes\Textfields\TextfieldsGetByStructureId::class);

        $this->app->get('/infotexts', Routes\Infotexts\InfotextsList::class);

        $this->app->get('/interdeps', Routes\Interdeps\InterdepsList::class);
        $this->app->post('/interdeps', Routes\Interdeps\InterdepsCreate::class);
        $this->app->put('/interdeps/{id}', Routes\Interdeps\InterdepsUpdate::class);

        $this->app->get('/textfields/{id}', Routes\Textfields\TextfieldsGetById::class);
        $this->app->post('/textfields', Routes\Textfields\TextfieldsCreate::class);
        $this->app->put('/textfields/{id}', Routes\Textfields\TextfieldsUpdate::class);
    }

    public function adminRoutes()
    {
        $this->app->post('/templates', Routes\Templates\TemplatesCreate::class);
        $this->app->put('/templates/{id}', Routes\Templates\TemplatesUpdate::class);

        $this->app->post('/structures', Routes\Structures\StructuresCreate::class);
        $this->app->put('/structures/{id}', Routes\Structures\StructuresUpdate::class);

        $this->app->post('/infotexts', Routes\Infotexts\InfotextsCreate::class);
        $this->app->put('/infotexts/{id}', Routes\Infotexts\InfotextsUpdate::class);
    }
}
