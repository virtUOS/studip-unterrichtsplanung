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

        $this->app->get('/discovery', Routes\DiscoveryIndex::class);
    }

    public function authenticatedRoutes()
    {
        $this->app->get('/user', Routes\Users\UsersShow::class);

        $this->app->get('/plans', Routes\Plans\PlansList::class);
        $this->app->get('/plans/{id}', Routes\Plans\PlansGetById::class);
        $this->app->post('/plans', Routes\Plans\PlansCreate::class);
        $this->app->put('/plans/{id}', Routes\Plans\PlansUpdate::class);
        $this->app->delete('/plans/{id}', Routes\Plans\PlansDelete::class);
        $this->app->get('/plans/{id}/textfields', Routes\Textfields\TextfieldsGetByPlansId::class);

        $this->app->get('/templates', Routes\Templates\TemplatesList::class);

        $this->app->get('/structures', Routes\Structures\StructuresList::class);
        $this->app->get('/structures/{id}', Routes\Structures\StructuresGetByParentId::class);

        $this->app->get('/interdeps/{plans_id}/{structures_id}', Routes\Interdeps\InterdepsGetByPlanAndStructureId::class);
        $this->app->post('/interdeps/{plans_id}/{structures_id}', Routes\Interdeps\InterdepsCreate::class);
        $this->app->put('/interdeps/{plans_id}/{structures_id}', Routes\Interdeps\InterdepsUpdate::class);
        $this->app->delete('/interdeps/{plans_id}/{structures_id}', Routes\Interdeps\InterdepsDelete::class);

        $this->app->get('/infotexts', Routes\Infotexts\InfotextsList::class);

        $this->app->get('/textfields/{id}', Routes\Textfields\TextfieldsGetById::class);
        $this->app->post('/textfields', Routes\Textfields\TextfieldsCreate::class);
        $this->app->put('/textfields/{id}', Routes\Textfields\TextfieldsUpdate::class);
        $this->app->delete('/textfields/{id}', Routes\Textfields\TextfieldsDelete::class);
        $this->app->get('/textfields/{plans_id}/{structures_id}', Routes\Textfields\TextfieldsGetByPlanAndStructureId::class);
    }

    public function adminRoutes()
    {
        $this->app->post('/templates', Routes\Templates\TemplatesCreate::class);
        $this->app->put('/templates/{id}', Routes\Templates\TemplatesUpdate::class);
        $this->app->delete('/templates/{id}', Routes\Templates\TemplatesDelete::class);

        $this->app->post('/structures', Routes\Structures\StructuresCreate::class);
        $this->app->put('/structures/{id}', Routes\Structures\StructuresUpdate::class);
        $this->app->delete('/structures/{id}', Routes\Structures\StructuresDelete::class);

        $this->app->post('/infotexts', Routes\Infotexts\InfotextsCreate::class);
        $this->app->put('/infotexts/{id}', Routes\Infotexts\InfotextsUpdate::class);
        $this->app->delete('/infotexts/{id}', Routes\Infotexts\InfotextsDelete::class);
    }
}
