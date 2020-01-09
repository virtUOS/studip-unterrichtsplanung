<?php

class AddSummary extends Migration
{
    public function description()
    {
        return 'add table for summary';
    }

    public function up()
    {
        $db = DBManager::get();

        $db->exec('CREATE TABLE IF NOT EXISTS `du_summary` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `plans_id` int NOT NULL,
          `structures_id` int NOT NULL,
          `text` text NOT NULL,
          `metadata` text NULL,
          `mkdate` DATETIME DEFAULT 0 NOT NULL,
          `chdate` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL
        )');

        SimpleORMap::expireTableScheme();
    }

    public function down()
    {
        $db = DBManager::get();

        foreach ([
            'du_summary'
        ] as $table) {
            $db->exec(sprintf('DROP TABLE IF EXISTS `%s`', $table));
        }

        SimpleORMap::expireTableScheme();
    }
}
