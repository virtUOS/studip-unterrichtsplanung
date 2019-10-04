<?php

namespace Unterrichtsplanung\Models;

class Textfields extends \SimpleOrMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_textfields';

        $config['belongs_to']['plans'] = [
            'class_name'  => Plans::class,
            'foreign_key' => 'plans_id'
        ];

        parent::configure($config);
    }
}
