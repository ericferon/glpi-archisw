<?php
/*
 -------------------------------------------------------------------------
 Archisw plugin for GLPI
 Copyright (C) 2009-2018 by Eric Feron.
 -------------------------------------------------------------------------

 LICENSE
      
 This file is part of Archisw.

 Archisw is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 at your option any later version.

 Archisw is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Archisw. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// Init the hooks of the plugins -Needed
function plugin_init_archisw() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['archisw'] = true;
   $PLUGIN_HOOKS['change_profile']['archisw'] = ['PluginArchiswProfile', 'initProfile'];
   $PLUGIN_HOOKS['assign_to_ticket']['archisw'] = true;
   
//   $PLUGIN_HOOKS['assign_to_ticket_dropdown']['archisw'] = true;
//   $PLUGIN_HOOKS['assign_to_ticket_itemtype']['archisw'] = ['PluginArchiswSwcomponent_Item'];
   
   Plugin::registerClass('PluginArchiswSwcomponent', [
         'linkgroup_tech_types'   => true,
         'linkuser_tech_types'    => true,
         'document_types'         => true,
         'ticket_types'           => true,
         'helpdesk_visible_types' => true,
         'addtabon'               => 'Supplier'
   ]);
   Plugin::registerClass('PluginArchiswProfile',
                         ['addtabon' => 'Profile']);
                         
   //Plugin::registerClass('PluginDatabasesDatabase_Item',
   //                      array('ticket_types' => true));

   // Add links to other plugins
   $types = ['PluginArchimapGraph'];
   foreach ($types as $itemtype) {
      if (class_exists($itemtype)) {
         $itemtype::registerType('PluginArchiswSwcomponent');
      }
   }
// Add other plugin associations
   $associatedtypes = ['PluginDatabasesDatabase',
                     'PluginArchiswSwcomponent'];
   if (class_exists('PluginArchiswSwcomponent'))
	  foreach ($associatedtypes as $itemtype) {
		if (class_exists($itemtype)) {
			$itemtype::registerType('PluginArchiswSwcomponent');
		}
	  }

   if (Session::getLoginUserID()) {

      $plugin = new Plugin();
      if (Session::haveRight("plugin_archisw", READ)) {

         $PLUGIN_HOOKS['menu_toadd']['archisw'] = ['assets'   => 'PluginArchiswMenu'];
      }

      if (Session::haveRight("plugin_archisw", UPDATE)) {
         $PLUGIN_HOOKS['use_massive_action']['archisw']=1;
      }

      if (class_exists('PluginArchiswSwcomponent_Item')) { // only if plugin activated
         $PLUGIN_HOOKS['plugin_datainjection_populate']['archisw'] = 'plugin_datainjection_populate_archisw';
      }
      // End init, when all types are registered
      $PLUGIN_HOOKS['post_init']['archisw'] = 'plugin_archisw_postinit';

      // Import from Data_Injection plugin
      $PLUGIN_HOOKS['migratetypes']['archisw'] = 'plugin_datainjection_migratetypes_archisw';
   }
}

// Get the name and the version of the plugin - Needed
function plugin_version_archisw() {

   return array (
      'name' => _n('Apps structure', 'Apps structures', 2, 'archisw'),
      'version' => '2.2.13',
      'author'  => "Eric Feron",
      'license' => 'GPLv2+',
      'homepage'=> 'https://github.com/ericferon/glpi-archisw',
      'requirements' => [
         'glpi' => [
            'min' => '10.0',
            'dev' => false
         ]
      ]
   );

}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_archisw_check_prerequisites() {
   if (version_compare(GLPI_VERSION, '10.0', 'lt')
       || version_compare(GLPI_VERSION, '10.1', 'ge')) {
      if (method_exists('Plugin', 'messageIncompatible')) {
         echo Plugin::messageIncompatible('core', '10.0');
      }
      return false;
   }
   return true;
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_archisw_check_config() {
   return true;
}

function plugin_datainjection_migratetypes_archisw($types) {
   $types[2400] = 'PluginArchiswSwcomponent';
   return $types;
}

?>
