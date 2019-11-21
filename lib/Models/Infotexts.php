<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;
use Unterrichtsplanung\Models\UPMap;

class Infotexts extends UPMap
{
    use RelationshipTrait;

    protected static function configure($config = [])
    {
        $config['db_table'] = 'du_infotexts';

        parent::configure($config);
    }

    public function getRelationships()
    {
        return [];
    }
}
