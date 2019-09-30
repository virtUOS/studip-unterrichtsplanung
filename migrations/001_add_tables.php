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
          `structures_id` int(11) NOT NULL,
          `templates_id` int(11) NOT NULL DEFAULT 0,
          `text` text NOT NULL
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_textfields` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `structures_id` int NOT NULL,
          `text` text NOT NULL,
          `user_id` varchar(32) NOT NULL
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_structures` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `structures_id` int NOT NULL DEFAULT 0,
          `name` varchar(32) NOT NULL
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_interdeps` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `structures_id_1` int NOT NULL,
          `structures_id_2` int NOT NULL,
          `user_id` varchar(32) NOT NULL
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_plans` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `user_id` varchar(32) NOT NULL,
          `name` varchar(255) NOT NULL,
          `templates_id` int NOT NULL,
          `metadata` text NULL
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS `du_templates` (
          `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `name` varchar(32) NOT NULL
        )');
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
    }
}
