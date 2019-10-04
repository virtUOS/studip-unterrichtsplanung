<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;

class Templates extends \SimpleOrMap
{
    use RelationshipTrait;

    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_templates';

        parent::configure($config);
    }

    public function getRelationships()
    {
        return [];
    }
}
