<?php

namespace Unterrichtsplanung\Models;

class Textfields extends \SimpleOrMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_textfields';

        parent::configure($config);
    }
}
