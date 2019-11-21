<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;
use Unterrichtsplanung\Models\UPMap;

class Structures extends UPMap
{
    use RelationshipTrait;

    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_structures';

        $config['has_many']['structures'] = [
            'class_name'        => Structures::class,
            'assoc_foreign_key' => 'parent_id'
        ];

        parent::configure($config);
    }

    public function getRelationships()
    {
        $data = [];

        $data['structures']['links']['related'] =
            $this->getRelLink('structures/' . $this->id);

        return $data;
    }
}
