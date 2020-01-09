<?php

class AddStructuresData extends Migration
{
    public function description()
    {
        return 'add tables for plugin Unterrichtsplanung';
    }

    public function up()
    {
        $db = DBManager::get();

        $db->exec("INSERT INTO `du_structures` (`id`, `parent_id`, `name`) VALUES
            (1, 0, 'situative Voraussetzungen '),
            (2, 0, 'individuelle Voraussetzungen '),
            (3, 0, 'Intentionalität'),
            (4, 0, 'Inhalt'),
            (5, 0, 'Methodik'),
            (6, 0, 'Medien'),
            (7, 1, 'Klassenkomposition'),
            (8, 1, 'Soziale Situation in der Schulklasse'),
            (9, 1, 'Räumliche und zeitliche Voraussetzungen'),
            (10, 1, 'Situation der Schule'),
            (11, 1, 'Lehrplanvorgaben'),
            (12, 2, 'Kognitive Lernvoraussetzungen'),
            (13, 2, 'affektive Lernvoraussetzungen'),
            (14, 2, 'psychomotorische Lernvoraussetzungen'),
            (15, 2, 'Lern- und Arbeitsverhalten'),
            (16, 2, 'Sozialverhalten'),
            (17, 5, 'Makroebene'),
            (18, 5, 'Mesoebene'),
            (19, 5, 'Mikroebene'),
            (20, 5, 'Aufgaben'),
            (21, 6, 'Art der Sinneserfahrung'),
            (22, 6, 'Funktion'),
            (23, 6, 'Darstellungsform'),
            (24, 4, 'Sachanalyse'),
            (25, 4, 'Didaktische Analyse'),
            (26, 25, 'Exemplarische Bedeutung'),
            (27, 25, 'Zukunftsbedeutung'),
            (28, 25, 'Zugänglichkeit')");

        SimpleORMap::expireTableScheme();
    }

    public function down()
    {
        $db = DBManager::get();

        foreach ([
            'du_structures'
        ] as $table) {
            $db->exec(sprintf('TRUNCATE TABLE `%s`', $table));
        }

        SimpleORMap::expireTableScheme();
    }
}
