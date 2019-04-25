ALTER TABLE `glpi_plugin_archisw_swcomponents` 
   ADD COLUMN IF NOT EXISTS `plugin_archisw_standards_id` INT(11) NOT NULL default '0' COMMENT 'Standard status (Standard, Not standard, ...)' AFTER `plugin_archisw_swcomponentlicenses_id`,
   ADD KEY IF NOT EXISTS `plugin_archisw_standards_id` (`plugin_archisw_standards_id`)
;

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
 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (1,'Standard','Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (2,'Non-Standard','Non-Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (3,'Proposed Standard','Proposed Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (4,'Provisional Standard','Provisional Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (5,'Phasing-Out Standard','Phasing-Out Standard');
INSERT INTO `glpi_plugin_archisw_standards` ( `id` , `name` , `comment` )  VALUES (6,'Retired Standard','Retired Standard');
