<?php

class AddNewStructures extends Migration
{
    public function description()
    {
        return 'add new structure elements for plugin Unterrichtsplanung';
    }

    public function up()
    {
        $db = DBManager::get();

        $db->exec("
        INSERT INTO `du_structures` (id, parent_id, name, mkdate, chdate)
        VALUES ('36', '6', 'Geräte und Materialien', '', '')
        ");

        $db->exec("
        INSERT INTO `du_structures` (id, parent_id, name,  mkdate, chdate)
        VALUES ('37', '1', 'Stellung fer Stunde in der Unterrichtseinheit', '', '')
        ");

        SimpleORMap::expireTableScheme();
    }

    public function down()
    {
    }
}