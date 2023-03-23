
-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponents`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponents`;
CREATE TABLE `glpi_plugin_archisw_swcomponents` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL default '0',
  `plugin_archisw_swcomponents_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `shortname` varchar(20) COLLATE utf8_unicode_ci,
  `completename` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `comment` text COLLATE utf8_unicode_ci,
  `level` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `sons_cache` longtext COLLATE utf8_unicode_ci,
  `ancestors_cache` longtext COLLATE utf8_unicode_ci,
  `plugin_archisw_swcomponenttypes_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'swcomponent type : Custom Development, Commercial Off The Shelf, ...' ,
  `plugin_archisw_swcomponentstates_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'swcomponent status : in development, in use ...' ,
  `statedate` DATETIME NULL COMMENT 'validity date of swcomponent status',
  `plugin_archisw_swcomponenttechnics_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_plugin_webapplications_webapplicationtechnics (id)',
  `users_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_users (id)',
  `groups_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
  `suppliers_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_suppliers (id)',
  `manufacturers_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_manufacturers (id)',
  `locations_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_locations (id)',
  `version` VARCHAR(255) collate utf8_unicode_ci default NULL,
  `startyear` VARCHAR(8) collate utf8_unicode_ci default NULL COMMENT 'year of starting in production' ,
  `plugin_archisw_swcomponentusers_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'number of users' ,
  `plugin_archisw_swcomponentslas_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'Service Level Agreement' ,
  `plugin_archisw_swcomponentdbs_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'Data repository' ,
  `plugin_archisw_swcomponentinstances_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'Instances typology (dev+qa+prod, qa+prod, ...)' ,
  `plugin_archisw_swcomponenttargets_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'Target user segments (department A, B, A+B, ...)' ,
  `plugin_archisw_swcomponentlicenses_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'License type (Named user, ...)' ,
  `plugin_archisw_standards_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'Standard status (Standard, Not standard, ...)' ,
  `address` varchar(255) collate utf8_unicode_ci default NULL,
  `address_qa` varchar(255) collate utf8_unicode_ci default NULL,
  `health_check` varchar(255) collate utf8_unicode_ci default NULL,
  `repo` varchar(255) collate utf8_unicode_ci default null,
  `date_mod` datetime default NULL,
  `is_helpdesk_visible` INT(11) UNSIGNED NOT NULL default '1',
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
) AUTO_INCREMENT=756 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- ----------------------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponents_items`
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponents_items`;
CREATE TABLE `glpi_plugin_archisw_swcomponents_items` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`plugin_archisw_swcomponents_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archisw_swcomponents (id)',
	`items_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemtype (id)',
   `itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file',
	`plugin_archisw_swcomponents_itemroles_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'role of the relation (f.i Dev, QA, Prod, ...)',
	`comment` text COMMENT 'comment about the relation',
	PRIMARY KEY  (`id`),
	UNIQUE KEY `unicity` (`plugin_archisw_swcomponents_id`,`items_id`,`itemtype`,`plugin_archisw_swcomponents_itemroles_id`),
  KEY `FK_device` (`items_id`,`itemtype`),
  KEY `item` (`itemtype`,`items_id`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponents_itemroles`
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponents_itemroles`;
CREATE TABLE `glpi_plugin_archisw_swcomponents_itemroles` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file',
	`name` VARCHAR(45) NOT NULL ,
	`comment` VARCHAR(45) NULL ,
	PRIMARY KEY  (`id`),
	UNIQUE INDEX `unicity` (`itemtype`,`name`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



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
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`profiles_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
	`archisw` char(1) collate utf8_unicode_ci default NULL,
	`open_ticket` char(1) collate utf8_unicode_ci default NULL,
	PRIMARY KEY  (`id`),
	KEY `profiles_id` (`profiles_id`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponentstates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentstates`;
CREATE  TABLE `glpi_plugin_archisw_swcomponentstates` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `comment` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `glpi_plugin_archisw_swcomponentstates_name` (`name` ASC) )
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponentstates` ( `id` , `name` , `comment` )  VALUES (1,'In Development','In development');
INSERT INTO `glpi_plugin_archisw_swcomponentstates` ( `id` , `name` , `comment` )  VALUES (2,'In Use','In Use');
INSERT INTO `glpi_plugin_archisw_swcomponentstates` ( `id` , `name` , `comment` )  VALUES (3,'To be removed','To be removed');
INSERT INTO `glpi_plugin_archisw_swcomponentstates` ( `id` , `name` , `comment` )  VALUES (4,'Removed','Removed');

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_swcomponenttypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponenttypes`;
CREATE  TABLE `glpi_plugin_archisw_swcomponenttypes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `entities_id` INT(11) UNSIGNED NOT NULL default '0',
  `is_recursive` tinyint(1) NOT NULL default '0',
  `name` VARCHAR(45) NOT NULL ,
  `plugin_archisw_swcomponenttypes_id` INT(11) UNSIGNED NOT NULL default '0',
  `completename` text COLLATE utf8_unicode_ci,
  `comment` VARCHAR(45) NULL ,
  `level` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `sons_cache` longtext COLLATE utf8_unicode_ci,
  `ancestors_cache` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `glpi_plugin_archisw_swcomponenttype_name` (`name` ASC) )
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponenttypes` (`id`, `name`, `completename`, `comment`)  VALUES (1,'Custom','Custom','Custom Development');
INSERT INTO `glpi_plugin_archisw_swcomponenttypes` (`id`, `name`, `completename`, `comment`)  VALUES (2,'Package','Package','Commercial Off The Shelf');

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponenttechnics`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponenttechnics`;
CREATE TABLE `glpi_plugin_archisw_swcomponenttechnics` (
	`id` INT(11) UNSIGNED NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
	`id` INT(11) UNSIGNED NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponentslas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentslas`;
CREATE TABLE `glpi_plugin_archisw_swcomponentslas` (
	`id` INT(11) UNSIGNED NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponentdbs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentdbs`;
CREATE TABLE `glpi_plugin_archisw_swcomponentdbs` (
	`id` INT(11) UNSIGNED NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponentdbs` VALUES ('1', 'SQLserver','');
INSERT INTO `glpi_plugin_archisw_swcomponentdbs` VALUES ('2', 'Oracle','');
INSERT INTO `glpi_plugin_archisw_swcomponentdbs` VALUES ('3', 'Mysql','');

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponentinstances`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentinstances`;
CREATE TABLE `glpi_plugin_archisw_swcomponentinstances` (
	`id` INT(11) UNSIGNED NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
	`id` INT(11) UNSIGNED NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponenttargets` VALUES ('1', 'Integrated+Franchise','');
INSERT INTO `glpi_plugin_archisw_swcomponenttargets` VALUES ('2', 'Integrated only','');
INSERT INTO `glpi_plugin_archisw_swcomponenttargets` VALUES ('3', 'Franchise only','');

-- -----------------------------------------------------
-- Table `plugin_archisw_swcomponentlicenses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_swcomponentlicenses`;
CREATE TABLE `glpi_plugin_archisw_swcomponentlicenses` (
	`id` INT(11) UNSIGNED NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
	PRIMARY KEY  (`id`),
	KEY `name` (`name`)
)  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_swcomponentlicenses` VALUES ('1', 'Named user','');
INSERT INTO `glpi_plugin_archisw_swcomponentlicenses` VALUES ('2', 'Concurrent user','');
INSERT INTO `glpi_plugin_archisw_swcomponentlicenses` VALUES ('3', 'Server','');
INSERT INTO `glpi_plugin_archisw_swcomponentlicenses` VALUES ('4', 'Processor','');
	
-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_standards`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_standards`;
CREATE  TABLE `glpi_plugin_archisw_standards` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `comment` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_standards_name` (`name` ASC) )
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (1,'Standard','Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (2,'Non-Standard','Non-Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (3,'Proposed Standard','Proposed Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (4,'Provisional Standard','Provisional Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (5,'Phasing-Out Standard','Phasing-Out Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (6,'Retired Standard','Retired Standard');

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_fieldgroups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configswfieldgroups`;
CREATE  TABLE `glpi_plugin_archisw_configswfieldgroups` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL,
  `comment` VARCHAR(45) NULL,
  `sortorder` TINYINT UNSIGNED NOT NULL,
  `is_visible` TINYINT UNSIGNED NOT NULL COMMENT '0=False/1=True',
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_configswfieldgroups_name` (`name` ASC) )
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_configswfieldgroups` (`id` ,`name` ,`comment` ,`sortorder` ,`is_visible`)  VALUES (1,'main','Main characteristics',1,1);
INSERT INTO `glpi_plugin_archisw_configswfieldgroups` (`id` ,`name` ,`comment` ,`sortorder` ,`is_visible`)  VALUES (2,'location','Locations',2,1);
INSERT INTO `glpi_plugin_archisw_configswfieldgroups` (`id` ,`name` ,`comment` ,`sortorder` ,`is_visible`)  VALUES (3,'other','Other characteristics',3,0);

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configswbphaligns`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configswhaligns`;
CREATE  TABLE `glpi_plugin_archisw_configswhaligns` (
  `id` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_configswhaligns_name` (`name`) )
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_configswhaligns` (`id` ,`name`)  VALUES ('1','Full row');
INSERT INTO `glpi_plugin_archisw_configswhaligns` (`id` ,`name`)  VALUES ('2','Left column');
INSERT INTO `glpi_plugin_archisw_configswhaligns` (`id` ,`name`)  VALUES ('3','Left+Center columns');
INSERT INTO `glpi_plugin_archisw_configswhaligns` (`id` ,`name`)  VALUES ('4','Center column');
INSERT INTO `glpi_plugin_archisw_configswhaligns` (`id` ,`name`)  VALUES ('5','Center+Right columns');
INSERT INTO `glpi_plugin_archisw_configswhaligns` (`id` ,`name`)  VALUES ('6','Right column');

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configswdbfieldtypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configswdbfieldtypes`;
CREATE  TABLE `glpi_plugin_archisw_configswdbfieldtypes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL,
  `comment` VARCHAR(255) NULL,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_configswdbfieldtypes_name` (`name` ASC) )
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (10,'INT UNSIGNED','Unsigned Integer (range 0 to 4294967295');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (11,'TINYINT UNSIGNED','Unsigned Tiny Integer (range 0 to 255)');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (12,'SMALLINT UNSIGNED','Unsigned Small Integer (range 0 to 65535)');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (20,'INT','Integer (range -2147483648 to 2147483647');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (21,'TINYINT','Tiny Integer (range -128 to 127)');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (22,'SMALLINT','Small Integer (range -32768 to 32767)');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (30,'VARCHAR(255)','Variable character string (max. 255 char.)');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (31,'TEXT','Variable character string (max. 65535 char.)');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (32,'MEDIUMTEXT','Variable character string (max. 16777215 char.)');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (40,'DATETIME','Date and time');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (41,'DATE','Date (YYYY-MM-DD)');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (42,'TIME','Year (hhh:mm:ss)');
INSERT INTO `glpi_plugin_archisw_configswdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (43,'YEAR','Year (YYYY)');

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configswdatatypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configswdatatypes`;
CREATE  TABLE `glpi_plugin_archisw_configswdatatypes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL,
  `comment` VARCHAR(255) NULL,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_configswdatatypes_name` (`name` ASC) )
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_configswdatatypes` (`id` ,`name` ,`comment`)  VALUES (1,'text','Text');
INSERT INTO `glpi_plugin_archisw_configswdatatypes` (`id` ,`name` ,`comment`)  VALUES (2,'bool','Boolean');
INSERT INTO `glpi_plugin_archisw_configswdatatypes` (`id` ,`name` ,`comment`)  VALUES (3,'date','Date');
INSERT INTO `glpi_plugin_archisw_configswdatatypes` (`id` ,`name` ,`comment`)  VALUES (4,'datetime','Date and time');
INSERT INTO `glpi_plugin_archisw_configswdatatypes` (`id` ,`name` ,`comment`)  VALUES (5,'number','Key or number');
INSERT INTO `glpi_plugin_archisw_configswdatatypes` (`id` ,`name` ,`comment`)  VALUES (6,'dropdown','Dropdown');
INSERT INTO `glpi_plugin_archisw_configswdatatypes` (`id` ,`name` ,`comment`)  VALUES (7,'itemlink','Itemlink');
INSERT INTO `glpi_plugin_archisw_configswdatatypes` (`id` ,`name` ,`comment`)  VALUES (8,'textarea','Text editor');

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configswlinks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configswlinks`;
CREATE  TABLE `glpi_plugin_archisw_configswlinks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL,
  `has_dropdown` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  `is_entity_limited` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  `is_tree_dropdown` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  `as_view_on` VARCHAR(255) NULL COMMENT 'empty or table name',
  `viewlimit` VARCHAR(255) NULL COMMENT 'empty or where clause (without where reserved word)',
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_configswlinks_name` (`name` ASC) )
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (1,'PluginArchiswSwcomponent',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (2,'PluginArchidataDataelement',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (3,'PluginArchibpTask',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (4,'PluginArchifunFuncarea',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (5,'Computer',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (6,'Software',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (7,'Appliance',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (8,'Contract',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (9,'Entity',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (10,'Project',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (11,'ProjectTask',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (12,'User',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (13,'Group',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (14,'Location',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (15,'PluginArchiswSwcomponentState',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (16,'PluginArchiswStandard',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`,`is_tree_dropdown`) VALUES (17,'PluginArchiswSwcomponentType',0,1,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (18,'PluginArchiswSwcomponentTechnic',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (19,'PluginArchiswSwcomponentInstance',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (20,'PluginArchiswSwcomponentDb',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (21,'PluginArchiswSwcomponentTarget',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (22,'PluginArchiswSwcomponentUser',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (23,'PluginArchiswSwcomponentLicense',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (24,'PluginArchiswSwcomponentSla',0,0);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (25,'Supplier',0,1);
INSERT INTO `glpi_plugin_archisw_configswlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (26,'Manufacturer',0,0);

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configsws`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configsws`;
CREATE  TABLE `glpi_plugin_archisw_configsws` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `plugin_archisw_configswfieldgroups_id` INT(11) UNSIGNED NOT NULL,
  `row` TINYINT UNSIGNED NOT NULL,
  `plugin_archisw_configswhaligns_id` INT(11) UNSIGNED NOT NULL COMMENT 'Left/Center/Right column or Full line',
  `plugin_archisw_configswdbfieldtypes_id` INT(11) UNSIGNED NOT NULL,
  `plugin_archisw_configswdatatypes_id` INT(11) UNSIGNED NOT NULL,
  `nosearch` CHAR(1) NOT NULL COMMENT '0=False/1=True',
  `massiveaction` CHAR(1) NOT NULL COMMENT '0=False/1=True',
  `forcegroupby` CHAR(1) NOT NULL COMMENT '0=False/1=True',
  `is_linked` TINYINT UNSIGNED NOT NULL COMMENT '0=False/1=True',
  `plugin_archisw_configswlinks_id` INT(11) UNSIGNED,
  `linkfield` VARCHAR(255),
  `joinparams` VARCHAR(255),
  `description` VARCHAR(45) NOT NULL,
  `is_readonly` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  `is_deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_archisw_configswfieldgroups_id`, `row`, `plugin_archisw_configswhaligns_id`),
  UNIQUE INDEX `plugin_archisw_configsws_name` (`name` ASC) )
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'is_helpdesk_visible',2,5,6,11,2,1,0,0,0,0,'','',0,'Associable to a ticket',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'locations_id',2,5,2,12,6,1,0,0,1,14,'','',0,'Location',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'comment',3,1,1,32,8,0,0,0,0,0,'','',0,'Comment',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'address',2,1,1,30,7,0,0,0,0,0,'','',0,'URL Production',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'address_qa',2,2,1,30,7,0,0,0,0,0,'','',0,'URL QA',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'health_check',2,3,1,30,7,0,0,0,0,0,'','',0,'URL Health Check',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'repo',2,4,1,30,7,0,0,0,0,0,'','',0,'Source Repository',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'version',0,2,4,30,1,0,0,0,0,0,'','',0,'Version',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'startyear',0,2,2,30,1,0,0,0,0,0,'','',0,'In use since year',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'level',0,1,6,20,1,1,0,0,0,0,'','',1,'Level',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'shortname',0,2,6,30,1,0,0,0,0,0,'','',0,'Short code',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'description',0,3,1,32,8,1,0,0,0,0,'','',0,'Description',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentstates_id',1,1,2,10,6,1,1,0,1,15,'','',0,'Status',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'statedate',1,1,4,40,3,0,0,0,0,0,'','',0,'Status Startdate',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_standards_id',1,1,6,10,6,1,0,0,1,16,'','',0,'Standardization Status',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponenttypes_id',1,2,2,10,6,1,0,0,1,17,'','',0,'Type',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponenttechnics_id',1,2,4,10,6,1,0,0,1,18,'','',0,'Development Language',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentinstances_id',1,3,2,10,6,1,0,0,1,19,'','',0,'Instances',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentdbs_id',1,3,4,10,6,1,0,0,1,20,'','',0,'Databases',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponenttargets_id',1,4,2,10,6,1,0,0,1,21,'','',0,'Targets',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentusers_id',1,4,4,10,6,1,0,0,1,22,'','',0,'# users',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentlicenses_id',1,4,6,10,6,1,0,0,1,23,'','',0,'License metric',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'groups_id',1,5,2,10,6,1,0,0,1,13,'groups_id','',0,'Component Owner',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'suppliers_id',1,5,4,10,6,1,0,0,1,25,'','',0,'Supplier',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'users_id',1,6,2,10,6,1,0,0,1,12,'users_id','',0,'Component Maintainer',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'manufacturers_id',1,6,4,10,6,1,0,0,1,26,'','',0,'Editor',0);
 INSERT INTO `glpi_plugin_archisw_configsws` (`id`,`name`,`plugin_archisw_configswfieldgroups_id`,`row`,`plugin_archisw_configswhaligns_id`,`plugin_archisw_configswdbfieldtypes_id`,`plugin_archisw_configswdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configswlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentslas_id',1,6,6,10,6,1,0,0,1,24,'','',0,'Service level',0);

-- ----------------------------------
-- Statecheck rules
-- ----------------------------------
INSERT INTO `glpi_plugin_statecheck_tables` (`id`,`name`,`comment`,`statetable`,`stateclass`,`class`,`frontname`) VALUES (null,'glpi_plugin_archisw_configsws', 'Apps structure configuration', 'glpi_plugin_archisw_configswdatatypes', 'PluginArchiswConfigswDatatype', 'PluginArchiswConfigsw', 'config');
SELECT @table_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure configuration - reserved words',@table_id,0,1,'AND',true,'Do not delete',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnot','name','name');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnot','name','completename');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnot','name','is_deleted');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnot','name','entities_id');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnot','name','id');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnot','name','is_recursive');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnot','name','sons_cache');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnot','name','ancestors_cache');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnot','name','date_mod');
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure configuration - mandatory fields',@table_id,0,1,'AND',true,'Do not delete',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','row',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','plugin_archisw_configswhaligns_id',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','description',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','plugin_archisw_configswdbfieldtypes_id',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','plugin_archisw_configswdatatypes_id',null);
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure configuration - not dropdown',@table_id,0,1,'AND',true,'Do not delete
/nIf the field is not a dropdown,
/n- a name must be lowercase, start with a letter, contain only letters, numbers or underscores
/n- a name may not end with "s_id" ((?&#60;!a) is a negated lookbehind assertion that ensures, that before the end of the string (or row with m modifier), there is not the character "a")',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'regex_check','name','/^[a-z][a-z0-9_]*$/');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'regex_check','name','/.*(?&#60;!s_id)$/m');
INSERT INTO `glpi_plugin_statecheck_rulecriterias` (`id`,`plugin_statecheck_rules_id`,`criteria`,`condition`,`pattern`) VALUES (null,@rule_id,'plugin_archisw_configswdatatypes_id',1,6);
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure configuration - dropdown',@table_id,6,1,'AND',true,'Do not delete',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'regex_check','name','/^[a-z][a-z0-9_]*s_id$/');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'is','is_linked','1');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','plugin_archisw_configswlinks_id',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'is','plugin_archisw_configswdbfieldtypes_id',10);

INSERT INTO `glpi_plugin_statecheck_tables` (`id`,`name`,`comment`,`statetable`,`stateclass`,`class`,`frontname`) VALUES (null,'glpi_plugin_archisw_configswlinks', 'Apps structure configuration links', '', '', 'PluginArchiswConfigswLink', 'configlink');
SELECT @table_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure configuration links for dropdown',@table_id,0,1,'AND',true,'Do not delete : set temporarily inactive, if needed',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'regex_check','name','/^PluginArchisw[a-zA-Z0-9]+$/');

INSERT INTO `glpi_plugin_statecheck_tables` (`id`,`name`,`comment`,`statetable`,`stateclass`,`class`,`frontname`) VALUES (null,'glpi_plugin_archisw_configswfieldgroups', 'Apps structure field groups', '', '', 'PluginArchiswConfigswFieldgroup', 'configfieldgroup');
SELECT @table_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure Field Groups - mandatory fields',@table_id,0,1,'AND',true,'Do not delete',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','name',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','comment',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','sortorder',null);

INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswSwcomponent','6','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswSwcomponent','7','4','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswSwcomponent','2','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfigsw',2,1,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfigsw',3,2,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfigsw',11,3,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfigsw',12,4,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfigsw',4,5,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfigsw',10,6,0);
