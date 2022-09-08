ALTER TABLE `glpi_plugin_archisw_swcomponents` 
	ADD KEY IF NOT EXISTS `plugin_archisw_swcomponentusers_id` (`plugin_archisw_swcomponentusers_id`),
	ADD KEY IF NOT EXISTS `plugin_archisw_swcomponentslas_id` (`plugin_archisw_swcomponentslas_id`),
	ADD KEY IF NOT EXISTS `plugin_archisw_swcomponentdbs_id` (`plugin_archisw_swcomponentdbs_id`),
	ADD KEY IF NOT EXISTS `plugin_archisw_swcomponentinstances_id` (`plugin_archisw_swcomponentinstances_id`),
	ADD KEY IF NOT EXISTS `plugin_archisw_swcomponenttargets_id` (`plugin_archisw_swcomponenttargets_id`),
	ADD KEY IF NOT EXISTS `plugin_archisw_swcomponentlicenses_id` (`plugin_archisw_swcomponentlicenses_id`)
;
