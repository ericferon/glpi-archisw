ALTER TABLE `glpi_plugin_archisw_configfieldgroups` RENAME `glpi_plugin_archisw_configswfieldgroups`;
ALTER TABLE `glpi_plugin_archisw_confighaligns` RENAME `glpi_plugin_archisw_configswhaligns`;
ALTER TABLE `glpi_plugin_archisw_configdbfieldtypes` RENAME `glpi_plugin_archisw_configswdbfieldtypes`;
ALTER TABLE `glpi_plugin_archisw_configdatatypes` RENAME `glpi_plugin_archisw_configswdatatypes`;
ALTER TABLE `glpi_plugin_archisw_configlinks` RENAME `glpi_plugin_archisw_configswlinks`;
ALTER TABLE `glpi_plugin_archisw_configs` RENAME `glpi_plugin_archisw_configsws`;

ALTER TABLE `glpi_plugin_archisw_configsws` CHANGE COLUMN `plugin_archisw_configfieldgroups_id` `plugin_archisw_configswfieldgroups_id` INT(11) UNSIGNED NOT NULL;
ALTER TABLE `glpi_plugin_archisw_configsws` CHANGE COLUMN `plugin_archisw_confighaligns_id` `plugin_archisw_configswhaligns_id` INT(11) UNSIGNED NOT NULL COMMENT 'Left/Center/Right column or Full line';
ALTER TABLE `glpi_plugin_archisw_configsws` CHANGE COLUMN `plugin_archisw_configdbfieldtypes_id` `plugin_archisw_configswdbfieldtypes_id` INT(11) UNSIGNED NOT NULL;
ALTER TABLE `glpi_plugin_archisw_configsws` CHANGE COLUMN `plugin_archisw_configdatatypes_id` `plugin_archisw_configswdatatypes_id` INT(11) UNSIGNED NOT NULL;
ALTER TABLE `glpi_plugin_archisw_configsws` CHANGE COLUMN `plugin_archisw_configlinks_id` `plugin_archisw_configswlinks_id` INT(11) UNSIGNED;

DROP INDEX `plugin_archisw_configfieldgroups_name` ON `glpi_plugin_archisw_configswfieldgroups`;
DROP INDEX `plugin_archisw_confighalignss_name` ON `glpi_plugin_archisw_configswhaligns`;
DROP INDEX `plugin_archisw_configdbfieldtypes_name` ON `glpi_plugin_archisw_configswdbfieldtypes`;
DROP INDEX `plugin_archisw_configdatatypes_name` ON `glpi_plugin_archisw_configswdatatypes`;
DROP INDEX `plugin_archisw_configlinks_name` ON `glpi_plugin_archisw_configswlinks`;
DROP INDEX `plugin_archisw_configs_name` ON `glpi_plugin_archisw_configsws`;

CREATE INDEX `plugin_archisw_configfieldgroups_name` ON `glpi_plugin_archisw_configswfieldgroups` (`name` ASC);
CREATE INDEX `plugin_archisw_confighalignss_name` ON `glpi_plugin_archisw_configswhaligns` (`name`);
CREATE INDEX `plugin_archisw_configdbfieldtypes_name` ON `glpi_plugin_archisw_configswdbfieldtypes` (`name` ASC);
CREATE INDEX `plugin_archisw_configdatatypes_name` ON `glpi_plugin_archisw_configswdatatypes` (`name` ASC);
CREATE INDEX `plugin_archisw_configlinks_name` ON `glpi_plugin_archisw_configswlinks` (`name` ASC);
CREATE INDEX `plugin_archisw_configs_name` ON `glpi_plugin_archisw_configsws` (`name` ASC);

ALTER TABLE `glpi_plugin_archisw_configswlinks` ADD COLUMN `as_view_on` VARCHAR(255) NULL COMMENT 'empty or table name';
ALTER TABLE `glpi_plugin_archisw_configswlinks` ADD COLUMN `viewlimit` VARCHAR(255) NULL COMMENT 'empty or where clause (without where reserved word)';

UPDATE `glpi_plugin_statecheck_tables` 
SET `name` = 'glpi_plugin_archisw_configsws',
`statetable` = 'glpi_plugin_archisw_configswdatatypes',
`stateclass` = 'PluginArchiswConfigswDatatype',
`class`= 'PluginArchiswConfigsw',
`frontname` = 'configsw'
WHERE `name` = 'glpi_plugin_archisw_configs';
UPDATE `glpi_plugin_statecheck_tables` 
SET `name` = 'glpi_plugin_archisw_configswlinks',
`class`= 'PluginArchiswConfigswLink',
`frontname` = 'configswlink'
WHERE `name` = 'glpi_plugin_archisw_configlinks';
UPDATE `glpi_plugin_statecheck_tables` 
SET `name` = 'glpi_plugin_archisw_configswfieldgroups',
`class`= 'PluginArchiswConfigswFieldgroup',
`frontname` = 'configswfieldgroup'
WHERE `name` = 'glpi_plugin_archisw_configfieldgroups';

UPDATE  `glpi_plugin_statecheck_ruleactions` SET `field` = 'plugin_archisw_configswhaligns_id' WHERE `field` = 'plugin_archisw_confighaligns_id';
UPDATE  `glpi_plugin_statecheck_ruleactions` SET `field` = 'plugin_archisw_configswdbfieldtypes_id' WHERE `field` = 'plugin_archisw_configdbfieldtypes_id';
UPDATE  `glpi_plugin_statecheck_ruleactions` SET `field` = 'plugin_archisw_configswdatatypes_id' WHERE `field` = 'plugin_archisw_configdatatypes_id';
UPDATE  `glpi_plugin_statecheck_ruleactions` SET `field` = 'plugin_archisw_configswlinks_id' WHERE `field` = 'plugin_archisw_configlinks_id';
UPDATE  `glpi_plugin_statecheck_rulecriterias` SET `criteria` = 'plugin_archisw_configswdatatypes_id' WHERE `criteria` = 'plugin_archisw_configdatatypes_id';

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

