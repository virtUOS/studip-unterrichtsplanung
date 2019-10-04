<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;

class Interdeps extends \SimpleOrMap
{
    use RelationshipTrait;

    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_interdeps';

        parent::configure($config);
    }

    public function getRelationships()
    {
        return [];
    }
}
