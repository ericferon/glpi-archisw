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

function plugin_archisw_install() {
   global $DB;

   include_once (GLPI_ROOT."/plugins/archisw/inc/profile.class.php");

   $update=false;
   if (!$DB->TableExists("glpi_plugin_archisw_swcomponents")) {
      
      $DB->runFile(GLPI_ROOT ."/plugins/archisw/sql/empty-1.0.1.sql");

   }
   else if ($DB->TableExists("glpi_plugin_archisw_swcomponenttypes") && !$DB->FieldExists("glpi_plugin_archisw_swcomponenttypes","plugin_archisw_swcomponenttypes_id")) {
      $update=true;
      $DB->runFile(GLPI_ROOT ."/plugins/archisw/sql/update-1.0.1.sql");
   }
   if ($DB->numrows($DB->query("SELECT * from glpi_plugin_archisw_swcomponents_itemroles where itemtype = 'PluginArchiswSwcomponent'")) == 0) {
      $DB->runFile(GLPI_ROOT ."/plugins/archisw/sql/update-1.0.2.sql");
   }

   
   if ($DB->TableExists("glpi_plugin_archisw_profiles")) {
   
      $notepad_tables = array('glpi_plugin_archisw_swcomponents');

      foreach ($notepad_tables as $t) {
         // Migrate data
         if ($DB->FieldExists($t, 'notepad')) {
            $query = "SELECT id, notepad
                      FROM `$t`
                      WHERE notepad IS NOT NULL
                            AND notepad <>'';";
            foreach ($DB->request($query) as $data) {
               $iq = "INSERT INTO `glpi_notepads`
                             (`itemtype`, `items_id`, `content`, `date`, `date_mod`)
                      VALUES ('PluginArchiswSwcomponent', '".$data['id']."',
                              '".addslashes($data['notepad'])."', NOW(), NOW())";
               $DB->queryOrDie($iq, "0.85 migrate notepad data");
            }
            $query = "ALTER TABLE `glpi_plugin_archisw_swcomponents` DROP COLUMN `notepad`;";
            $DB->query($query);
         }
      }
   }
   
   if ($update) {
      $query_="SELECT *
            FROM `glpi_plugin_archisw_profiles` ";
      $result_=$DB->query($query_);
      if ($DB->numrows($result_)>0) {

         while ($data=$DB->fetch_array($result_)) {
            $query="UPDATE `glpi_plugin_archisw_profiles`
                  SET `profiles_id` = '".$data["id"]."'
                  WHERE `id` = '".$data["id"]."';";
            $result=$DB->query($query);

         }
      }

      $query="ALTER TABLE `glpi_plugin_archisw_profiles`
               DROP `name` ;";
      $result=$DB->query($query);
   }

   PluginArchiswProfile::initProfile();
   PluginArchiswProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
   $migration = new Migration("2.0.0");
   $migration->dropTable('glpi_plugin_archisw_profiles');
   
   return true;
}

