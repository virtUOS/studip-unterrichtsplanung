<?php

namespace Unterrichtsplanung\Models;

use Unterrichtsplanung\RelationshipTrait;
use Unterrichtsplanung\Models\UPMap;

class Summary extends UPMap
{
    use RelationshipTrait;

    protected static function configure($config = array())
    {
        $config['db_table'] = 'du_summary';

        $config['belongs_to']['plans'] = [
            'class_name'  => Plans::class,
            'foreign_key' => 'plans_id'
        ];

        $config['belongs_to']['structures'] = [
            'class_name'  => Structures::class,
            'foreign_key' => 'structures_id'
        ];


        $config['registered_callbacks']['after_create'][]  = 'updatePlan';
        $config['registered_callbacks']['after_store'][]   = 'updatePlan';
        $config['registered_callbacks']['before_delete'][] = 'updatePlan';

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
