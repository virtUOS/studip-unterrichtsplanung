<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;

class Infotexts extends \SimpleOrMap
{
    use RelationshipTrait;

    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_infotexts';

        parent::configure($config);
    }

    public function getRelationships()
    {
        return [];
    }
}
