-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_labeltranslations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `glpi_plugin_archisw_labeltranslations` (
	`id`                         INT          UNSIGNED NOT NULL auto_increment,
	`items_id`                   INT          UNSIGNED NOT NULL,
	`language`                   VARCHAR(5)   NOT NULL,
	`label`                      VARCHAR(255) DEFAULT NULL,
	PRIMARY KEY                  (`id`),
	KEY `items_id`               (`items_id`),
	KEY `language`               (`language`),
	UNIQUE KEY `unicity` (`items_id`, `language`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