function plugin_archisw_uninstall() {
   global $DB;
   
   include_once (GLPI_ROOT."/plugins/archisw/inc/profile.class.php");
   include_once (GLPI_ROOT."/plugins/archisw/inc/menu.class.php");
   
	$tables = array("glpi_plugin_archisw_swcomponents",
					"glpi_plugin_archisw_swcomponents_items",
					"glpi_plugin_archisw_swcomponentdbs",
					"glpi_plugin_archisw_swcomponentinstances",
					"glpi_plugin_archisw_swcomponentlicenses",
					"glpi_plugin_archisw_swcomponents_itemroles",
					"glpi_plugin_archisw_swcomponentslas",
					"glpi_plugin_archisw_swcomponentstates",
					"glpi_plugin_archisw_swcomponenttargets",
					"glpi_plugin_archisw_swcomponenttechnics",
					"glpi_plugin_archisw_swcomponenttypes",
					"glpi_plugin_archisw_swcomponentusers",
					"glpi_plugin_archisw_profiles");

   foreach($tables as $table)
      $DB->query("DROP TABLE IF EXISTS `$table`;");

	$tables_glpi = array("glpi_displaypreferences",
               "glpi_documents_items",
               "glpi_bookmarks",
               "glpi_logs",
               "glpi_items_tickets",
               "glpi_notepads",
               "glpi_dropdowntranslations");

   foreach($tables_glpi as $table_glpi)
      $DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` LIKE 'PluginArchisw%' ;");

   if (class_exists('PluginDatainjectionModel')) {
      PluginDatainjectionModel::clean(array('itemtype'=>'PluginArchiswSwcomponent'));
   }
   
   //Delete rights associated with the plugin
   $profileRight = new ProfileRight();
   foreach (PluginArchiswProfile::getAllRights() as $right) {
      $profileRight->deleteByCriteria(array('name' => $right['field']));
   }
   PluginArchiswMenu::removeRightsFromSession();
   PluginArchiswProfile::removeRightsFromSession();
   
   return true;
}

function plugin_archisw_postinit() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['item_purge']['archisw'] = array();

   foreach (PluginArchiswSwcomponent::getTypes(true) as $type) {

      $PLUGIN_HOOKS['item_purge']['archisw'][$type]
         = array('PluginArchiswSwcomponent_Item','cleanForItem');

      CommonGLPI::registerStandardTab($type, 'PluginArchiswSwcomponent_Item');
   }
}

function plugin_archisw_AssignToTicket($types) {

   if (Session::haveRight("plugin_archisw_open_ticket", "1")) {
      $types['PluginArchiswSwcomponent'] = PluginArchiswSwcomponent::getTypeName(2);
   }
   return $types;
}

// Define dropdown relations
function plugin_archisw_getSwcomponentRelations() {

   $plugin = new Plugin();
   if ($plugin->isActivated("archisw"))
		return array("glpi_plugin_archisw_swcomponents"=>array("glpi_plugin_archisw_swcomponents_items"=>"plugin_archisw_swcomponents_id"),
					 "glpi_plugin_archisw_swcomponenttypes"=>array("glpi_plugin_archisw_swcomponents"=>"plugin_archisw_swcomponenttypes_id"),
					 "glpi_plugin_archisw_swcomponents_itemroles"=>array("glpi_plugin_archisw_swcomponents_items"=>"plugin_archisw_swcomponents_itemroles_id"),
					 "glpi_plugin_archisw_swcomponentstates"=>array("glpi_plugin_archisw_swcomponents"=>"plugin_archisw_swcomponentstates_id"),
					 "glpi_plugin_archisw_swcomponenttechnics"=>array("glpi_plugin_archisw_swcomponents"=>"plugin_archisw_swcomponenttechnics_id"),
					 "glpi_plugin_archisw_swcomponentdbs"=>array("glpi_plugin_archisw_swcomponents"=>"plugin_archisw_swcomponentdbs_id"),
					 "glpi_plugin_archisw_swcomponentinstances"=>array("glpi_plugin_archisw_swcomponents"=>"plugin_archisw_swcomponentinstances_id"),
					 "glpi_plugin_archisw_swcomponenttargets"=>array("glpi_plugin_archisw_swcomponents"=>"plugin_archisw_swcomponenttargets_id"),
//					 "glpi_plugin_archisw_graphlevels"=>array("glpi_plugin_archisw_swcomponents"=>"plugin_archisw_graphlevels_id"),
					 "glpi_entities"=>array("glpi_plugin_archisw_swcomponents"=>"entities_id"),
					 "glpi_groups"=>array("glpi_plugin_archisw_swcomponents"=>"groups_id"),
					 "glpi_users"=>array("glpi_plugin_archisw_swcomponents"=>"users_id")
					 );
   else
      return array();
}

// Define Dropdown tables to be manage in GLPI :
function plugin_archisw_getDropdown() {

   $plugin = new Plugin();
   if ($plugin->isActivated("archisw"))
		return array('PluginArchiswSwcomponentType'=>PluginArchiswSwcomponentType::getTypeName(2),
					 'PluginArchiswSwcomponent_Itemrole'=>PluginArchiswSwcomponent_Itemrole::getTypeName(2),
					 'PluginArchiswSwcomponentDb'=>PluginArchiswSwcomponentDb::getTypeName(1), //PluginArchiswSwcomponentDb::getTypeName(2) does not work
					 'PluginArchiswSwcomponentInstance'=>PluginArchiswSwcomponentInstance::getTypeName(2),
					 'PluginArchiswSwcomponentLicense'=>PluginArchiswSwcomponentLicense::getTypeName(2),
					 'PluginArchiswSwcomponentSla'=>PluginArchiswSwcomponentSla::getTypeName(2),
					 'PluginArchiswSwcomponentState'=>PluginArchiswSwcomponentState::getTypeName(2),
					 'PluginArchiswSwcomponentTarget'=>PluginArchiswSwcomponentTarget::getTypeName(2),
					 'PluginArchiswSwcomponentTechnic'=>PluginArchiswSwcomponentTechnic::getTypeName(2),
					 'PluginArchiswSwcomponentUser'=>PluginArchiswSwcomponentUser::getTypeName(2)
		);
   else
      return array();
}

////// SEARCH FUNCTIONS ///////() {

function plugin_archisw_getAddSearchOptions($itemtype) {

   $sopt=array();

   if (in_array($itemtype, PluginArchiswSwcomponent::getTypes(true))) {
      if (Session::haveRight("plugin_archisw", READ)) {

// Add search entry on app name from other plugins (f.i archimap)
         $sopt[2460]['table']         ='glpi_plugin_archisw_swcomponents';
         $sopt[2460]['field']         ='name';
         $sopt[2460]['name']          = PluginArchiswSwcomponent::getTypeName(2)." - ".__('Name');
         $sopt[2460]['forcegroupby']  = true;
         $sopt[2460]['datatype']      = 'itemlink';
         $sopt[2460]['massiveaction'] = false;
         $sopt[2460]['itemlink_type'] = 'PluginArchiswSwcomponent';
         $sopt[2460]['joinparams']    = array('beforejoin'
                                                => array('table'      => 'glpi_plugin_archisw_swcomponents_items',
                                                         'joinparams' => array('jointype' => 'itemtype_item')));

         $sopt[2461]['table']         ='glpi_plugin_archisw_swcomponenttypes';
         $sopt[2461]['field']         ='name';
         $sopt[2461]['name']          = PluginArchiswSwcomponent::getTypeName(2)." - ".PluginArchiswSwcomponentType::getTypeName(1);
         $sopt[2461]['forcegroupby']  = true;
         $sopt[2461]['datatype']      = 'dropdown';
         $sopt[2461]['massiveaction'] = false;
         $sopt[2461]['joinparams']    = array('beforejoin' => array(
                                                array('table'      => 'glpi_plugin_archisw_swcomponents',
                                                      'joinparams' => $sopt[2460]['joinparams'])));

     }
   }
   return $sopt;
}

function plugin_archisw_giveItem($type,$ID,$data,$num) {
   global $DB;
   $searchopt =& Search::getOptions($type);
   $table     = $searchopt[$ID]["table"];
   $field     = $searchopt[$ID]["field"];

   switch ($table . '.' . $field) {
      case "glpi_plugin_archisw_swcomponents_items.items_id" :
         $query_device  = "SELECT DISTINCT `itemtype`
                     FROM `glpi_plugin_archisw_swcomponents_items`
                     WHERE `plugin_archisw_swcomponents_id` = '" . $data['id'] . "'
                     ORDER BY `itemtype`";
         $result_device = $DB->query($query_device);
         $number_device = $DB->numrows($result_device);

         $out       = '';
         $databases = $data['id'];
         if ($number_device > 0) {
            for ($i = 0; $i < $number_device; $i++) {
               $column   = "name";
               $itemtype = $DB->result($result_device, $i, "itemtype");

               if (!class_exists($itemtype)) {
                  continue;
               }
               $item = new $itemtype();
               if ($item->canView()) {
                  $table_item = getTableForItemType($itemtype);

                  $query = "SELECT `" . $table_item . "`.*, `glpi_plugin_archisw_swcomponents_items`.`id` AS items_id, `glpi_entities`.`id` AS entity "
                           . " FROM `glpi_plugin_archisw_swcomponents_items`, `" . $table_item
                           . "` LEFT JOIN `glpi_entities` ON (`glpi_entities`.`id` = `" . $table_item . "`.`entities_id`) "
                           . " WHERE `" . $table_item . "`.`id` = `glpi_plugin_archisw_swcomponents_items`.`items_id`
                  AND `glpi_plugin_archisw_swcomponents_items`.`itemtype` = '$itemtype'
                  AND `glpi_plugin_archisw_swcomponents_items`.`plugin_archisw_swcomponents_id` = '" . $databases . "' "
                           . getEntitiesRestrictRequest(" AND ", $table_item, '', '', $item->maybeRecursive());

                  if ($item->maybeTemplate()) {
                     $query .= " AND `" . $table_item . "`.`is_template` = '0'";
                  }
                  $query .= " ORDER BY `glpi_entities`.`completename`, `" . $table_item . "`.`$column`";

                  if ($result_linked = $DB->query($query))
                     if ($DB->numrows($result_linked)) {
                        $item = new $itemtype();
                        while ($data = $DB->fetch_assoc($result_linked)) {
                           if ($item->getFromDB($data['id'])) {
                              $out .= $item::getTypeName(1) . " - " . $item->getLink() . "<br>";
                           }
                        }
                     } else
                        $out .= ' ';
               } else
                  $out .= ' ';
            }
         }
         return $out;
         break;

      case 'glpi_plugin_archisw_swcomponents.name':
         if ($type == 'Ticket') {
            $swcomponents_id = array();
            if ($data['raw']["ITEM_$num"] != '') {
               $swcomponents_id = explode('$$$$', $data['raw']["ITEM_$num"]);
            } else {
               $swcomponents_id = explode('$$$$', $data['raw']["ITEM_" . $num . "_2"]);
            }
            $ret        = array();
            $paSwcomponent = new PluginArchiswSwcomponent();
            foreach ($swcomponents_id as $ap_id) {
               $paSwcomponent->getFromDB($ap_id);
               $ret[] = $paSwcomponent->getLink();
            }
            return implode('<br>', $ret);
         }
         break;

   }

   return "";
}

