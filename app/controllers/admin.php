<?php

class AdminController extends Unterrichtsplanung\Controller
{
    public function index_action()
    {
        Navigation::activateItem('/unterrichtsplanung/admin');
        PageLayout::addStylesheet($this->plugin->getPluginURL().'/assets/css/unterrichtsplanung-admin.css');
        $this->body_id = 'Unterrichtsplanung-admin';
        // starting point for vue app
    }
}
