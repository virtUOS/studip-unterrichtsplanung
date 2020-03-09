<?php

class AddTextfieldSorting extends Migration
{
    public function description()
    {
        return 'add tables for plugin Unterrichtsplanung';
    }

    public function up()
    {
        $db = DBManager::get();

        $db->exec("ALTER TABLE `du_textfields`
            ADD COLUMN `position` int DEFAULT 0 NOT NULL
            AFTER `metadata`");

        SimpleORMap::expireTableScheme();
    }

    public function down()
    {
    }
}
