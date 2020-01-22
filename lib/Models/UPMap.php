<?php

namespace Unterrichtsplanung\Models;

class UPMap extends \SimpleORMap
{
    protected static function configure($config = [])
    {
        $config['registered_callbacks']['before_create'][] = 'cbSetMkdate';

        $config['registered_callbacks']['before_store'][] = 'cbSetChdate';

        parent::configure($config);
    }

    function cbSetMkdate()
    {
        $this->mkdate = date('Y-m-d H:i:s');
    }

    function cbSetChdate()
    {
        $this->chdate = date('Y-m-d H:i:s');
    }

    function isFieldDirty($field)
    {
        if ($field == 'chdate') {
            return true;
        }

        return parent::isFieldDirty($field);
    }
}
