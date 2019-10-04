<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;

class Structures extends \SimpleOrMap
{
    use RelationshipTrait;

    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_structures';

        parent::configure($config);
    }

    public function getRelationships()
    {
        return [];
    }
}
