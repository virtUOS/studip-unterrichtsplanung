<?php

namespace Unterrichtsplanung\Models;

class Infotexts extends \SimpleOrMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_infotexts';

        parent::configure($config);
    }
}
