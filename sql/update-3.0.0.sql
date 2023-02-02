-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_fieldgroups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configfieldgroups`;
CREATE  TABLE `glpi_plugin_archisw_configfieldgroups` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL,
  `comment` VARCHAR(45) NULL,
  `sortorder` TINYINT UNSIGNED NOT NULL,
  `is_visible` TINYINT UNSIGNED NOT NULL COMMENT '0=False/1=True',
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_configfieldgroups_name` (`name` ASC) )
 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_configfieldgroups` (`id` ,`name` ,`comment` ,`sortorder` ,`is_visible`)  VALUES (1,'main','Main characteristics',1,1);
INSERT INTO `glpi_plugin_archisw_configfieldgroups` (`id` ,`name` ,`comment` ,`sortorder` ,`is_visible`)  VALUES (2,'location','Locations',2,1);
INSERT INTO `glpi_plugin_archisw_configfieldgroups` (`id` ,`name` ,`comment` ,`sortorder` ,`is_visible`)  VALUES (3,'other','Other characteristics',3,0);

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_confighaligns`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_confighaligns`;
CREATE  TABLE `glpi_plugin_archisw_confighaligns` (
  `id` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_confighalignss_name` (`name`) )
 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_confighaligns` (`id` ,`name`)  VALUES ('1','Left column');
INSERT INTO `glpi_plugin_archisw_confighaligns` (`id` ,`name`)  VALUES ('2','Center column');
INSERT INTO `glpi_plugin_archisw_confighaligns` (`id` ,`name`)  VALUES ('3','Right column');
INSERT INTO `glpi_plugin_archisw_confighaligns` (`id` ,`name`)  VALUES ('4','Full row');
INSERT INTO `glpi_plugin_archisw_confighaligns` (`id` ,`name`)  VALUES ('5','Left+Center columns');
INSERT INTO `glpi_plugin_archisw_confighaligns` (`id` ,`name`)  VALUES ('6','Center+Right columns');

-- -----------------------------------------------------
-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configdbfieldtypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configdbfieldtypes`;
CREATE  TABLE `glpi_plugin_archisw_configdbfieldtypes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL,
  `comment` VARCHAR(255) NULL,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_configdbfieldtypes_name` (`name` ASC) )
 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (10,'INT UNSIGNED','Unsigned Integer (range 0 to 4294967295');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (11,'TINYINT UNSIGNED','Unsigned Tiny Integer (range 0 to 255)');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (12,'SMALLINT UNSIGNED','Unsigned Small Integer (range 0 to 65535)');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (20,'INT','Integer (range -2147483648 to 2147483647');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (21,'TINYINT','Tiny Integer (range -128 to 127)');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (22,'SMALLINT','Small Integer (range -32768 to 32767)');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (30,'VARCHAR(255)','Variable character string (max. 255 char.)');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (31,'TEXT','Variable character string (max. 65535 char.)');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (32,'MEDIUMTEXT','Variable character string (max. 16777215 char.)');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (40,'DATETIME','Date and time');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (41,'DATE','Date (YYYY-MM-DD)');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (42,'TIME','Year (hhh:mm:ss)');
INSERT INTO `glpi_plugin_archisw_configdbfieldtypes` (`id` ,`name` ,`comment`)  VALUES (43,'YEAR','Year (YYYY)');

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configdatatypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configdatatypes`;
CREATE  TABLE `glpi_plugin_archisw_configdatatypes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL,
  `comment` VARCHAR(255) NULL,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_configdatatypes_name` (`name` ASC) )
 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_configdatatypes` (`id` ,`name` ,`comment`)  VALUES (1,'text','Text');
