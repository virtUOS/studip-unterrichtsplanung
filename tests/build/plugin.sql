SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `du_infotexts`;
CREATE TABLE `du_infotexts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `structures_id` int(11) NOT NULL DEFAULT 0,
  `templates_id` int(11) NOT NULL DEFAULT 0,
  `text` text NOT NULL,
  `mkdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `du_interdeps`;
CREATE TABLE `du_interdeps` (
  `plans_id` int(11) NOT NULL,
  `structures_id` int(11) NOT NULL,
  `references` text NOT NULL,
  `mkdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`plans_id`,`structures_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `du_plans`;
CREATE TABLE `du_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `templates_id` int(11) NOT NULL,
  `metadata` text DEFAULT NULL,
  `mkdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `du_schedules`;
CREATE TABLE `du_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plans_id` int(11) NOT NULL DEFAULT 0,
  `content` text NOT NULL,
  `mkdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `du_structures`;
CREATE TABLE `du_structures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `mkdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `du_structures` (`id`, `parent_id`, `name`, `mkdate`, `chdate`) VALUES
(1,	0,	'Situative Voraussetzungen',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(2,	0,	'Individuelle Voraussetzungen',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(3,	0,	'Intentionalität',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(4,	0,	'Inhalt',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(5,	0,	'Methodik',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(6,	0,	'Medien',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(7,	1,	'Klassenkomposition',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(8,	1,	'Klassenklima',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(9,	1,	'Raum und Zeit',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(10,	1,	'Schule',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(11,	1,	'Lehrplanvorgaben',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(12,	1,	'Lehrer*innen-Schüler*innen-Beziehung',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(13,	2,	'Vorwissen',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(14,	2,	'Motivation',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(15,	2,	'Interesse',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(16,	2,	'Einstellungen',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(17,	2,	'Psychomotorische Lernvoraussetzungen',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(18,	2,	'Lern- und Arbeitsverhalten',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(19,	2,	'Sozialverhalten',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(20,	3,	'Richtziel',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(21,	3,	'Grobziel',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(22,	3,	'Feinziel',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(23,	4,	'Sachanalyse',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(24,	4,	'Didaktische Analyse',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(25,	5,	'Makroebene',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(26,	5,	'Mesoebene',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(27,	5,	'Mikroebene',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(28,	5,	'Aufgaben',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(29,	6,	'Art der Sinneserfahrung',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(30,	6,	'Funktion',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(31,	6,	'Darstellungsform',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(32,	24,	'Exemplarische Bedeutung',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(33,	24,	'Gegenwartsbedeutung',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(34,	24,	'Zukunftsbedeutung',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00'),
(35,	24,	'Zugänglichkeit',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00');

DROP TABLE IF EXISTS `du_summary`;
CREATE TABLE `du_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plans_id` int(11) NOT NULL,
  `structures_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `metadata` text DEFAULT NULL,
  `mkdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `du_templates`;
CREATE TABLE `du_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `mkdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `du_textfields`;
CREATE TABLE `du_textfields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plans_id` int(11) NOT NULL,
  `structures_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `metadata` text DEFAULT NULL,
  `mkdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `plugins` (`pluginid`, `pluginclassname`, `pluginpath`, `pluginname`, `plugintype`, `enabled`, `navigationpos`, `dependentonid`, `automatic_update_url`, `automatic_update_secret`) VALUES
(15,	'Unterrichtsplanung',	'elan-ev/Unterrichtsplanung',	'Unterrichtsplanung',	'SystemPlugin',	'yes',	5,	NULL,	NULL,	NULL);

INSERT INTO `roles_plugins` (`roleid`, `pluginid`) VALUES
(1,	15),
(2,	15),
(3,	15),
(4,	15),
(5,	15),
(6,	15),
(7,	15);
