<?php

namespace Unterrichtsplanung\Models;

class Structures extends \SimpleOrMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_structures';

        parent::configure($config);
    }
}
