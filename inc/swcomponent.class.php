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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginArchiswSwcomponent extends CommonTreeDropdown {

   public $dohistory=true;
   static $rightname = "plugin_archisw";
   protected $usenotepad         = true;
   
   static $types = array('Computer', 'Project', 'User', 'Software');

   static function getTypeName($nb=0) {

      return _n('Apps Structure', 'Apps Structures', $nb, 'archisw');
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

   switch ($item->getType()) {
        case 'Supplier' :
//      if ($item->getType()=='Supplier') {
			if ($_SESSION['glpishow_count_on_tabs']) {
				return self::createTabEntry(self::getTypeName(2), self::countForItem($item));
			}
			return self::getTypeName(2);
        case 'PluginArchiswSwcomponent' :
			return $this->getTypeName(Session::getPluralNumber());
      }
      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      switch ($item->getType()) {
        case 'Supplier' :
//      if ($item->getType()=='Supplier') {
			$self = new self();
			$self->showPluginFromSupplier($item->getField('id'));
            break;
        case 'PluginArchiswSwcomponent' :
            $item->showChildren();
            break;
      }
      return true;
   }

   static function countForItem(CommonDBTM $item) {

      return countElementsInTable('glpi_plugin_archisw_swcomponents',
                                  "`suppliers_id` = '".$item->getID()."'");
   }

   //clean if swcomponent are deleted
   function cleanDBonPurge() {

//      $temp = new PluginArchiswSwcomponent_Item();
//      $temp->deleteByCriteria(array('plugin_archisw_swcomponents_id' => $this->fields['id']));
   }

   function getSearchOptions() {

      $tab                       = array();

      $tab['common']             = self::getTypeName(2);

      $tab[1]['table']           = $this->getTable();
      $tab[1]['field']           = 'name';
      $tab[1]['name']            = __('Name');
      $tab[1]['datatype']        = 'itemlink';
      $tab[1]['itemlink_type']   = $this->getType();

      $tab[2]['table']           = 'glpi_plugin_archisw_swcomponenttypes';
      $tab[2]['field']           = 'name';
      $tab[2]['name']            = __('Type');
      $tab[2]['datatype']        = 'dropdown';

      $tab[3]['table']           = $this->getTable();
      $tab[3]['field']           = 'level';
      $tab[3]['name']            = __('Level','archisw');
      $tab[3]['datatype']        = 'text';

      $tab[4]['table']           = 'glpi_plugin_archisw_swcomponentstates';
      $tab[4]['field']           = 'name';
      $tab[4]['name']            = __('Status');
      $tab[4]['datatype']        = 'dropdown';

      $tab[5]['table']           = $this->getTable();
      $tab[5]['field']           = 'description';
      $tab[5]['name']            = __('Description');
      $tab[5]['datatype']        = 'text';

      $tab[6]['table']           = 'glpi_plugin_archisw_swcomponenttechnics';
      $tab[6]['field']           = 'name';
      $tab[6]['name']            = __('Development language','archisw');
      $tab[6]['datatype']        = 'dropdown';

      $tab[7]['table']           = 'glpi_plugin_archisw_swcomponentdbs';
      $tab[7]['field']           = 'name';
      $tab[7]['name']            = __('Database','archisw');
      $tab[7]['datatype']        = 'dropdown';

      $tab[8]['table']           = 'glpi_locations';
      $tab[8]['field']           = 'completename';
      $tab[8]['name']            = __('Location');
      $tab[8]['datatype']        = 'dropdown';

      $tab[9]['table']           = 'glpi_suppliers';
      $tab[9]['field']           = 'name';
      $tab[9]['name']            = __('Supplier');
      $tab[9]['datatype']        = 'dropdown';

      $tab[10]['table']           = 'glpi_manufacturers';
      $tab[10]['field']           = 'name';
      $tab[10]['name']            = __('Editor', 'archisw');
      $tab[10]['datatype']        = 'dropdown';

      $tab[11]['table']          = 'glpi_users';
      $tab[11]['field']          = 'name';
      $tab[11]['linkfield']      = 'users_id';
      $tab[11]['name']           = __('Component Maintainer','archisw');
      $tab[11]['datatype']       = 'dropdown';
      $tab[11]['right']          = 'interface';

      $tab[12]['table']          = 'glpi_groups';
      $tab[12]['field']          = 'name';
      $tab[12]['linkfield']      = 'groups_id';
      $tab[12]['name']           = __('Component Owner','archisw');
      $tab[12]['condition']      = '`is_assign`';
      $tab[12]['datatype']       = 'dropdown';

      $tab[13]['table']           = 'glpi_plugin_archisw_swcomponents_items';
      $tab[13]['field']           = 'items_id';
      $tab[13]['nosearch']        = true;
      $tab[13]['massiveaction']   = false;
      $tab[13]['name']            = _n('Associated item' , 'Associated items', 2);
      $tab[13]['forcegroupby']    = true;
      $tab[13]['joinparams']      = array('jointype' => 'child');

      $tab[14]['table']           = $this->getTable();
      $tab[14]['field']           = 'is_recursive';
      $tab[14]['name']            = __('Child entities');
      $tab[14]['datatype']        = 'bool';

/*      $tab[13]['table']          = $this->getTable();
      $tab[13]['field']          = 'is_helpdesk_visible';
      $tab[13]['name']           = __('Associable to a ticket');
      $tab[13]['datatype']       = 'bool';
*/
      $tab[16]['table']          = $this->getTable();
      $tab[16]['field']          = 'date_mod';
      $tab[16]['massiveaction']  = false;
      $tab[16]['name']           = __('Last update');
      $tab[16]['datatype']       = 'datetime';

      $tab[30]['table']          = $this->getTable();
      $tab[30]['field']          = 'id';
      $tab[30]['name']           = __('ID');
      $tab[30]['datatype']       = 'number';

      $tab[80]['table']          = $this->getTable();
      $tab[80]['field']          = 'completename';
      $tab[80]['name']           = __('Apps Structure','archisw');
      $tab[80]['datatype']       = 'dropdown';
      
      $tab[81]['table']       = 'glpi_entities';
      $tab[81]['field']       = 'entities_id';
      $tab[81]['name']        = __('Entity')."-".__('ID');
      
      return $tab;
   }

   //define header form
   function defineTabs($options=array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginArchiswSwcomponent', $ong, $options);
      $this->addStandardTab('PluginArchiswSwcomponent_Item', $ong, $options);
      $this->addStandardTab('Ticket', $ong, $options);
      $this->addStandardTab('Item_Problem', $ong, $options);
      $this->addStandardTab('Change_Item', $ong, $options);
      $this->addStandardTab('Notepad', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }

   /*
    * Return the SQL command to retrieve linked object
    *
    * @return a SQL command which return a set of (itemtype, items_id)
    */
/*   function getSelectLinkedItem () {
      return "SELECT `itemtype`, `items_id`
              FROM `glpi_plugin_archisw_swcomponents_items`
              WHERE `plugin_archisw_swcomponents_id`='" . $this->fields['id']."'";
   }
*/
   function showForm ($ID, $options=array()) {

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      //name of swcomponent
      echo "<td>".__('Name')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"name");
      echo "</td>";
      //version of swcomponent
      echo "<td>".__('Version','archisw')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"version",array('size' => "4"));
      echo "</td>";
      //use startdate of swcomponent
      echo "<td>".__('In use since year','archisw')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"startyear",array('size' => "4"));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //completename of swcomponent
      echo "<td>".__('As child of','archisw').": </td>";
      echo "<td>";
      Dropdown::show('PluginArchiswSwcomponent', array('value' => $this->fields["plugin_archisw_swcomponents_id"]));
      echo "</td>";
      //level of swcomponent
      echo "<td>".__('Level','archisw').": </td>";
      echo "<td>";
      Html::autocompletionTextField($this,"level",array('size' => "2", 'option' => "readonly='readonly'"));
      echo "</td>";
      //shortname of swcomponent
      echo "<td>".__('Short code','archisw')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"shortname",array('size' => "5"));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //description of swcomponent
      echo "<td>".__('Description').":	</td>";
      echo "<td class='top center' colspan='6'>";
      Html::autocompletionTextField($this,"description",array('size' => "140"));
      echo "</td>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      //comment about swcomponent
      echo "<td>".__('Comment').":	</td>";
      echo "<td class='top center' colspan='5'><textarea cols='100' rows='3' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //status of swcomponent
      echo "<td>".__('Status')."</td>";
      echo "<td>";
      Dropdown::show('PluginArchiswSwcomponentState', array('value' => $this->fields["plugin_archisw_swcomponentstates_id"]));
      echo "</td>";
      //status date of swcomponent
      echo "<td  colspan='3'>".__('Status Startdate','archisw')."</td>";
      echo "<td>";
      Html::showDateField("statedate", array('value' => $this->fields["statedate"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //type of swcomponent
      echo "<td>".__('Type')."</td>";
      echo "<td>";
//      Dropdown::show('PluginArchiswSwcomponentType', array('value' => $this->fields["plugin_archisw_swcomponenttypes_id"],'entity' => $this->fields["entities_id"]));
      Dropdown::show('PluginArchiswSwcomponentType', array('value' => $this->fields["plugin_archisw_swcomponenttypes_id"]));
      echo "</td>";
      //language of swcomponent
      echo "<td colspan='3'>".__('Development Language','archisw')."</td>";
      echo "<td>";
      Dropdown::show('PluginArchiswSwcomponentTechnic', array('value' => $this->fields["plugin_archisw_swcomponenttechnics_id"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //instances of swcomponent
      echo "<td>".__('Instances','archisw')."</td>";
      echo "<td>";
      Dropdown::show('PluginArchiswSwcomponentInstance', array('value' => $this->fields["plugin_archisw_swcomponentinstances_id"]));
      echo "</td>";
      //db
      echo "<td colspan='3'>".__('DataBases','archisw')."</td><td>";
      Dropdown::show('PluginArchiswSwcomponentDb', array('value' => $this->fields["plugin_archisw_swcomponentdbs_id"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //target
      echo "<td>".__('Targets','archisw')."</td>";
	  echo "<td>";
      Dropdown::show('PluginArchiswSwcomponentTarget', array('value' => $this->fields["plugin_archisw_swcomponenttargets_id"]));
      echo "</td>";
      //#users
      echo "<td>".__('# users','archisw')."</td>";
      echo "<td>";
      Dropdown::show('PluginArchiswSwcomponentUser', array('value' => $this->fields["plugin_archisw_swcomponentusers_id"]));
      echo "</td>";
      echo "<td>".__('License metric','archisw')."</td>";
      echo "<td>";
      Dropdown::show('PluginArchiswSwcomponentLicense', array('value' => $this->fields["plugin_archisw_swcomponentlicenses_id"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //groups
      echo "<td>".__('Component Owner','archisw')."</td><td>";
      Group::dropdown(array('name'      => 'groups_id', 'value'     => $this->fields['groups_id'], 'entity'    => $this->fields['entities_id'], 'condition' => '`is_assign`'));
      echo "</td>";
      //supplier of swcomponent
      echo "<td colspan='3'>".__('Supplier','archisw')."</td>";
      echo "<td>";
      Dropdown::show('Supplier', array('value' => $this->fields["suppliers_id"],'entity' => $this->fields["entities_id"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //users
      echo "<td>".__('Component Maintainer','archisw')."</td><td>";
      User::dropdown(array('name' => "users_id", 'value' => $this->fields["users_id"], 'entity' => $this->fields["entities_id"], 'right' => 'interface'));
      echo "</td>";
      //manufacturer of swcomponent
      echo "<td colspan='3'>".__('Editor','archisw')."</td>";
      echo "<td>";
      Dropdown::show('Manufacturer', array('value' => $this->fields["manufacturers_id"],'entity' => $this->fields["entities_id"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //url of swcomponent
      echo "<td>".__('URL','archisw')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"address",array('size' => "65"));
       //service level agreement
      echo "<td colspan='3'>".__('Service level','archisw')."</td>";
      echo "<td>";
//      Dropdown::show('PluginArchiswSwcomponentType', array('value' => $this->fields["plugin_archisw_swcomponenttypes_id"],'entity' => $this->fields["entities_id"]));
      Dropdown::show('PluginArchiswSwcomponentSla', array('value' => $this->fields["plugin_archisw_swcomponentslas_id"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      //location of swcomponent
      echo "<td>".__('Location')."</td>";
      echo "<td>";
      Dropdown::show('Location', array('value' => $this->fields["locations_id"],'entity' => $this->fields["entities_id"]));
      echo "</td>";
       echo "</td>";
      //is_helpdesk_visible
      echo "<td>" . __('Associable to a ticket')."</td><td>";
      Dropdown::showYesNo('is_helpdesk_visible',$this->fields['is_helpdesk_visible']);
      echo "</td>";
      echo "</tr>";



      $this->showFormButtons($options);

      return true;
   }
   
   /**
    * Make a select box for link swcomponent
    *
    * Parameters which could be used in options array :
    *    - name : string / name of the select (default is plugin_archisw_swcomponenttypes_id)
    *    - entity : integer or array / restrict to a defined entity or array of entities
    *                   (default -1 : no restriction)
    *    - used : array / Already used items ID: not to display in dropdown (default empty)
    *
    * @param $options array of possible options
    *
    * @return nothing (print out an HTML select box)
   **/
   static function dropdownSwcomponent($options=array()) {
      global $DB, $CFG_GLPI;


      $p['name']    = 'plugin_archisw_swcomponents_id';
      $p['entity']  = '';
      $p['used']    = array();
      $p['display'] = true;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }

      $where = " WHERE `glpi_plugin_archisw_swcomponents`.`is_deleted` = '0' ".
                       getEntitiesRestrictRequest("AND", "glpi_plugin_archisw_swcomponents", '', $p['entity'], true);

      $p['used'] = array_filter($p['used']);
      if (count($p['used'])) {
         $where .= " AND `id` NOT IN (0, ".implode(",",$p['used']).")";
      }

      $query = "SELECT *
                FROM `glpi_plugin_archisw_swcomponenttypes`
                WHERE `id` IN (SELECT DISTINCT `plugin_archisw_swcomponenttypes_id`
                               FROM `glpi_plugin_archisw_swcomponents`
                             $where)
                ORDER BY `name`";
      $result = $DB->query($query);

      $values = array(0 => Dropdown::EMPTY_VALUE);

      while ($data = $DB->fetch_assoc($result)) {
         $values[$data['id']] = $data['name'];
      }
      $rand = mt_rand();
      $out  = Dropdown::showFromArray('_swcomponenttype', $values, array(/*'width'   => '15%',*/
                                                                     'rand'    => $rand,
                                                                     'display' => false));
      $field_id = Html::cleanId("dropdown__swcomponenttype$rand");

      $params   = array('swcomponenttype' => '__VALUE__',
                        'entity' => $p['entity'],
                        'rand'   => $rand,
                        'myname' => $p['name'],
                        'used'   => $p['used']);

      $out .= Ajax::updateItemOnSelectEvent($field_id,"show_".$p['name'].$rand,
                                            $CFG_GLPI["root_doc"]."/plugins/archisw/ajax/dropdownTypeArchisw.php",
                                            $params, false);
      $out .= "<span id='show_".$p['name']."$rand'>";
      $out .= "</span>\n";

      $params['swcomponenttype'] = 0;
      $out .= Ajax::updateItem("show_".$p['name'].$rand,
                               $CFG_GLPI["root_doc"]. "/plugins/archisw/ajax/dropdownTypeArchisw.php",
                               $params, false);
      $query = "SELECT `id`,`name`
                FROM `glpi_plugin_archisw_swcomponents_itemroles`
                WHERE `itemtype` = '".$_GET['_itemtype']."'" ;
      $result = $DB->query($query);

      $values = array(0 => Dropdown::EMPTY_VALUE);

      while ($data = $DB->fetch_assoc($result)) {
         $values[$data['id']] = $data['name'];
      }
      $out .= Dropdown::showFromArray('plugin_archisw_swcomponents_itemroles_id', $values, array(/*'width'   => '20%',*/
                                                                     'rand'    => $rand,
                                                                     'display' => false));
      $out .= "<input name='comment'>";
      if ($p['display']) {
         echo $out;
         return $rand;
      }
      return $out;
   }

   /**
    * For other plugins, add a type to the linkable types
    *
    * @since version 1.3.0
    *
    * @param $type string class name
   **/
   static function registerType($type) {
      if (!in_array($type, self::$types)) {
         self::$types[] = $type;
      }
   }


   /**
    * Type than could be linked to a Rack
    *
    * @param $all boolean, all type, or only allowed ones
    *
    * @return array of types
   **/
   static function getTypes($all=false) {

      if ($all) {
         return self::$types;
      }

      // Only allowed types
      $types = self::$types;

      foreach ($types as $key => $type) {
         if (!class_exists($type)) {
            continue;
         }

         $item = new $type();
         if (!$item->canView()) {
            unset($types[$key]);
         }
      }
      return $types;
   }


   function showPluginFromSupplier($ID,$withtemplate='') {
      global $DB,$CFG_GLPI;

      $item = new Supplier();
      $canread = $item->can($ID,READ);
      $canedit = $item->can($ID,UPDATE);

      $query = "SELECT `glpi_plugin_archisw_swcomponents`.* "
        ."FROM `glpi_plugin_archisw_swcomponents` "
        ." LEFT JOIN `glpi_entities` ON (`glpi_entities`.`id` = `glpi_plugin_archisw_swcomponents`.`entities_id`) "
        ." WHERE `suppliers_id` = '$ID' "
        . getEntitiesRestrictRequest(" AND ","glpi_plugin_archisw_swcomponents",'','',$this->maybeRecursive());
      $query.= " ORDER BY `glpi_plugin_archisw_swcomponents`.`name` ";

      $result = $DB->query($query);
      $number = $DB->numrows($result);

      if (Session::isMultiEntitiesMode()) {
         $colsup=1;
      } else {
         $colsup=0;
      }

      if ($withtemplate!=2) echo "<form method='post' action=\"".$CFG_GLPI["root_doc"]."/plugins/archisw/front/swcomponent.form.php\">";

      echo "<div align='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='".(4+$colsup)."'>"._n('App structure associated', 'App structures associated', 2, 'archisw')."</th></tr>";
      echo "<tr><th>".__('Name','archisw')."</th>";
      if (Session::isMultiEntitiesMode())
         echo "<th>".__('Entity')."</th>";
      echo "<th>".PluginArchiswSwcomponentType::getTypeName(1)."</th>";
      echo "<th>".__('Type')."</th>";
      echo "<th>".__('Comments')."</th>";

      echo "</tr>";

      while ($data=$DB->fetch_array($result)) {

         echo "<tr class='tab_bg_1".($data["is_deleted"]=='1'?"_2":"")."'>";
         if ($withtemplate!=3 && $canread && (in_array($data['entities_id'],$_SESSION['glpiactiveentities']) || $data["is_recursive"])) {
            echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/archisw/front/swcomponent.form.php?id=".$data["id"]."'>".$data["name"];
         if ($_SESSION["glpiis_ids_visible"]) echo " (".$data["id"].")";
            echo "</a></td>";
         } else {
            echo "<td class='center'>".$data["name"];
            if ($_SESSION["glpiis_ids_visible"]) echo " (".$data["id"].")";
            echo "</td>";
         }
         echo "</a></td>";
         if (Session::isMultiEntitiesMode())
            echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";
         echo "<td>".Dropdown::getDropdownName("glpi_plugin_archisw_swcomponenttypes",$data["plugin_archisw_swcomponenttypes_id"])."</td>";
         echo "<td>".Dropdown::getDropdownName("glpi_plugin_archisw_swcomponentstates",$data["plugin_archisw_swcomponentstates_id"])."</td>";
         echo "<td>".$data["comment"]."</td></tr>";
      }
      echo "</table></div>";
      Html::closeForm();
   }
   
   /**
    * @since version 0.85
    *
    * @see CommonDBTM::getSpecificMassiveActions()
   **/
   function getSpecificMassiveActions($checkitem=NULL) {
      $isadmin = static::canUpdate();
      $actions = parent::getSpecificMassiveActions($checkitem);

      if ($_SESSION['glpiactiveprofile']['interface'] == 'central') {
         if ($isadmin) {
            $actions['PluginArchiswSwcomponent'.MassiveAction::CLASS_ACTION_SEPARATOR.'install']    = _x('button', 'Associate');
            $actions['PluginArchiswSwcomponent'.MassiveAction::CLASS_ACTION_SEPARATOR.'uninstall'] = _x('button', 'Dissociate');
            $actions['PluginArchiswSwcomponent'.MassiveAction::CLASS_ACTION_SEPARATOR.'duplicate'] = _x('button', 'Duplicate');

            if (Session::haveRight('transfer', READ)
                     && Session::isMultiEntitiesMode()
            ) {
               $actions['PluginArchiswSwcomponent'.MassiveAction::CLASS_ACTION_SEPARATOR.'transfer'] = __('Transfer');
            }
         }
      }
      return $actions;
   }
   
   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   static function showMassiveActionsSubForm(MassiveAction $ma) {

      switch ($ma->getAction()) {
         case 'plugin_archisw_add_item':
            self::dropdownDataflow(array());
            echo "&nbsp;".
                 Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
            return true;
            break;
         case "install" :
            Dropdown::showSelectItemFromItemtypes(array('items_id_name' => 'item_item',
                                                        'itemtype_name' => 'typeitem',
                                                        'itemtypes'     => self::getTypes(true),
                                                        'checkright'
                                                                        => true,
                                                  ));
            echo Html::submit(_x('button', 'Post'), array('name' => 'massiveaction'));
            return true;
            break;
         case "uninstall" :
            Dropdown::showSelectItemFromItemtypes(array('items_id_name' => 'item_item',
                                                        'itemtype_name' => 'typeitem',
                                                        'itemtypes'     => self::getTypes(true),
                                                        'checkright'
                                                                        => true,
                                                  ));
            echo Html::submit(_x('button', 'Post'), array('name' => 'massiveaction'));
            return true;
            break;
         case "duplicate" :
		    $options = array();
			$options['value'] = 1;
			$options['min'] = 1;
			$options['max'] = 20;
			$options['unit'] = "times";
            Dropdown::showNumber('repeat', $options);
            echo Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
            return true;
            break;
         case "transfer" :
            Dropdown::show('Entity');
            echo Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
            return true;
            break;
    }
      return parent::showMassiveActionsSubForm($ma);
   }
   
   
   /**
    * @since version 0.85
    *
    * @see CommonDBTM::processMassiveActionsForOneItemtype()
   **/
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {
      global $DB;
      
      $dataflow_item = new PluginDataflowsDataflow_Item();
      
      switch ($ma->getAction()) {
         case "plugin_archisw_add_item":
            $input = $ma->getInput();
            foreach ($ids as $id) {
               $input = array('plugin_archisw_swcomponenttypes_id' => $input['plugin_archisw_swcomponenttypes_id'],
                                 'items_id'      => $id,
                                 'itemtype'      => $item->getType());
               if ($dataflow_item->can(-1,UPDATE,$input)) {
                  if ($dataflow_item->add($input)) {
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $ids, MassiveAction::ACTION_KO);
                  }
               } else {
                  $ma->itemDone($item->getType(), $ids, MassiveAction::ACTION_KO);
               }
            }

            return;
         case "transfer" :
            $input = $ma->getInput();
            if ($item->getType() == 'PluginArchiswSwcomponent') {
            foreach ($ids as $key) {
                  $item->getFromDB($key);
                  $type = PluginArchiswSwcomponentType::transfer($item->fields["plugin_archisw_swcomponenttypes_id"], $input['entities_id']);
                  if ($type > 0) {
                     $values["id"] = $key;
                     $values["plugin_archisw_swcomponenttypes_id"] = $type;
                     $item->update($values);
                  }

                  unset($values);
                  $values["id"] = $key;
                  $values["entities_id"] = $input['entities_id'];

                  if ($item->update($values)) {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                  }
               }
            }
            return;

         case 'install' :
            $input = $ma->getInput();
            foreach ($ids as $key) {
               if ($item->can($key, UPDATE)) {
                  $values = array('plugin_archisw_swcomponents_id' => $key,
                                 'items_id'      => $input["item_item"],
                                 'itemtype'      => $input['typeitem']);
                  if ($dataflow_item->add($values)) {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                  }
               } else {
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_NORIGHT);
                  $ma->addMessage($item->getErrorMessage(ERROR_RIGHT));
               }
            }
            return;
            
         case 'uninstall':
            $input = $ma->getInput();
            foreach ($ids as $key) {
               if ($val == 1) {
                  if ($dataflow_item->deleteItemBySwcomponentsAndItem($key,$input['item_item'],$input['typeitem'])) {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                  }
               }
            }
            return;

         case "duplicate" :
            $input = $ma->getInput();
            if ($item->getType() == 'PluginArchiswSwcomponent') {
            foreach ($ids as $key) {
				  $success = array();
				  $failure = array();
                  $item->getFromDB($key);
				  $values = $item->fields;
				  $name = $values["name"];

                  unset($values["id"]);
				  for ($i = 1 ; $i <= $input['repeat'] ; $i++) {
					$values["name"] = $name . " (Copy $i)";

					if ($item->add($values)) {
						$success[] = $key;
					} else {
						$failure[] = $key;
					}
				  }
				  if ($success) {
				    $ma->itemDone('PluginArchiswSwcomponent', $key, MassiveAction::ACTION_OK);
				  }
				  if ($failure) {
					$ma->itemDone('PluginArchiswSwcomponent', $key, MassiveAction::ACTION_KO);
				  }
               }
            }
            return;

      }
      parent::processMassiveActionsForOneItemtype($ma, $item, $ids);
   }
   
}

?>