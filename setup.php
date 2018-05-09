<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 archisw plugin for GLPI
 Copyright (C) 2009-2016 by the archisw Development Team.

 https://github.com/InfotelGLPI/archisw
 -------------------------------------------------------------------------

 LICENSE
      
 This file is part of archisw.

 archisw is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 archisw is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with archisw. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// Init the hooks of the plugins -Needed
function plugin_init_archisw() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['archisw'] = true;
   $PLUGIN_HOOKS['change_profile']['archisw'] = array('PluginArchiswProfile', 'initProfile');
   $PLUGIN_HOOKS['assign_to_ticket']['archisw'] = true;
   
   //$PLUGIN_HOOKS['assign_to_ticket_dropdown']['archisw'] = true;
   //$PLUGIN_HOOKS['assign_to_ticket_itemtype']['archisw'] = array('PluginArchiswSwcomponent_Item');
   
   Plugin::registerClass('PluginArchiswSwcomponent', array(
         'linkgroup_tech_types'   => true,
         'linkuser_tech_types'    => true,
         'document_types'         => true,
         'ticket_types'           => true,
         'helpdesk_visible_types' => true//,
//         'addtabon'               => 'Supplier'
   ));
   Plugin::registerClass('PluginArchiswProfile',
                         array('addtabon' => 'Profile'));
                         
   //Plugin::registerClass('PluginArchiswSwcomponent_Item',
   //                      array('ticket_types' => true));
      
   if (class_exists('PluginDatabasesDatabase') 
   and class_exists('PluginArchiswSwcomponent')) {
//      PluginDatabasesDatabase::registerType('PluginArchiswSwcomponent');
	  PluginArchiswSwcomponent::registerType('PluginDatabasesDatabase');
   }

   if (Session::getLoginUserID()) {

      $plugin = new Plugin();
      if (!$plugin->isActivated('environment')
         && Session::haveRight("plugin_archisw", READ)) {

         $PLUGIN_HOOKS['menu_toadd']['archisw'] = array('assets'   => 'PluginArchiswMenu');
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
      'version' => '2.0.1',
      'author'  => "Eric Feron",
      'license' => 'GPLv2+',
      'homepage'=>'',
      'minGlpiVersion' => '0.90',
   );

}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_archisw_check_prerequisites() {
   if (version_compare(GLPI_VERSION,'0.90','lt') || version_compare(GLPI_VERSION,'9.3','ge')) {
      _e('This plugin requires GLPI >= 0.90', 'archisw');
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
