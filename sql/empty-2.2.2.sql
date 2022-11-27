
-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponents`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponents`;
CREATE TABLE `glpi_plugin_archisw_swcomponents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL default '0',
  `plugin_archisw_swcomponents_id` int(11) NOT NULL DEFAULT '0',
  `shortname` varchar(20) COLLATE utf8mb4_unicode_ci,
  `completename` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `level` int(11) NOT NULL DEFAULT '0',
  `sons_cache` longtext COLLATE utf8mb4_unicode_ci,
  `ancestors_cache` longtext COLLATE utf8mb4_unicode_ci,
  `plugin_archisw_swcomponenttypes_id` INT(11) NOT NULL default '0' COMMENT 'swcomponent type : Custom Development, Commercial Off The Shelf, ...' ,
  `plugin_archisw_swcomponentstates_id` INT(11) NOT NULL default '0' COMMENT 'swcomponent status : in development, in use ...' ,
  `statedate` DATETIME NULL COMMENT 'validity date of swcomponent status',
  `plugin_archisw_swcomponenttechnics_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_plugin_webapplications_webapplicationtechnics (id)',
  `users_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_users (id)',
  `groups_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
  `suppliers_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_suppliers (id)',
  `manufacturers_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_manufacturers (id)',
  `locations_id` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_locations (id)',
  `version` VARCHAR(255) collate utf8mb4_unicode_ci default NULL,
  `startyear` VARCHAR(8) collate utf8mb4_unicode_ci default NULL COMMENT 'year of starting in production' ,
  `plugin_archisw_swcomponentusers_id` INT(11) NOT NULL default '0' COMMENT 'number of users' ,
  `plugin_archisw_swcomponentslas_id` INT(11) NOT NULL default '0' COMMENT 'Service Level Agreement' ,
  `plugin_archisw_swcomponentdbs_id` INT(11) NOT NULL default '0' COMMENT 'Data repository' ,
  `plugin_archisw_swcomponentinstances_id` INT(11) NOT NULL default '0' COMMENT 'Instances typology (dev+qa+prod, qa+prod, ...)' ,
  `plugin_archisw_swcomponenttargets_id` INT(11) NOT NULL default '0' COMMENT 'Target user segments (department A, B, A+B, ...)' ,
  `plugin_archisw_swcomponentlicenses_id` INT(11) NOT NULL default '0' COMMENT 'License type (Named user, ...)' ,
  `plugin_archisw_standards_id` INT(11) NOT NULL default '0' COMMENT 'Standard status (Standard, Not standard, ...)' ,
  `address` varchar(255) collate utf8mb4_unicode_ci default NULL,
  `address_qa` varchar(255) collate utf8mb4_unicode_ci default NULL,
  `health_check` varchar(255) collate utf8mb4_unicode_ci default NULL,
  `repo` varchar(255) collate utf8mb4_unicode_ci default null,
  `date_mod` datetime default NULL,
  `is_helpdesk_visible` int(11) NOT NULL default '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_archisw_swcomponents_id`,`name`),
  KEY `plugin_archisw_swcomponenttechnics_id` (`plugin_archisw_swcomponenttechnics_id`),
  KEY `plugin_archisw_swcomponenttypes_id` (`plugin_archisw_swcomponenttypes_id`),
  KEY `plugin_archisw_swcomponentstates_id` (`plugin_archisw_swcomponentstates_id`),
  KEY `plugin_archisw_swcomponentusers_id` (`plugin_archisw_swcomponentusers_id`),
  KEY `plugin_archisw_swcomponentslas_id` (`plugin_archisw_swcomponentslas_id`),
  KEY `plugin_archisw_swcomponentdbs_id` (`plugin_archisw_swcomponentdbs_id`),
  KEY `plugin_archisw_swcomponentinstances_id` (`plugin_archisw_swcomponentinstances_id`),
  KEY `plugin_archisw_swcomponenttargets_id` (`plugin_archisw_swcomponenttargets_id`),
  KEY `plugin_archisw_swcomponentlicenses_id` (`plugin_archisw_swcomponentlicenses_id`),
  KEY `users_id` (`users_id`),
  KEY `groups_id` (`groups_id`),
  KEY `suppliers_id` (`suppliers_id`),
  KEY `manufacturers_id` (`manufacturers_id`),
  KEY `locations_id` (`locations_id`),
  KEY `plugin_archisw_standards_id` (`plugin_archisw_standards_id`),
  KEY date_mod (date_mod),
  KEY is_helpdesk_visible (is_helpdesk_visible),
  KEY `is_deleted` (`is_deleted`),
  KEY `plugin_archisw_swcomponents_id` (`plugin_archisw_swcomponents_id`)
) AUTO_INCREMENT=756 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- ----------------------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponents_items`
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponents_items`;
CREATE TABLE `glpi_plugin_archisw_swcomponents_items` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`plugin_archisw_swcomponents_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archisw_swcomponents (id)',
	`items_id` int(11) NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemtype (id)',
   `itemtype` varchar(100) collate utf8mb4_unicode_ci NOT NULL COMMENT 'see .class.php file',
	`plugin_archisw_swcomponents_itemroles_id` int(11) NOT NULL default '0' COMMENT 'role of the relation (f.i Dev, QA, Prod, ...)',
	`comment` text COMMENT 'comment about the relation',
	PRIMARY KEY  (`id`),
	UNIQUE KEY `unicity` (`plugin_archisw_swcomponents_id`,`items_id`,`itemtype`,`plugin_archisw_swcomponents_itemroles_id`),
  KEY `FK_device` (`items_id`,`itemtype`),
  KEY `item` (`itemtype`,`items_id`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponents_itemroles`
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponents_itemroles`;
CREATE TABLE `glpi_plugin_archisw_swcomponents_itemroles` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`itemtype` varchar(100) collate utf8mb4_unicode_ci NOT NULL COMMENT 'see .class.php file',
	`name` VARCHAR(45) NOT NULL ,
	`comment` VARCHAR(45) NULL ,
	PRIMARY KEY  (`id`),
	UNIQUE INDEX `unicity` (`itemtype`,`name`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO glpi_plugin_archisw_swcomponents_itemroles (id,itemtype,name,comment) VALUES (1,'Computer','1-DEV','Development system');
INSERT INTO glpi_plugin_archisw_swcomponents_itemroles (id,itemtype,name,comment) VALUES (2,'Computer','3-QA','Quality Assurance');
INSERT INTO glpi_plugin_archisw_swcomponents_itemroles (id,itemtype,name,comment) VALUES (3,'Computer','5-PROD','Production System');
INSERT INTO glpi_plugin_archisw_swcomponents_itemroles (id,itemtype,name,comment) VALUES (4,'Computer','4-PREPROD','Pre-production system');
INSERT INTO glpi_plugin_archisw_swcomponents_itemroles (id,itemtype,name,comment) VALUES (5,'Computer','2-INTEG','Integration');
INSERT INTO glpi_plugin_archisw_swcomponents_itemroles (id,itemtype,name,comment) VALUES (6,'PluginArchiswSwcomponent','(To be/Being) Replaced by','Which application will replace this one ?');

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_profiles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_profiles`;
CREATE TABLE `glpi_plugin_archisw_profiles` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`profiles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
	`archisw` char(1) collate utf8mb4_unicode_ci default NULL,
	`open_ticket` char(1) collate utf8mb4_unicode_ci default NULL,
	PRIMARY KEY  (`id`),
	KEY `profiles_id` (`profiles_id`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponentstates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentstates`;