INSERT INTO `glpi_plugin_archisw_configdatatypes` (`id` ,`name` ,`comment`)  VALUES (2,'bool','Boolean');
INSERT INTO `glpi_plugin_archisw_configdatatypes` (`id` ,`name` ,`comment`)  VALUES (3,'date','Date');
INSERT INTO `glpi_plugin_archisw_configdatatypes` (`id` ,`name` ,`comment`)  VALUES (4,'datetime','Date and time');
INSERT INTO `glpi_plugin_archisw_configdatatypes` (`id` ,`name` ,`comment`)  VALUES (5,'number','Key or number');
INSERT INTO `glpi_plugin_archisw_configdatatypes` (`id` ,`name` ,`comment`)  VALUES (6,'dropdown','Dropdown');
INSERT INTO `glpi_plugin_archisw_configdatatypes` (`id` ,`name` ,`comment`)  VALUES (7,'itemlink','Itemlink');
INSERT INTO `glpi_plugin_archisw_configdatatypes` (`id` ,`name` ,`comment`)  VALUES (8,'textarea','Text editor');

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configlinks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configlinks`;
CREATE  TABLE `glpi_plugin_archisw_configlinks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL,
  `has_dropdown` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  `is_entity_limited` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  `is_tree_dropdown` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `plugin_archisw_configlinks_name` (`name` ASC) )
 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (1,'PluginArchiswSwcomponent',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (2,'PluginArchidataDataelement',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (3,'PluginArchibpTask',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (4,'PluginArchifunFuncarea',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (5,'Computer',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (6,'Software',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (7,'Appliance',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (8,'Contract',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (9,'Entity',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (10,'Project',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (11,'ProjectTask',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (12,'User',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (13,'Group',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (14,'Location',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (15,'PluginArchiswSwcomponentState',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (16,'PluginArchiswStandard',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`,`is_tree_dropdown`) VALUES (17,'PluginArchiswSwcomponentType',0,1,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (18,'PluginArchiswSwcomponentTechnic',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (19,'PluginArchiswSwcomponentInstance',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (20,'PluginArchiswSwcomponentDb',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (21,'PluginArchiswSwcomponentTarget',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (22,'PluginArchiswSwcomponentUser',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (23,'PluginArchiswSwcomponentLicense',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (24,'PluginArchiswSwcomponentSla',0,0);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (25,'Supplier',0,1);
INSERT INTO `glpi_plugin_archisw_configlinks` (`id`,`name`,`has_dropdown`,`is_entity_limited`) VALUES (26,'Manufacturer',0,0);

-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archisw_configs`;
CREATE  TABLE `glpi_plugin_archisw_configs` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `plugin_archisw_configfieldgroups_id` INT(11) UNSIGNED NOT NULL,
  `row` TINYINT UNSIGNED NOT NULL,
  `plugin_archisw_confighaligns_id` INT(11) UNSIGNED NOT NULL COMMENT 'Left/Center/Right column or Full line',
  `plugin_archisw_configdbfieldtypes_id` INT(11) UNSIGNED NOT NULL,
  `plugin_archisw_configdatatypes_id` INT(11) UNSIGNED NOT NULL,
  `nosearch` CHAR(1) NOT NULL COMMENT '0=False/1=True',
  `massiveaction` CHAR(1) NOT NULL COMMENT '0=False/1=True',
  `forcegroupby` CHAR(1) NOT NULL COMMENT '0=False/1=True',
  `is_linked` TINYINT UNSIGNED NOT NULL COMMENT '0=False/1=True',
  `plugin_archisw_configlinks_id` INT(11) UNSIGNED,
  `linkfield` VARCHAR(255),
  `joinparams` VARCHAR(255),
  `description` VARCHAR(45) NOT NULL,
  `is_readonly` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  `is_deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=False/1=True',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_archisw_configfieldgroups_id`, `row`, `plugin_archisw_confighaligns_id`),
  UNIQUE INDEX `plugin_archisw_configs_name` (`name` ASC) )
 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'is_helpdesk_visible',2,5,6,11,2,1,0,0,0,0,'','',0,'Associable to a ticket',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'locations_id',2,5,2,12,6,1,0,0,1,14,'','',0,'Location',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'comment',3,1,1,32,8,0,0,0,0,0,'','',0,'Comment',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'address',2,1,1,30,7,0,0,0,0,0,'','',0,'URL Production',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'address_qa',2,2,1,30,7,0,0,0,0,0,'','',0,'URL QA',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'health_check',2,3,1,30,7,0,0,0,0,0,'','',0,'URL Health Check',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'repo',2,4,1,30,7,0,0,0,0,0,'','',0,'Source Repository',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'version',0,2,4,30,1,0,0,0,0,0,'','',0,'Version',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'startyear',0,2,2,30,1,0,0,0,0,0,'','',0,'In use since year',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'level',0,1,6,20,1,1,0,0,0,0,'','',1,'Level',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'shortname',0,2,6,30,1,0,0,0,0,0,'','',0,'Short code',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'description',0,3,1,32,8,1,0,0,0,0,'','',0,'Description',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentstates_id',1,1,2,10,6,1,1,0,1,15,'','',0,'Status',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'statedate',1,1,4,40,3,0,0,0,0,0,'','',0,'Status Startdate',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_standards_id',1,1,6,10,6,1,0,0,1,16,'','',0,'Standardization Status',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponenttypes_id',1,2,2,10,6,1,0,0,1,17,'','',0,'Type',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponenttechnics_id',1,2,4,10,6,1,0,0,1,18,'','',0,'Development Language',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentinstances_id',1,3,2,10,6,1,0,0,1,19,'','',0,'Instances',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentdbs_id',1,3,4,10,6,1,0,0,1,20,'','',0,'Databases',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponenttargets_id',1,4,2,10,6,1,0,0,1,21,'','',0,'Targets',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentusers_id',1,4,4,10,6,1,0,0,1,22,'','',0,'# users',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentlicenses_id',1,4,6,10,6,1,0,0,1,23,'','',0,'License metric',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'groups_id',1,5,2,10,6,1,0,0,1,13,'groups_id','',0,'Component Owner',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'suppliers_id',1,5,4,10,6,1,0,0,1,25,'','',0,'Supplier',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'users_id',1,6,2,10,6,1,0,0,1,12,'users_id','',0,'Component Maintainer',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'manufacturers_id',1,6,4,10,6,1,0,0,1,26,'','',0,'Editor',0);
 INSERT INTO `glpi_plugin_archisw_configs` (`id`,`name`,`plugin_archisw_configfieldgroups_id`,`row`,`plugin_archisw_confighaligns_id`,`plugin_archisw_configdbfieldtypes_id`,`plugin_archisw_configdatatypes_id`,`nosearch`,`massiveaction`,`forcegroupby`,`is_linked`,`plugin_archisw_configlinks_id`,`linkfield`,`joinparams`,`is_readonly`,`description`,`is_deleted`) VALUES (null,'plugin_archisw_swcomponentslas_id',1,6,6,10,6,1,0,0,1,24,'','',0,'Service level',0);

-- ----------------------------------
-- Statecheck rules
-- ----------------------------------
INSERT INTO `glpi_plugin_statecheck_tables` (`id`,`name`,`comment`,`statetable`,`stateclass`,`class`,`frontname`) VALUES (null,'glpi_plugin_archisw_configs', 'Apps structure configuration', 'glpi_plugin_archisw_configdatatypes', 'PluginArchiswConfigDatatype', 'PluginArchiswConfig', 'config');
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
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','plugin_archisw_confighaligns_id',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','description',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','plugin_archisw_configdbfieldtypes_id',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','plugin_archisw_configdatatypes_id',null);
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure configuration - not dropdown',@table_id,0,1,'AND',true,'Do not delete
/nIf the field is not a dropdown,
/n- a name must be lowercase, start with a letter, contain only letters, numbers or underscores
/n- a name may not end with "s_id" ((?&#60;!a) is a negated lookbehind assertion that ensures, that before the end of the string (or row with m modifier), there is not the character "a")',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'regex_check','name','/^[a-z][a-z0-9_]*$/');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'regex_check','name','/.*(?&#60;!s_id)$/m');
INSERT INTO `glpi_plugin_statecheck_rulecriterias` (`id`,`plugin_statecheck_rules_id`,`criteria`,`condition`,`pattern`) VALUES (null,@rule_id,'plugin_archisw_configdatatypes_id',1,6);
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure configuration - dropdown',@table_id,6,1,'AND',true,'Do not delete',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'regex_check','name','/^[a-z][a-z0-9_]*s_id$/');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'is','is_linked','1');
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','plugin_archisw_configlinks_id',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'is','plugin_archisw_configdbfieldtypes_id',10);

INSERT INTO `glpi_plugin_statecheck_tables` (`id`,`name`,`comment`,`statetable`,`stateclass`,`class`,`frontname`) VALUES (null,'glpi_plugin_archisw_configlinks', 'Apps structure configuration links', '', '', 'PluginArchiswConfigLink', 'configlink');
SELECT @table_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure configuration links for dropdown',@table_id,0,1,'AND',true,'Do not delete : set temporarily inactive, if needed',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'regex_check','name','/^PluginArchisw[a-zA-Z0-9]+$/');

INSERT INTO `glpi_plugin_statecheck_tables` (`id`,`name`,`comment`,`statetable`,`stateclass`,`class`,`frontname`) VALUES (null,'glpi_plugin_archisw_configfieldgroups', 'Apps structure field groups', '', '', 'PluginArchiswConfigFieldgroup', 'configfieldgroup');
SELECT @table_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_rules` (`id`,`entities_id`,`name`,`plugin_statecheck_tables_id`,`plugin_statecheck_targetstates_id`,`ranking`,`match`,`is_active`,`comment`,`successnotifications_id`,`failurenotifications_id`,`date_mod`,`is_recursive`) VALUES (null,0,'Apps structure Field Groups - mandatory fields',@table_id,0,1,'AND',true,'Do not delete',0,0,NOW(),true);
SELECT @rule_id := LAST_INSERT_ID();
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','name',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','comment',null);
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'isnotempty','sortorder',null);

INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfig',2,1,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfig',3,2,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfig',11,3,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfig',12,4,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfig',4,5,0);
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchiswConfig',10,6,0);
