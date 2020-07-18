ALTER TABLE `glpi_plugin_archisw_swcomponents` 
	ADD COLUMN IF NOT EXISTS `address_qa` varchar(255) default null,
	ADD COLUMN IF NOT EXISTS `repo` varchar(255) collate utf8_unicode_ci default NULL,
	ADD COLUMN IF NOT EXISTS `health_check` varchar(255) collate utf8_unicode_ci DEFAULT NULL;