CREATE  TABLE `glpi_plugin_archisw_swcomponentstates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `comment` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `glpi_plugin_archisw_swcomponentstates_name` (`name` ASC) )
 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponentstates` ( `id` , `name` , `comment` )  VALUES (1,'In Development','In development');
INSERT INTO `glpi_plugin_archisw_swcomponentstates` ( `id` , `name` , `comment` )  VALUES (2,'In Use','In Use');
INSERT INTO `glpi_plugin_archisw_swcomponentstates` ( `id` , `name` , `comment` )  VALUES (3,'To be removed','To be removed');
INSERT INTO `glpi_plugin_archisw_swcomponentstates` ( `id` , `name` , `comment` )  VALUES (4,'Removed','Removed');

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponenttypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponenttypes`;
CREATE  TABLE `glpi_plugin_archisw_swcomponenttypes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `entities_id` INT(11) NOT NULL default '0',
  `is_recursive` tinyint(1) NOT NULL default '0',
  `name` VARCHAR(45) NOT NULL ,
  `plugin_archisw_swcomponenttypes_id` INT(11) NOT NULL default '0',
  `completename` text COLLATE utf8mb4_unicode_ci,
  `comment` VARCHAR(45) NULL ,
  `level` int(11) NOT NULL DEFAULT '0',
  `sons_cache` longtext COLLATE utf8mb4_unicode_ci,
  `ancestors_cache` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `glpi_plugin_archisw_swcomponenttype_name` (`name` ASC) )
 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponenttypes` (`id`, `name`, `completename`, `comment`)  VALUES (1,'Custom','Custom','Custom Development');
INSERT INTO `glpi_plugin_archisw_swcomponenttypes` (`id`, `name`, `completename`, `comment`)  VALUES (2,'Package','Package','Commercial Off The Shelf');

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponenttechnics`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponenttechnics`;
CREATE TABLE `glpi_plugin_archisw_swcomponenttechnics` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `comment` text collate utf8mb4_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponenttechnics` VALUES ('1', 'Asp','');
INSERT INTO `glpi_plugin_archisw_swcomponenttechnics` VALUES ('2', 'Cgi','');
INSERT INTO `glpi_plugin_archisw_swcomponenttechnics` VALUES ('3', 'Java','');
INSERT INTO `glpi_plugin_archisw_swcomponenttechnics` VALUES ('4', 'Perl','');
INSERT INTO `glpi_plugin_archisw_swcomponenttechnics` VALUES ('5', 'Php','');
INSERT INTO `glpi_plugin_archisw_swcomponenttechnics` VALUES ('6', '.Net','');

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponentusers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentusers`;
CREATE TABLE `glpi_plugin_archisw_swcomponentusers` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `comment` text collate utf8mb4_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponentslas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentslas`;
CREATE TABLE `glpi_plugin_archisw_swcomponentslas` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `comment` text collate utf8mb4_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponentdbs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentdbs`;
CREATE TABLE `glpi_plugin_archisw_swcomponentdbs` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `comment` text collate utf8mb4_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponentdbs` VALUES ('1', 'SQLserver','');
INSERT INTO `glpi_plugin_archisw_swcomponentdbs` VALUES ('2', 'Oracle','');
INSERT INTO `glpi_plugin_archisw_swcomponentdbs` VALUES ('3', 'Mysql','');

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponentinstances`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentinstances`;
CREATE TABLE `glpi_plugin_archisw_swcomponentinstances` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `comment` text collate utf8mb4_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponentinstances` VALUES ('1', 'Integration+QA+Prod','');
INSERT INTO `glpi_plugin_archisw_swcomponentinstances` VALUES ('2', 'QA+Prod','');
INSERT INTO `glpi_plugin_archisw_swcomponentinstances` VALUES ('3', 'Prod','');
INSERT INTO `glpi_plugin_archisw_swcomponentinstances` VALUES ('4', 'QA+multiple Prod','');
INSERT INTO `glpi_plugin_archisw_swcomponentinstances` VALUES ('5', 'Integration+QA+multiple Prod','');

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponenttargets`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponenttargets`;
CREATE TABLE `glpi_plugin_archisw_swcomponenttargets` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `comment` text collate utf8mb4_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponenttargets` VALUES ('1', 'Integrated+Franchise','');
INSERT INTO `glpi_plugin_archisw_swcomponenttargets` VALUES ('2', 'Integrated only','');
INSERT INTO `glpi_plugin_archisw_swcomponenttargets` VALUES ('3', 'Franchise only','');

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponentlicenses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentlicenses`;
CREATE TABLE `glpi_plugin_archisw_swcomponentlicenses` (
	`id` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8mb4_unicode_ci default NULL,
   `comment` text collate utf8mb4_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponentlicenses` VALUES ('1', 'Named user','');
INSERT INTO `glpi_plugin_archisw_swcomponentlicenses` VALUES ('2', 'Concurrent user','');
INSERT INTO `glpi_plugin_archisw_swcomponentlicenses` VALUES ('3', 'Server','');
INSERT INTO `glpi_plugin_archisw_swcomponentlicenses` VALUES ('4', 'Processor','');

INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswSwcomponent','2','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswSwcomponent','6','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswSwcomponent','7','4','0');
	
-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_standards`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_standards`;
CREATE  TABLE `glpi_plugin_archisw_standards` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `comment` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_standards_name` (`name` ASC) )
 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (1,'Standard','Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (2,'Non-Standard','Non-Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (3,'Proposed Standard','Proposed Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (4,'Provisional Standard','Provisional Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (5,'Phasing-Out Standard','Phasing-Out Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (6,'Retired Standard','Retired Standard');

