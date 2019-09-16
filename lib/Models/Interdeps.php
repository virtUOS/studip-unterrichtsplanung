<?php

namespace Unterrichtsplanung\Models;

class Interdeps extends \SimpleOrMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_interdeps';

        parent::configure($config);
    }
}
