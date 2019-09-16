<?php

namespace Unterrichtsplanung\Models;

class Plans extends \SimpleOrMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_plans';

        parent::configure($config);
    }
}
