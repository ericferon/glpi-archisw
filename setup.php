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
   global $PLUGIN_HOOKS, $CFG_GLPI;

   $PLUGIN_HOOKS['csrf_compliant']['archisw'] = true;
   $PLUGIN_HOOKS['change_profile']['archisw'] = ['PluginArchiswProfile', 'initProfile'];
   $PLUGIN_HOOKS['assign_to_ticket']['archisw'] = true;
   
//   $PLUGIN_HOOKS['assign_to_ticket_dropdown']['archisw'] = true;
//   $PLUGIN_HOOKS['assign_to_ticket_itemtype']['archisw'] = ['PluginArchiswSwcomponent_Item'];
   
   $CFG_GLPI['impact_asset_types']['PluginArchiswSwcomponent'] = Plugin::getPhpDir("archisw", false)."/swcomponent.png";

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
      // link to fields plugin
      if ($plugin->isActivated('fields')
      && Session::haveRight("plugin_archisw", READ))
      {
         $PLUGIN_HOOKS['plugin_fields']['archisw'] = 'PluginArchiswSwcomponent';
      }

      if (Session::haveRight("plugin_archisw", READ)) {

         $PLUGIN_HOOKS['menu_toadd']['archisw'] = ['assets'   => 'PluginArchiswMenu', "config" => 'PluginArchiswConfigMenu'];
      }

      if (Session::haveRight("plugin_archisw", READ)
          || Session::haveRight("config", UPDATE)) {
         $PLUGIN_HOOKS['config_page']['archisw']        = 'front/config.php';
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
      
      $PLUGIN_HOOKS['pre_item_update']['archisw'] = ['PluginArchiswConfig' => 'hook_pre_item_update_config', 
                                                   'PluginArchiswConfigLink' => 'hook_pre_item_update_configlink'];
      $PLUGIN_HOOKS['pre_item_add']['archisw'] = ['PluginArchiswConfig' => 'hook_pre_item_add_config', 
                                                   'PluginArchiswConfigLink' => 'hook_pre_item_add_configlink'];
      $PLUGIN_HOOKS['pre_item_purge']['archisw'] = ['PluginArchiswConfig' => 'hook_pre_item_purge_config', 
                                                   'PluginArchiswConfigLink' => 'hook_pre_item_purge_configlink'];

   }
}

