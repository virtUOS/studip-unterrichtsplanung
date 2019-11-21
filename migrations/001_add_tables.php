<?php

class AddTables extends Migration
{
    public function description()
    {
        return 'add tables for plugin Unterrichtsplanung';
    }

    public function up()
    {
        $db = DBManager::get();

        $db->exec('CREATE TABLE IF NOT EXISTS `du_infotexts` (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `structures_id` int(11) NOT NULL DEFAULT 0,
          `templates_id` int(11) NOT NULL DEFAULT 0,
          `text` text NOT NULL,
          `mkdate` DATETIME DEFAULT 0 NOT NULL,
          `chdate` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_textfields` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `plans_id` int NOT NULL,
          `structures_id` int NOT NULL,
          `text` text NOT NULL,
          `metadata` text NULL,
          `mkdate` DATETIME DEFAULT 0 NOT NULL,
          `chdate` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_structures` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `parent_id` int NOT NULL DEFAULT 0,
          `name` varchar(255) NOT NULL,
          `mkdate` DATETIME DEFAULT 0 NOT NULL,
          `chdate` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_interdeps` (
          `plans_id` int NOT NULL,
          `structures_id` int NOT NULL,
          `references` text NOT NULL,
          `mkdate` DATETIME DEFAULT 0 NOT NULL,
          `chdate` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL,
          PRIMARY KEY `structures_id_user_id` (`plans_id`, `structures_id`)
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_plans` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `user_id` varchar(32) NOT NULL,
          `name` varchar(255) NOT NULL,
          `templates_id` int NOT NULL,
          `metadata` text NULL,
          `mkdate` DATETIME DEFAULT 0 NOT NULL,
          `chdate` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_templates` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `name` varchar(32) NOT NULL,
          `mkdate` DATETIME DEFAULT 0 NOT NULL,
          `chdate` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL
        )');

        SimpleORMap::expireTableScheme();
    }

    public function down()
    {
        $db = DBManager::get();

        foreach ([
            'du_infotexts', 'du_textfields', 'du_structures',
            'du_interdeps', 'du_plans', 'du_templates'
        ] as $table) {
            $db->exec(sprintf('DROP TABLE IF EXISTS `%s`', $table));
        }

        SimpleORMap::expireTableScheme();
    }
}
