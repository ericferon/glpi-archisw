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

   include_once (Plugin::getPhpDir("archisw")."/inc/profile.class.php");

   $update=false;
   if (!$DB->TableExists("glpi_plugin_archisw_swcomponents")) {
		$DB->runFile(Plugin::getPhpDir("archisw")."/sql/empty-3.0.2.sql");
   }
   else if ($DB->TableExists("glpi_plugin_archisw_swcomponenttypes") && !$DB->FieldExists("glpi_plugin_archisw_swcomponenttypes","plugin_archisw_swcomponenttypes_id")) {
      $update=true;
      $DB->runFile(Plugin::getPhpDir("archisw")."/sql/update-1.0.1.sql");
   }

   if ($DB->numrows($DB->query("SELECT * from glpi_plugin_archisw_swcomponents_itemroles where itemtype = 'PluginArchiswSwcomponent'")) == 0) {
      $DB->runFile(Plugin::getPhpDir("archisw")."/sql/update-1.0.2.sql");
   }

   if (!$DB->TableExists("glpi_plugin_archisw_standards") || !$DB->FieldExists("glpi_plugin_archisw_swcomponents","plugin_archisw_standards_id")) {
      $DB->runFile(Plugin::getPhpDir("archisw")."/sql/update-1.0.3.sql");
   }

	// Field introduced in version 2.2.0
	if (!$DB->FieldExists("glpi_plugin_archisw_swcomponents", "address_qa")) {
		$DB->runFile(Plugin::getPhpDir("archisw")."/sql/update-2.2.0.sql");
	}
	// Add missing indexes
	if (plugin_version_archisw()['version'] == '2.2.10') {
		$DB->runFile(Plugin::getPhpDir("archisw")."/sql/update-2.2.1.sql");
	}
   
   if (!$DB->TableExists("glpi_plugin_archisw_configs") && !$DB->TableExists("glpi_plugin_archisw_configsws")) {
      $DB->runFile(Plugin::getPhpDir("archisw")."/sql/update-3.0.0.sql");
   }

   if (!$DB->TableExists("glpi_plugin_archisw_configsws")) {
      $DB->runFile(Plugin::getPhpDir("archisw")."/sql/update-3.0.1.sql");
   }

   if ($DB->numrows($DB->query("SELECT * from glpi_plugin_archisw_configswhaligns where id = '7'")) == 0) {
      $DB->runFile(Plugin::getPhpDir("archisw")."/sql/update-3.0.2.sql");
   }

   // regenerate configured fields
   if ($DB->TableExists("glpi_plugin_archisw_configswlinks") && $DB->TableExists("glpi_plugin_archisw_configsws")) {
      $query = "SELECT `glpi_plugin_archisw_configswlinks`.`name` as `classname`, `is_entity_limited`, `is_tree_dropdown`, `as_view_on`, `viewlimit`
               FROM `glpi_plugin_archisw_configswlinks` 
               JOIN `glpi_plugin_archisw_configsws`  ON `glpi_plugin_archisw_configswlinks`.`id` = `glpi_plugin_archisw_configsws`.`plugin_archisw_configswlinks_id` 
               WHERE `glpi_plugin_archisw_configswlinks`.`name` like 'PluginArchisw%'";
      $result = $DB->query($query);
      $item = new CommonDBTM;
      while ($data = $DB->fetchAssoc($result)) {
         $item->input['name'] = $data['classname'];
         $item->input['is_entity_limited'] = $data['is_entity_limited'];
         $item->input['is_tree_dropdown'] = $data['is_tree_dropdown'];
         $item->input['as_view_on'] = $data['as_view_on'];
         $item->input['viewlimit'] = $data['viewlimit'];
         hook_pre_item_add_archisw_configswlink($item); // simulate the creation of this field
      }
      // refresh with new files
      header("Refresh:0");
   }

   PluginArchiswProfile::initProfile();
   PluginArchiswProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
   $migration = new Migration("2.0.0");
   $migration->dropTable('glpi_plugin_archisw_profiles');
   
   return true;
}

