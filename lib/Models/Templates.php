<?php

namespace Unterrichtsplanung\Models;

class Templats extends \SimpleOrMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_templates';

        parent::configure($config);
    }
}
