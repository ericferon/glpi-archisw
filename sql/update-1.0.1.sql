ALTER TABLE `glpi_plugin_archisw_swcomponenttypes` 
   ADD COLUMN `entities_id` INT(11) NOT NULL default '0' AFTER `id`,
   ADD COLUMN `is_recursive` tinyint(1) NOT NULL default '0' AFTER `entities_id`,
   ADD COLUMN `plugin_archisw_swcomponenttypes_id` INT(11) NOT NULL default '0' AFTER `name`,
   ADD COLUMN `completename` text COLLATE utf8_unicode_ci AFTER `plugin_archisw_swcomponenttypes_id`,
   ADD COLUMN `level` int(11) NOT NULL DEFAULT '0' AFTER `comment`,
   ADD COLUMN `sons_cache` longtext COLLATE utf8_unicode_ci AFTER `level`,
   ADD COLUMN `ancestors_cache` longtext COLLATE utf8_unicode_ci AFTER `sons_cache`;

update `glpi_plugin_archisw_swcomponenttypes`
set completename=name,
	level=1;