function plugin_archisw_uninstall() {
   global $DB;
   
   include_once (Plugin::getPhpDir("archisw")."/inc/profile.class.php");
   include_once (Plugin::getPhpDir("archisw")."/inc/menu.class.php");
   
   $query = "SELECT `id` FROM `glpi_plugin_statecheck_tables` WHERE `name` = 'glpi_plugin_archisw_configsws'";
   $result = $DB->query($query);
   $rowcount = $DB->numrows($result);
   if ($rowcount > 0) {
      while ($data = $DB->fetchAssoc($result)) {
         $tableid = $data['id'];
         $rulequery = "SELECT `id` FROM `glpi_plugin_statecheck_rules` WHERE `plugin_statecheck_tables_id` = '".$tableid."'";
         $ruleresult = $DB->query($rulequery);
         while ($ruledata = $DB->fetchAssoc($ruleresult)) {
            $ruleid = $ruledata['id'];
            $query = "DELETE FROM `glpi_plugin_statecheck_ruleactions` WHERE `plugin_statecheck_rules_id` = '".$ruleid."'";
            $DB->query($query);
            $query = "DELETE FROM `glpi_plugin_statecheck_rulecriterias` WHERE `plugin_statecheck_rules_id` = '".$ruleid."'";
            $DB->query($query);
         }
         $query = "DELETE FROM `glpi_plugin_statecheck_rules` WHERE `plugin_statecheck_tables_id` = '".$tableid."'";
         $DB->query($query);
      }
      $query = "DELETE FROM `glpi_plugin_statecheck_tables` WHERE `name` like 'glpi_plugin_archisw_%'";
      $result = $DB->query($query);
   }

   $tables = ["glpi_plugin_archisw_swcomponents",
					"glpi_plugin_archisw_swcomponents_items",
					"glpi_plugin_archisw_swcomponents_itemroles",
                    "glpi_plugin_archisw_configsws",
                    "glpi_plugin_archisw_configswfieldgroups",
                    "glpi_plugin_archisw_configswhaligns",
                    "glpi_plugin_archisw_configswdbfieldtypes",
                    "glpi_plugin_archisw_configswdatatypes",
                    "glpi_plugin_archisw_configswlinks",
					"glpi_plugin_archisw_profiles"];

   $query = "SELECT `name` FROM `glpi_plugin_archisw_configswlinks` WHERE `name` like 'PluginArchisw%' AND (`as_view_on` IS NULL OR `as_view_on` = '')";
   $result = $DB->query($query);
   while ($data = $DB->fetchAssoc($result)) {
      $tablename = CommonDBTM::getTable($data['name']);
      if (!in_array($tablename,$tables))
         $tables[] = $tablename;
   }

   foreach($tables as $table)
      $DB->query("DROP TABLE IF EXISTS `$table`;");

   $views = [];
   $query = "SELECT `name` FROM `glpi_plugin_archisw_configswlinks` WHERE `name` LIKE 'PluginArchisw%' AND (`as_view_on` IS NOT NULL AND `as_view_on` <> '')";
   $result = $DB->query($query);
   while ($data = $DB->fetchAssoc($result)) {
      $tablename = CommonDBTM::getTable($data['name']);
      if (!in_array($tablename,$tables))
         $views[] = $tablename;
   }
				
	foreach($views as $view)
		$DB->query("DROP VIEW IF EXISTS `$view`;");

	$tables_glpi = ["glpi_displaypreferences",
               "glpi_documents_items",
               "glpi_savedsearches",
               "glpi_logs",
               "glpi_items_tickets",
               "glpi_notepads",
               "glpi_dropdowntranslations",
               "glpi_impactitems"];

   foreach($tables_glpi as $table_glpi)
      $DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` LIKE 'PluginArchisw%' ;");

   $DB->query("DELETE
                  FROM `glpi_impactrelations`
                  WHERE `itemtype_source` IN ('PluginArchiswSwcomponent')
                    OR `itemtype_impacted` IN ('PluginArchiswSwcomponent')");

   if (class_exists('PluginDatainjectionModel')) {
      PluginDatainjectionModel::clean(['itemtype'=>'PluginArchiswSwcomponent']);
   }
   
   //Delete rights associated with the plugin
   $profileRight = new ProfileRight();
   foreach (PluginArchiswProfile::getAllRights() as $right) {
      $profileRight->deleteByCriteria(['name' => $right['field']]);
   }
   PluginArchiswMenu::removeRightsFromSession();
   PluginArchiswProfile::removeRightsFromSession();
   
   return true;
}

function plugin_archisw_postinit() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['item_purge']['archisw'] = [];

   foreach (PluginArchiswSwcomponent::getTypes(true) as $type) {

      $PLUGIN_HOOKS['item_purge']['archisw'][$type]
         = ['PluginArchiswSwcomponent_Item','cleanForItem'];

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
   global $DB;

   $plugin = new Plugin();
   if ($plugin->isActivated("archisw")) {
      $tables = ["glpi_plugin_archisw_swcomponents"=>["glpi_plugin_archisw_swcomponents_items"=>"plugin_archisw_swcomponents_id"],
					 "glpi_plugin_archisw_swcomponents_itemroles"=>["glpi_plugin_archisw_swcomponents_items"=>"plugin_archisw_swcomponents_itemroles_id"],
					 "glpi_entities"=>["glpi_plugin_archisw_swcomponents"=>"entities_id"],
					 "glpi_groups"=>["glpi_plugin_archisw_swcomponents"=>"groups_id"],
					 "glpi_users"=>["glpi_plugin_archisw_swcomponents"=>"users_id"]
					 ];

      $query = "SELECT `name` FROM `glpi_plugin_archisw_configswlinks` WHERE `name` like 'PluginArchisw%'";
      $result = $DB->query($query);
      while ($data = $DB->fetchAssoc($result)) {
         $tablename = CommonDBTM::getTable($data['name']);
         if (!in_array($tablename,$tables)) {
            $fieldname = substr($tablename, 5)."_id";
            $tables[$tablename] = ["glpi_plugin_archisw_swcomponents"=>$fieldname];
         }
      }
      return $tables;
   }
   else
      return [];
}

// Define Dropdown tables to be manage in GLPI :
function plugin_archisw_getDropdown() {
   global $DB;

   $plugin = new Plugin();
   if ($plugin->isActivated("archisw")) {
      $classes = [//'PluginArchiswSwcomponentType'=>PluginArchiswSwcomponentType::getTypeName(2),
					 'PluginArchiswSwcomponent_Itemrole'=>PluginArchiswSwcomponent_Itemrole::getTypeName(2),
					 'PluginArchiswConfigsw'=>PluginArchiswConfigsw::getTypeName(2),
					 'PluginArchiswConfigswFieldgroup'=>PluginArchiswConfigswFieldgroup::getTypeName(2),
					 'PluginArchiswConfigswHalign'=>PluginArchiswConfigswHalign::getTypeName(2),
					 'PluginArchiswConfigswDbfieldtype'=>PluginArchiswConfigswDbfieldtype::getTypeName(2),
					 'PluginArchiswConfigswDatatype'=>PluginArchiswConfigswDatatype::getTypeName(2),
					 'PluginArchiswConfigswLink'=>PluginArchiswConfigswLink::getTypeName(2)
		];

      if ($DB->TableExists("glpi_plugin_archisw_configswlinks") && $DB->TableExists("glpi_plugin_archisw_configsws")) {
         $query = "SELECT `glpi_plugin_archisw_configswlinks`.`name` as `classname`, `glpi_plugin_archisw_configsws`.`description` as `typename` 
               FROM `glpi_plugin_archisw_configswlinks` 
               JOIN `glpi_plugin_archisw_configsws`  ON `glpi_plugin_archisw_configswlinks`.`id` = `glpi_plugin_archisw_configsws`.`plugin_archisw_configswlinks_id` 
               WHERE `glpi_plugin_archisw_configswlinks`.`name` like 'PluginArchisw%' AND (`glpi_plugin_archisw_configswlinks`.`as_view_on` IS NULL OR `glpi_plugin_archisw_configswlinks`.`as_view_on` = '')";
         $result = $DB->query($query);
         while ($data = $DB->fetchAssoc($result)) {
            $classname = $data['classname'];
            if (!in_array($classname,$classes))
               $classes[$classname] = $data['typename'];
         }
      }
      return $classes;
   }
   else
      return [];
}

////// SEARCH FUNCTIONS ///////() {

function plugin_archisw_getAddSearchOptions($itemtype) {

   $sopt=[];

   if (in_array($itemtype, PluginArchiswSwcomponent::getTypes(true))) {
      if (Session::haveRight("plugin_archisw", READ)) {

// Add search entry on app name from other plugins (f.i archimap)
         $sopt[2460]['table']         ='glpi_plugin_archisw_swcomponents';
         $sopt[2460]['field']         ='name';
         $sopt[2460]['name']          = PluginArchiswSwcomponent::getTypeName(2)." - ".__('Name');
         $sopt[2460]['forcegroupby']  = true;
         $sopt[2460]['datatype']      = 'itemlink';
         $sopt[2460]['massiveaction'] = true;
         $sopt[2460]['itemlink_type'] = 'PluginArchiswSwcomponent';
         $sopt[2460]['joinparams']    = ['beforejoin'
                                                => ['table'      => 'glpi_plugin_archisw_swcomponents_items',
                                                         'joinparams' => ['jointype' => 'itemtype_item']]];

         $classnames = plugin_archisw_getDropdown();
         if (in_array('PluginArchiswSwcomponentType', $classnames)) {
            $sopt[2461]['table']         ='glpi_plugin_archisw_swcomponenttypes';
            $sopt[2461]['field']         ='name';
            $sopt[2461]['name']          = PluginArchiswSwcomponent::getTypeName(2)." - ".$classnames['PluginArchiswSwcomponentType'];
            $sopt[2461]['forcegroupby']  = true;
            $sopt[2461]['datatype']      = 'dropdown';
            $sopt[2461]['massiveaction'] = true;
            $sopt[2461]['joinparams']    = ['beforejoin' => [
                                                ['table'      => 'glpi_plugin_archisw_swcomponents',
                                                      'joinparams' => $sopt[2460]['joinparams']]]];
         }
     }
   }
   return $sopt;
}

function plugin_archisw_giveItem($type,$ID,$data,$num) {
   global $DB;
   $searchopt =& Search::getOptions($type);
   $table     = $searchopt[$ID]["table"];
   $field     = $searchopt[$ID]["field"];
   $dbu       = new DbUtils();

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
                  $table_item = $dbu->getTableForItemType($itemtype);

                  $query = "SELECT `" . $table_item . "`.*, `glpi_plugin_archisw_swcomponents_items`.`id` AS items_id, `entities`.`id` AS entity "
                           . " FROM `glpi_plugin_archisw_swcomponents_items`, `" . $table_item
                           . "` LEFT JOIN `glpi_entities` as entities ON (`entities`.`id` = `" . $table_item . "`.`entities_id`) "
                           . " WHERE `" . $table_item . "`.`id` = `glpi_plugin_archisw_swcomponents_items`.`items_id`
                  AND `glpi_plugin_archisw_swcomponents_items`.`itemtype` = '$itemtype'
                  AND `glpi_plugin_archisw_swcomponents_items`.`plugin_archisw_swcomponents_id` = '" . $databases . "' "
                           . $dbu->getEntitiesRestrictRequest(" AND ", $table_item, '', '', $item->maybeRecursive());

                  if ($item->maybeTemplate()) {
                     $query .= " AND `" . $table_item . "`.`is_template` = '0'";
                  }
                  $query .= " ORDER BY `entities`.`completename`, `" . $table_item . "`.`$column`";

                  if ($result_linked = $DB->query($query))
                     if ($DB->numrows($result_linked)) {
                        $item = new $itemtype();
                        while ($data = $DB->fetchAssoc($result_linked)) {
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
            $swcomponents_id = [];
            if ($data['raw']["ITEM_$num"] != '') {
               $swcomponents_id = explode('$$$$', $data['raw']["ITEM_$num"]);
            } else {
               $swcomponents_id = explode('$$$$', $data['raw']["ITEM_" . $num . "_2"]);
            }
            $ret        = [];
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

    $plugin = new Plugin();
    if ($plugin->isActivated('archisw')) {
        if (in_array($type,PluginArchiswSwcomponent::getTypes(true))) {
            return ['PluginArchiswSwcomponent'.MassiveAction::CLASS_ACTION_SEPARATOR.'plugin_archisw_add_item' =>
                                                              __('Associate to the Apps Structure', 'archisw')];
      }
   }
   return [];
}

/*
function plugin_archisw_MassiveActionsDisplay($options=[]) {

   $swcomponent=new PluginArchiswSwcomponent;

   if (in_array($options['itemtype'], PluginArchiswSwcomponent::getTypes(true))) {

      $swcomponent->dropdownSwcomponents("plugin_archisw_swcomponent_id");
      echo "<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\""._sx('button', 'Post')."\" >";
   }
   return "";
}

function plugin_archisw_MassiveActionsProcess($data) {

   $res = ['ok' => 0,
            'ko' => 0,
            'noright' => 0];

   $swcomponent_item = new PluginArchiswSwcomponent_Item();

   switch ($data['action']) {

      case "plugin_archisw_add_item":
         foreach ($data["item"] as $key => $val) {
            if ($val == 1) {
               $input = ['plugin_archisw_swcomponent_id' => $data['plugin_archisw_swcomponent_id'],
                              'items_id'      => $key,
                              'itemtype'      => $data['itemtype']];
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

function hook_pre_item_add_archisw_configswlink(CommonDBTM $item) {
   global $DB;
   $dir = Plugin::getPhpDir("archisw", true);
   $newclassname = $item->input['name'];
   $newistreedropdown = $item->input['is_tree_dropdown'];
   $newisentitylimited = $item->input['is_entity_limited'];
   $newasviewon = $item->input['as_view_on'];
   $newviewlimit = $item->input['viewlimit'];
  if (substr($newclassname, 0, 13) == 'PluginArchisw') {
      $rootname = strtolower(substr($newclassname, 13));
      $tablename = 'glpi_plugin_archisw_'.getPlural($rootname);
      $fieldname = 'plugin_archisw_'.getPlural($rootname).'_id';
      if (!empty($newasviewon)) {
         $entities = ($newisentitylimited?" `entities_id`,":"");
         $name = ($newistreedropdown?" `completename`,":" `name`,");
         $query = "CREATE VIEW `$tablename` (`id`,$entities `name`, `comment`) AS 
                  SELECT `id`,$entities `name`, `comment` FROM $newasviewon".(empty($newviewlimit)?"":" WHERE $newviewlimit");
         $result = $DB->query($query);
      }
      else {
         $entities = ($newisentitylimited?"`entities_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,":"");
         if (!$newistreedropdown) { //dropdown->create table
            $query = "CREATE TABLE IF NOT EXISTS `".$tablename."` (
                  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,".
                  $entities.
                  "`name` VARCHAR(45) NOT NULL,
                  `comment` VARCHAR(255) NULL,
                  `completename` MEDIUMTEXT NULL,
                  PRIMARY KEY (`id`) ,
                  UNIQUE INDEX `".$tablename."_name` (`name`) )
                  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
            $result = $DB->query($query);
         }
         else { //treedropdown->create table
            $query = "CREATE TABLE IF NOT EXISTS `".$tablename."` (
                        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,".
                        $entities.
                        "`is_recursive` BIT NOT NULL DEFAULT 0,
                        `name` VARCHAR(45) NOT NULL,
                        $fieldname INT(11) UNSIGNED NOT NULL DEFAULT 0,
                        `completename` MEDIUMTEXT NULL,
                        `comment` VARCHAR(255) NULL,
                        `level` INT NOT NULL DEFAULT 0,
                        `sons_cache` LONGTEXT NULL,
                        `ancestors_cache` LONGTEXT NULL,
                        PRIMARY KEY (`id`) ,
                        UNIQUE INDEX `".$tablename."_name` (`name`) )
                        DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
            $result = $DB->query($query);
         }
      }
      create_plugin_archisw_classfiles($dir, $newclassname, $newistreedropdown);
   }
}
function hook_pre_item_update_archisw_configswlink(CommonDBTM $item) {
   global $DB;
   $dir = Plugin::getPhpDir("archisw", true);
   $newclassname = $item->input['name'];
   $newistreedropdown = $item->input['is_tree_dropdown'];
   $newasviewon = $item->input['as_view_on'];
   $newviewlimit = $item->input['viewlimit'];
   $oldclassname = $item->fields['name'];
   $oldistreedropdown = $item->fields['is_tree_dropdown'];
   $oldasviewon = $item->fields['as_view_on'];
   if (substr($newclassname, 0, 13) == 'PluginArchisw') {
      // class is owned by this plugin
      $newrootname = strtolower(substr($newclassname, 13));
      $newfilename = $newrootname;
      $newtablename = 'glpi_plugin_archisw_'.getPlural($newrootname);
      $newfieldname = 'plugin_archisw_'.getPlural($newrootname).'_id';
      if (substr($oldclassname, 0, 13) == 'PluginArchisw') { 
         //old and new types are owned by this plugin
         if ($oldclassname != $newclassname) { 
            //dropdown name modified->rename table
            $oldrootname = strtolower(substr($oldclassname, 13));
            $oldfilename = $oldrootname;
            $oldtablename = 'glpi_plugin_archisw_'.getPlural($oldrootname);
            $oldfieldname = 'plugin_archisw_'.getPlural($oldrootname).'_id';
            $query = "RENAME TABLE `".$oldtablename."` TO `".$newtablename."`";
            $result = $DB->query($query);
            $query = "UPDATE `glpi_plugin_archisw_configswlinks` SET `name` = '".$newclassname."' WHERE `name` = '".$oldclassname."'";
            $result = $DB->query($query);
         }
         else {// no change dropdown name
            // if dropdown table is a view, replace the old view
            if (!empty($newasviewon)) {
               $entities = ($newisentitylimited?" `entities_id`,":"");
               $name = ($newistreedropdown?" `completename`,":" `name`,");
               $query = "CREATE OR REPLACE VIEW `$newtablename` (`id`,$entities `name`, `comment`) AS 
                        SELECT `id`,$entities `name`, `comment` FROM $newasviewon".(empty($newviewlimit)?"":" WHERE $newviewlimit");
               $result = $DB->query($query);
            }
            else {
               // if dropdown table is really a table ...
               if (!$oldistreedropdown && $newistreedropdown) {
               // 'is_tree_dropdown' has changed
               // old type was dropdown and new one is treedropdown=>add the needed fields
                  $query = "ALTER TABLE $newtablename
                     ADD COLUMN `is_recursive` BIT NOT NULL DEFAULT 0 AFTER `id`,
                     ADD COLUMN $newfieldname INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `name`,
                     ADD COLUMN `level` INT NOT NULL DEFAULT 0 AFTER `completename`,
                     ADD COLUMN `sons_cache` LONGTEXT NULL AFTER `level`,
                     ADD COLUMN `ancestors_cache` LONGTEXT NULL AFTER `sons_cache`";
                  $result = $DB->query($query);
               }
               else if ($oldistreedropdown && !$newistreedropdown) {
               // old type was treedropdown and new one is dropdown=>drop the unneeded fields
                  $query = "ALTER TABLE $newtablename
                     DROP COLUMN `is_recursive`,
                     DROP COLUMN $newfieldname,
                     DROP COLUMN `level`,
                     DROP COLUMN `sons_cache`,
                     DROP COLUMN `ancestors_cache`";
                  $result = $DB->query($query);
               }
               // 'is_entity_limited' has changed
               if (!$item->fields['is_entity_limited'] && $item->input['is_entity_limited']) { // 'is_entity_limited' changed from no to yes
               // => add 'entities_id' column to dropdown table
                  $query = "ALTER TABLE $newtablename ADD COLUMN IF NOT EXISTS `entities_id` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `id`";
                  $result = $DB->query($query);
               }
               else if ($item->fields['is_entity_limited'] && !$item->input['is_entity_limited']) { // 'is_entity_limited' changed from yes to no
               // => drop 'entities_id' column from dropdown table
                  $query = "ALTER TABLE $newtablename DROP COLUMN `entities_id`";
                  $result = $DB->query($query);
               }
            }
         }
      }
      else {// old type wasn't owned by this plugin, but the new one is well owned
         //dropdown new->create table or view
         if (!empty($newasviewon)) {
            $entities = ($newisentitylimited?" `entities_id`,":"");
            $name = ($newistreedropdown?" `completename`,":" `name`,");
            $query = "CREATE VIEW `$tablename` (`id`,$entities `name`, `comment`) AS 
                  SELECT `id`,$entities `name`, `comment` FROM $newasviewon".(empty($newviewlimit)?"":" WHERE $newviewlimit");
            $result = $DB->query($query);
         }
         else {
            $entities = ($item->input['is_entity_limited']?"`entities_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,":""); // with or without 'entities_id' column
            if (!$newistreedropdown) {
               // new simple dropdown table
               $query = "CREATE TABLE IF NOT EXISTS `".$newtablename."` (
                  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,".
                  $entities.
                  "`name` VARCHAR(45) NOT NULL,
                  `comment` VARCHAR(255) NULL,
                  `completename` MEDIUMTEXT NULL,
                  PRIMARY KEY (`id`) ,
                  UNIQUE INDEX `".$newtablename."_name` (`name`) )
                  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
            } 
            else { // new treedropdon table
               $query = "CREATE TABLE IF NOT EXISTS `".$newtablename."` (
                  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,".
                  $entities.
                  "`is_recursive` BIT NOT NULL DEFAULT 0,
                  `name` VARCHAR(45) NOT NULL,
                  $newfieldname INT(11) UNSIGNED NOT NULL DEFAULT 0,
                  `completename` MEDIUMTEXT NULL,
                  `comment` VARCHAR(255) NULL,
                  `level` INT NOT NULL DEFAULT 0,
                  `sons_cache` LONGTEXT NULL,
                  `ancestors_cache` LONGTEXT NULL,
                  PRIMARY KEY (`id`) ,
                  UNIQUE INDEX `".$newtablename."_name` (`name`) )
                  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
            }
            $result = $DB->query($query);
         }
      }
      create_plugin_archisw_classfiles($dir, $newclassname, $newistreedropdown);
   }
   if (substr($oldclassname, 0, 13) == 'PluginArchisw'
   && $oldclassname != $newclassname) {
      //old dropdown was owned by this plugin -> drop table if it hasn't been renamed
      $oldrootname = strtolower(substr($oldclassname, 13));
      $oldfilename = $oldrootname;
      $oldtablename = 'glpi_plugin_archisw_'.getPlural($oldrootname);
      $oldfieldname = 'plugin_archisw_'.getPlural($oldrootname).'_id';
      $tableorview = empty($oldasviewon)?"TABLE":"VIEW";
      $query = "DROP $tableorview IF EXISTS `".$oldtablename."`";
      $result = $DB->query($query);
      $query = "DELETE FROM `glpi_plugin_archisw_configswlinks` WHERE `name` = '".$oldclassname."'";
      $result = $DB->query($query);
      // delete files in inc and front directories
      if (file_exists($dir.'/inc/'.$oldfilename.'.class.php')) 
         unlink($dir.'/inc/'.$oldfilename.'.class.php');
      if (file_exists($dir.'/front/'.$oldfilename.'.form.php')) 
         unlink($dir.'/front/'.$oldfilename.'.form.php');
      if (file_exists($dir.'/front/'.$oldfilename.'.php')) 
         unlink($dir.'/front/'.$oldfilename.'.php');
   }
}
function hook_pre_item_purge_archisw_configswlink(CommonDBTM $item) {
   global $DB;
   $dir = Plugin::getPhpDir("archisw", true);
   $oldclassname = $item->fields['name'];
   $oldfilename = strtolower(substr($oldclassname, 13));
   $oldid = $item->fields['id'];
   // suppress in glpi_plugin_archisw_configsws
   $query = "UPDATE `glpi_plugin_archisw_configsws` SET `plugin_archisw_configswlinks_id` = 0 WHERE `plugin_archisw_configswlinks_id` = '".$oldid."'";
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
}
function create_plugin_archisw_classfiles($dir, $newclassname, $istreedropdown = false) {
   if (substr($newclassname, 0, 13) == 'PluginArchisw') {
      $newfilename = strtolower(substr($newclassname, 13));
      $dropdowntype = 'CommonDropdown';
      if ($istreedropdown) $dropdowntype = 'CommonTreeDropdown';
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
      if (!defined('GLPI_ROOT')) {
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
      include ('../../../inc/includes.php');
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
      include ('../../../inc/includes.php');
      \$dropdown = new $newclassname();
      include (GLPI_ROOT . '/front/dropdown.common.php');
      ?>");
      chmod($dir.'/inc/'.$newfilename.'.class.php', 0660);
      chmod($dir.'/front/'.$newfilename.'.form.php', 0660);
      chmod($dir.'/front/'.$newfilename.'.php', 0660);
      // refresh with new files
//      header("Refresh:0");
//   Session::addMessageAfterRedirect(__('Please, refresh the display', 'archisw'));
   }
   return true;
}
?>
