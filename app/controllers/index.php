<?php

class IndexController extends Unterrichtsplanung\Controller
{
    public function index_action()
    {
        Navigation::activateItem('/unterrichtsplanung/index');
        PageLayout::addStylesheet($this->plugin->getPluginURL().'/assets/css/unterrichtsplanung.css');
        $this->body_id = 'Unterrichtsplanung-index';
        // starting point for vue app
    }
}