////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////

function plugin_archisw_MassiveActions($type) {

   if (in_array($type,PluginArchiswSwcomponent::getTypes(true))) {
      return array('PluginArchiswSwcomponent'.MassiveAction::CLASS_ACTION_SEPARATOR.'plugin_archisw_add_item' =>
                                                              __('Associate to the Functional Area', 'archisw'));
   }
   return array();
}

/*
function plugin_archisw_MassiveActionsDisplay($options=array()) {

   $swcomponent=new PluginArchiswSwcomponent;

   if (in_array($options['itemtype'], PluginArchiswSwcomponent::getTypes(true))) {

      $swcomponent->dropdownSwcomponents("plugin_archisw_swcomponent_id");
      echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\""._sx('button', 'Post')."\" >";
   }
   return "";
}

function plugin_archisw_MassiveActionsProcess($data) {

   $res = array('ok' => 0,
            'ko' => 0,
            'noright' => 0);

   $swcomponent_item = new PluginArchiswSwcomponent_Item();

   switch ($data['action']) {

      case "plugin_archisw_add_item":
         foreach ($data["item"] as $key => $val) {
            if ($val == 1) {
               $input = array('plugin_archisw_swcomponent_id' => $data['plugin_archisw_swcomponent_id'],
                              'items_id'      => $key,
                              'itemtype'      => $data['itemtype']);
               if ($swcomponent_item->can(-1,'w',$input)) {
                  if ($swcomponent_item->can(-1,'w',$input)) {
                     $swcomponent_item->add($input);
                     $res['ok']++;
                  } else {
                     $res['ko']++;
                  }
               } else {
                  $res['noright']++;
               }
            }
         }
         break;
   }
   return $res;
}
*/
function plugin_datainjection_populate_archisw() {
   global $INJECTABLE_TYPES;
   $INJECTABLE_TYPES['PluginArchiswSwcomponentInjection'] = 'datainjection';
}



?>
