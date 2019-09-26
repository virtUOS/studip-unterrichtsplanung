<?php

class IndexController extends Unterrichtsplanung\Controller
{
    public function index_action()
    {
        Navigation::activateItem('/unterrichtsplanung');
        PageLayout::addStylesheet($this->plugin->getPluginURL().'/assets/css/unterrichtsplanung.css');
        $this->pluginURL = $this->plugin->getPluginURL();
        // starting point for vue app
    }
}
