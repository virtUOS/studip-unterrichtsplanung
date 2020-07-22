<?php

class IndexController extends Unterrichtsplanung\Controller
{
    public function index_action()
    {
        Navigation::activateItem('/unterrichtsplanung/index');
        PageLayout::addStylesheet($this->plugin->getPluginURL().'/assets/css/unterrichtsplanung.css');
        $this->body_id = 'Unterrichtsplanung-index';
        // starting point for vue app
        $this->getHelpbarContent();
    }

    protected function getHelpbarContent()
    {
        $description = 'Das Tool „Unterrichtsplanung“ hilft Ihnen dabei, die Strukturplanung von Unterricht zu erlernen und eigene Unterrichtsentwürfe anzulegen, die Sie anschließend in verschiedene Dateiformate exportieren können.';
        Helpbar::get()->addPlainText('', $description, '');
        $description2 = 'In diesem Tool können Sie berücksichtigte Zusammenhänge (Interdependenzen) zwischen den einzelnen Planungsbereichen markieren, damit Sie jederzeit den Überblick darüber behalten, welche Zusammenhänge Sie schon bedacht haben. Außerdem werden Ihnen Informationsbausteine mit wichtigen Hinweisen zu den Elementen von Unterrichtsentwürfen angeboten.';
        Helpbar::get()->addPlainText('', $description2, '');
    }
}
