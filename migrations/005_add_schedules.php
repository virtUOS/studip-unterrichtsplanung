<?php

class AddSchedules extends Migration
{
    public function description()
    {
        return 'add table du_Schedules for plugin Unterrichtsplanung';
    }

    public function up()
    {
        $db = DBManager::get();

        $db->exec('CREATE TABLE IF NOT EXISTS `du_schedules` (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `plans_id` int(11) NOT NULL DEFAULT 0,
          `content` text NOT NULL,
          `mkdate` DATETIME DEFAULT 0 NOT NULL,
          `chdate` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL
        )');

        SimpleORMap::expireTableScheme();
    }

    public function down()
    {
        $db = DBManager::get();

        foreach ([
            'du_schedules'
        ] as $table) {
            $db->exec(sprintf('DROP TABLE `%s`', $table));
        }

        SimpleORMap::expireTableScheme();
    }
}