// Get the name and the version of the plugin - Needed
function plugin_version_archisw() {

   return array (
      'name' => _n('Apps structure', 'Apps structures', 2, 'archisw'),
      'version' => '3.0.0',
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
   global $DB;
   if (version_compare(GLPI_VERSION, '10.0', 'lt')
       || version_compare(GLPI_VERSION, '10.1', 'ge')) {
      if (method_exists('Plugin', 'messageIncompatible')) {
         echo Plugin::messageIncompatible('core', '10.0');
      }
      return false;
   } else {
		$query = "select * from glpi_plugins where directory = 'statecheck' and state = 1";
		$result_query = $DB->query($query);
		if($DB->numRows($result_query) == 1) {
			return true;
		} else {
			echo "the plugin 'statecheck' must be installed before using 'Apps structure (archisw)'";
			return false;
		}
	}
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_archisw_check_config() {
   return true;
}

function plugin_datainjection_migratetypes_archisw($types) {
   $types[2400] = 'PluginArchiswSwcomponent';
   return $types;
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function hook_pre_item_add_config(CommonDBTM $item) {
   global $DB;
   $fieldname = $item->fields['name'];
   $dbfield = new PluginArchiswConfigDbfieldtype;
   if ($dbfield->getFromDB($item->fields['plugin_archisw_configdbfieldtypes_id'])) {
      $fieldtype = $dbfield->fields['name'];
      $query = "ALTER TABLE `glpi_plugin_archisw_swcomponents` ADD COLUMN IF NOT EXISTS $fieldname $fieldtype";
      $result = $DB->query($query);
      return true;
   }
   return false;
}
function hook_pre_item_update_config(CommonDBTM $item) {
   global $DB;
   $oldfieldname = $item->fields['name'];
   $newfieldname = $item->input['name'];
   $dbfield = new PluginArchiswConfigDbfieldtype;
   if ($dbfield->getFromDB($item->fields['plugin_archisw_configdbfieldtypes_id'])) {
      $fieldtype = $dbfield->fields['name'];
      if ($oldfieldname != $newfieldname) {
         $query = "ALTER TABLE `glpi_plugin_archisw_swcomponents` RENAME COLUMN $oldfieldname TO $newfieldname ";
         $result = $DB->query($query);
      }
      $query = "ALTER TABLE `glpi_plugin_archisw_swcomponents` MODIFY $newfieldname $fieldtype";
      $result = $DB->query($query);
      return true;
   }
   return false;
}
function hook_pre_item_purge_config(CommonDBTM $item) {
   global $DB;
   $fieldname = $item->fields['name'];
   $query = "ALTER TABLE `glpi_plugin_archisw_swcomponents` DROP COLUMN $fieldname";
   $result = $DB->query($query);
   $rowcount = $DB->numrows($fieldresult);
   $tablename = 'glpi_'.substr($fieldname, 0, -3);
   if ($item->fields['plugin_archisw_configdatatypes_id'] == 6 && substr($tablename, 0, 20) == 'glpi_plugin_archisw_') { //dropdown->drop table
         $query = "DROP TABLE IF EXISTS `".$tablename."`";
         $result = $DB->query($query);
         $classname = 'PluginArchisw'.ucfirst(substr($fieldname, 15, -4)); //cut ending 's_id'
         $query = "DELETE FROM `glpi_plugin_archisw_configlinks` WHERE `name` = '".$classname."'";
         $result = $DB->query($query);
   }
   return true;
}
/*function hook_pre_item_add_configlink(CommonDBTM $item) {
   $dir = Plugin::getPhpDir("archisw", true);
   $newclassname = $item->input['name'];
   if (substr($newclassname, 0, 13) == 'PluginArchisw') {
      $dropdowntype = 'CommonDropdown';
      if ($item->input['plugin_archisw_configdatatypes_id'] == 9) $dropdowntype = 'CommonTreeDropdown';
      $newfilename = strtolower(substr($newclassname, 13));
      // create files in inc and front directories, with read/write access
      file_put_contents($dir.'/inc/'.$newfilename.'.class.php', 
      "<?php
/*
 -------------------------------------------------------------------------
 Archisw plugin for GLPI
 Copyright (C) 2009-2023 by Eric Feron.
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
 /*     if (!defined('GLPI_ROOT')) {
         die('Sorry. You cannott access directly to this file');
      }
      class $newclassname extends $dropdowntype {
      }
      ?>");
      file_put_contents($dir.'/front/'.$newfilename.'.form.php', 
      "<?php
/*
 -------------------------------------------------------------------------
 Archisw plugin for GLPI
 Copyright (C) 2009-2023 by Eric Feron.
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
 /*     include ('../../../inc/includes.php');
      \$dropdown = new $newclassname();
      include (GLPI_ROOT . '/front/dropdown.common.form.php');
      ?>");
      file_put_contents($dir.'/front/'.$newfilename.'.php', 
      "<?php
/*
 -------------------------------------------------------------------------
 Archisw plugin for GLPI
 Copyright (C) 2009-2023 by Eric Feron.
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
 /*     include ('../../../inc/includes.php');
      \$dropdown = new $newclassname();
      include (GLPI_ROOT . '/front/dropdown.common.php');
      ?>");
      chmod($dir.'/inc/'.$newfilename.'.class.php', 0660);
      chmod($dir.'/front/'.$newfilename.'.form.php', 0660);
      chmod($dir.'/front/'.$newfilename.'.php', 0660);
      // refresh with new files
      header("Refresh:0");
//   Session::addMessageAfterRedirect(__('Please, refresh the display', 'archisw'));
   }
   return true;
}*/
/*function hook_pre_item_update_configlink(CommonDBTM $item) {
   global $DB;
   $dir = Plugin::getPhpDir("archisw", true);
   $newclassname = $item->input['name'];
   $newfilename = strtolower(substr($newclassname, 13));
   $oldclassname = $item->fields['name'];
   $oldfilename = strtolower(substr($oldclassname, 13));
   if ($newclassname == $oldclassname // no change in classname (if there is, the case is managed in hook_pre_item_update_config)
   && substr($newclassname, 0, 13) == 'PluginArchisw') // class is owned by this plugin
      if (!$item->fields['is_limited_entity'] && $item->input['is_limited_entity']) { // 'is_limited_entity' changed from no to yes
         // => add 'entities_id' column to dropdown table
         $newtablename = CommonDBTM::getTable($newclassname);
         if (!empty($newtablename)) {
            $query = "ALTER TABLE $newtablename ADD COLUMN IF NOT EXISTS `entities_id` INT(11) UNSIGNED NOT NULL AFTER `id`";
            $result = $DB->query($query);
         }
      } 
      else if ($item->fields['is_limited_entity'] && !$item->input['is_limited_entity']) { // 'is_limited_entity' changed from yes to no
         // => drop 'entities_id' column from dropdown table
         $newtablename = CommonDBTM::getTable($newclassname);
         if (!empty($newtablename)) {
            $query = "ALTER TABLE $newtablename DROP COLUMN `entities_id`";
            $result = $DB->query($query);
         }
      }
   }
   if (substr($oldclassname, 0, 13) == 'PluginArchisw') {
      // delete files in inc and front directories
      if (file_exists($dir.'/inc/'.$oldfilename.'.class.php')) 
         unlink($dir.'/inc/'.$oldfilename.'.class.php');
      if (file_exists($dir.'/front/'.$oldfilename.'.form.php')) 
         unlink($dir.'/front/'.$oldfilename.'.form.php');
      if (file_exists($dir.'/front/'.$oldfilename.'.php')) 
         unlink($dir.'/front/'.$oldfilename.'.php');
   }
   if (substr($newclassname, 0, 13) == 'PluginArchisw') {
      $dropdowntype = 'CommonDropdown';
      if ($item->input['plugin_archisw_configdatatypes_id'] == 9) $dropdowntype = 'CommonTreeDropdown';
      // create files in inc and front directories, with read/write access
      file_put_contents($dir.'/inc/'.$newfilename.'.class.php', 
      "<?php
/*
 -------------------------------------------------------------------------
 Archisw plugin for GLPI
 Copyright (C) 2009-2023 by Eric Feron.
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
/*      if (!defined('GLPI_ROOT')) {
         die('Sorry. You cannott access directly to this file');
      }
      class $newclassname extends $dropdowntype {
      }
      ?>");
      file_put_contents($dir.'/front/'.$newfilename.'.form.php', 
      "<?php
/*
 -------------------------------------------------------------------------
 Archisw plugin for GLPI
 Copyright (C) 2009-2023 by Eric Feron.
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
 /*     include ('../../../inc/includes.php');
      \$dropdown = new $newclassname();
      include (GLPI_ROOT . '/front/dropdown.common.form.php');
      ?>");
      file_put_contents($dir.'/front/'.$newfilename.'.php', 
      "<?php
/*
 -------------------------------------------------------------------------
 Archisw plugin for GLPI
 Copyright (C) 2009-2023 by Eric Feron.
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
 /*     include ('../../../inc/includes.php');
      \$dropdown = new $newclassname();
      include (GLPI_ROOT . '/front/dropdown.common.php');
      ?>");
      chmod($dir.'/inc/'.$newfilename.'.class.php', 0660);
      chmod($dir.'/front/'.$newfilename.'.form.php', 0660);
      chmod($dir.'/front/'.$newfilename.'.php', 0660);
      // refresh with new files
      header("Refresh:0");
//   Session::addMessageAfterRedirect(__('Please, refresh the display', 'archisw'));
   }
   return true;
}
function hook_pre_item_purge_configlink(CommonDBTM $item) {
   global $DB;
   $dir = Plugin::getPhpDir("archisw", true);
   $oldclassname = $item->fields['name'];
   $oldfilename = strtolower(substr($oldclassname, 13));
   $oldid = $item->fields['id'];
   // suppress in glpi_plugin_archisw_configs
   $query = "UPDATE `glpi_plugin_archisw_configs` SET `plugin_archisw_configlinks_id` = 0 WHERE `plugin_archisw_configlinks_id` = '".$oldid."'";
   $result = $DB->query($query);
   if (substr($oldclassname, 0, 13) == 'PluginArchisw') {
      // delete files in inc and front directories
      if (file_exists($dir.'/inc/'.$oldfilename.'.class.php')) 
         unlink($dir.'/inc/'.$oldfilename.'.class.php');
      if (file_exists($dir.'/front/'.$oldfilename.'.form.php')) 
         unlink($dir.'/front/'.$oldfilename.'.form.php');
      if (file_exists($dir.'/front/'.$oldfilename.'.php')) 
         unlink($dir.'/front/'.$oldfilename.'.php');
   }
   return true;
}*/
?>
