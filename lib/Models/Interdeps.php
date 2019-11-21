<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;

class Interdeps extends \SimpleOrMap
{
    use RelationshipTrait;

    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_interdeps';

        $config['belongs_to']['plans'] = [
            'class_name'  => Plans::class,
            'foreign_key' => 'plans_id'
        ];

        parent::configure($config);
    }

    public function getRelationships()
    {
        $data = [];

        $data['plans']['links']['related'] =
            $this->getRelLink('plans/' . $this->plans_id);

        return $data;
    }
}
