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

        $db->exec("REPLACE INTO `du_structures` (`id`, `parent_id`, `name`) VALUES
            (1, 0, 'Situative Voraussetzungen'),
            (2, 0, 'Individuelle Voraussetzungen'),
            (3, 0, 'Intentionalität'),
            (4, 0, 'Inhalt'),
            (5, 0, 'Methodik'),
            (6, 0, 'Medien'),
            (7, 1, 'Klassenkomposition'),
            (8, 1, 'Klassenklima'),
            (9, 1, 'Raum und Zeit'),
            (10, 1, 'Schule'),
            (11, 1, 'Lehrplanvorgaben'),
            (12, 1, 'Lehrer*innen-Schüler*innen-Beziehung'),
            (13, 2, 'Vorwissen'),
            (14, 2, 'Motivation'),
            (15, 2, 'Interesse'),
            (16, 2, 'Einstellungen'),
            (17, 2, 'Psychomotorische Lernvoraussetzungen'),
            (18, 2, 'Lern- und Arbeitsverhalten'),
            (19, 2, 'Sozialverhalten'),
            (20, 3, 'Richtziel'),
            (21, 3, 'Grobziel'),
            (22, 3, 'Feinziel'),
            (23, 4, 'Sachanalyse'),
            (24, 4, 'Didaktische Analyse'),
            (25, 5, 'Makroebene'),
            (26, 5, 'Mesoebene'),
            (27, 5, 'Mikroebene'),
            (28, 5, 'Aufgaben'),
            (29, 6, 'Art der Sinneserfahrung'),
            (30, 6, 'Funktion'),
            (31, 6, 'Darstellungsform'),
            (32, 24, 'Exemplarische Bedeutung'),
            (33, 24, 'Gegenwartsbedeutung'),
            (34, 24, 'Zukunftsbedeutung'),
            (35, 24, 'Zugänglichkeit')
        ");

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
