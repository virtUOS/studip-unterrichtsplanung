<?php

use Unterrichtsplanung\AppFactory;
use Unterrichtsplanung\RouteMap;

class Unterrichtsplanung extends \StudIPPlugin implements \SystemPlugin
{
    public function __construct()
    {
        parent::__construct();


        $main = new Navigation(_('Unterrichtsplanung'));
        $main->setImage(Icon::create('doctoral-cap'));
        $main->setURL(PluginEngine::getURL('unterrichtsplanung'));

        Navigation::addItem('/start/unterrichtsplanung', $main);
        Navigation::addItem('/unterrichtsplanung', $main);

        require __DIR__.'/composer_modules/autoload.php';
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function perform($unconsumedPath)
    {
        if (substr($unconsumedPath, 0, 3) == 'api') {
            $appFactory = new AppFactory();
            $app = $appFactory->makeApp($this);
            $app->group('/unterrichtsplanung/api', new RouteMap($app));
            $app->run();
        } else {
            $trails_root        = $this->getPluginPath() . '/app';
            $dispatcher         = new Trails_Dispatcher($trails_root,
                rtrim(PluginEngine::getURL($this, null, ''), '/'),
                'index');
            $dispatcher->current_plugin = $this;
            $dispatcher->dispatch($unconsumed_path);
        }
    }
}
