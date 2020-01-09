<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;
use Unterrichtsplanung\Models\UPMap;

class Plans extends UPMap
{
    use RelationshipTrait;

    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_plans';

        $config['has_many']['textfields'] = [
            'class_name'        => Textfields::class,
            'assoc_foreign_key' => 'plans_id'
        ];

        $config['has_many']['summary'] = [
            'class_name'        => Summary::class,
            'assoc_foreign_key' => 'plans_id'
        ];

        parent::configure($config);
    }

    public function getRelationships()
    {
        $data = [];

        $data['textfields']['links']['related'] =
            $this->getRelLink('plans/' . $this->id . '/textfields');

        $data['summary']['links']['related'] =
            $this->getRelLink('plans/' . $this->id . '/summary');

        return $data;
    }
}
