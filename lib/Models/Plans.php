<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;

class Plans extends \SimpleOrMap
{
    use RelationshipTrait;

    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_plans';

        $config['has_many']['textfields'] = [
            'class_name'        => Textfields::class,
            'assoc_foreign_key' => 'plans_id'
        ];

        parent::configure($config);
    }

    public function getRelationships()
    {
        $data = [];

        $data['textfields']['links']['related'] =
            $this->getRelLink('plans/' . $this->id . '/textfields');

        return $data;
    }
}
