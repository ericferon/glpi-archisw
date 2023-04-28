-- -----------------------------------------------------
-- Table `glpi_plugin_archisw_configswhaligns`
-- -----------------------------------------------------
INSERT INTO `glpi_plugin_archisw_configswhaligns` (`id` ,`name`)  VALUES ('7','Straddling 2 columns');

-- -----------------------------------------------------
-- Table `glpi_plugin_statecheck_ruleactions`
-- -----------------------------------------------------
SELECT `id` INTO @table_id FROM `glpi_plugin_statecheck_tables` WHERE `name` = 'glpi_plugin_archisw_configswfieldgroups';
SELECT `id` INTO @rule_id FROM `glpi_plugin_statecheck_rules` WHERE `plugin_statecheck_tables_id` = @table_id AND `name` = 'Apps structure Field Groups - mandatory fields' AND `plugin_statecheck_targetstates_id` = 0;
INSERT INTO `glpi_plugin_statecheck_ruleactions` (`id`,`plugin_statecheck_rules_id`,`action_type`,`field`,`value`) VALUES (null,@rule_id,'regex_check','name','/^[a-zA-Z][a-zA-Z0-9_]*$/');
