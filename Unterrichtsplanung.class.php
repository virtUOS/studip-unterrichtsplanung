<?php

use Unterrichtsplanung\AppFactory;
use Unterrichtsplanung\RouteMap;

class Unterrichtsplanung extends \StudIPPlugin implements \SystemPlugin
{
    const rolename = 'Unterrichtsplanung_Admin';

    public function __construct()
    {
        parent::__construct();

        if ($GLOBALS['perm']->have_perm('autor')) {
            $main = new Navigation(_('Unterrichtsplanung'));
            $main->setImage(Icon::create('topic'));
            $main->setURL(PluginEngine::getURL('unterrichtsplanung/index'));
            $main->setDescription(_('Erlernen der Strukturplanung von Unterricht und anlegen eigener Unterrichtsentwürfe'));

            $overview = new Navigation(_('Unterrichtsplanung'));
            $overview->setURL(PluginEngine::getURL('unterrichtsplanung/index'));

            $start = clone $main;

            $main->addSubNavigation('index', $overview);

            if ($GLOBALS['perm']->have_perm('root')
                || \RolePersistence::isAssignedRole(
                    $GLOBALS['user']->user_id,
                    'Unterrichtsplanung_Admin'
                )
            ) {
                $admin = new Navigation(_('Unterrichtsplanung Administration'));
                $admin->setURL(PluginEngine::getURL('unterrichtsplanung/admin'));

                $main->addSubNavigation('admin', $admin);
                Navigation::addItem('/contents/unterrichtsplanung', $main);
            } else {
                Navigation::addItem('/contents/unterrichtsplanung', $start);
            }

            Navigation::addItem('contents/unterrichtsplanung', $main);

            PageLayout::setHelpKeyword('Basis/Unterrichtsplanung'); // Hilfeseite im Hilfewiki
        }
    }

    public static function onEnable($plugin_id)
    {
        $stmt = DBManager::get()->prepare('SELECT * FROM roles WHERE rolename = ?');
        $stmt->execute([self::rolename]);

        $role_id = $stmt->fetchColumn();
        RolePersistence::assignPluginRoles($plugin_id, [$role_id, 7]);
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function perform($unconsumedPath)
    {
        require __DIR__.'/composer_modules/autoload.php';

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
            $dispatcher->dispatch($unconsumedPath);
        }
    }
}
