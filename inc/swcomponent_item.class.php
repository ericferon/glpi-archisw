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

class PluginArchiswSwcomponent_Item extends CommonDBRelation {

   // From CommonDBRelation
   static public $itemtype_1 = "PluginArchiswSwcomponent";
   static public $items_id_1 = 'plugin_archisw_swcomponents_id';
   static public $take_entity_1 = false ;
    
   static public $itemtype_2 = 'itemtype';
   static public $items_id_2 = 'items_id';
   static public $take_entity_2 = true ;
   
   static $rightname = "plugin_archisw";

   
   /*static function getTypeName($nb=0) {

      if ($nb > 1) {
         return _n('Swcomponent item', 'Swcomponents items', 2, 'archisw');
      }
      return _n('Swcomponent item', 'Swcomponents items', 1, 'archisw');
   }*/

   /**
    * Clean table when item is purged
    *
    * @param $item Object to use
    *
    * @return nothing
    **/
   public static function cleanForItem(CommonDBTM $item) {

      $temp = new self();
      $temp->deleteByCriteria(
         array('itemtype' => $item->getType(),
               'items_id' => $item->getField('id'))
      );
   }

   /**
    * Get Tab Name used for itemtype
    *
    * NB : Only called for existing object
    *      Must check right on what will be displayed + template
    *
    * @since version 0.83
    *
    * @param $item            CommonDBTM object for which the tab need to be displayed
    * @param $withtemplate    boolean  is a template object ? (default 0)
    *
    *  @return string tab name
    **/
   public function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate) {
         if ($item->getType()=='PluginArchiswSwcomponent'
             && count(PluginArchiswSwcomponent::getTypes(false))) {
            if ($_SESSION['glpishow_count_on_tabs']) {
               return self::createTabEntry(_n('Associated item','Associated items',2), self::countForSwcomponent($item));
            }
            return _n('Associated item','Associated items',2);

         } else if (in_array($item->getType(), PluginArchiswSwcomponent::getTypes(true))
                    && Session::haveRight('plugin_archisw', READ)) {
            if ($_SESSION['glpishow_count_on_tabs']) {
               return self::createTabEntry(PluginArchiswSwcomponent::getTypeName(2), self::countForItem($item));
            }
            return PluginArchiswSwcomponent::getTypeName(2);
         }
      }
      return '';
   }

   /**
    * show Tab content
    *
    * @since version 0.83
    *
    * @param $item                  CommonGLPI object for which the tab need to be displayed
    * @param $tabnum       integer  tab number (default 1)
    * @param $withtemplate boolean  is a template object ? (default 0)
    *
    * @return true
    **/
   public static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='PluginArchiswSwcomponent') {

         self::showForSwcomponent($item);

      } else if (in_array($item->getType(), PluginArchiswSwcomponent::getTypes(true))) {

         self::showForITem($item);
      }
      return true;
   }

   static function countForSwcomponent(PluginArchiswSwcomponent $item) {

      $types = implode("','", $item->getTypes());
      if (empty($types)) {
         return 0;
      }
      $dbu = new DbUtils();
      return $dbu->countElementsInTable('glpi_plugin_archisw_swcomponents_items',
                                  "`itemtype` IN ('$types')
                                   AND `plugin_archisw_swcomponents_id` = '".$item->getID()."'");
   }


   static function countForItem(CommonDBTM $item) {

      $dbu = new DbUtils();
      return $dbu->countElementsInTable('glpi_plugin_archisw_swcomponents_items',
                                  "`itemtype`='".$item->getType()."'
                                   AND `items_id` = '".$item->getID()."'");
   }

   function getFromDBbySwcomponentsAndItem($plugin_archisw_swcomponents_id, $items_id, $itemtype) {
      global $DB;

      $query = "SELECT * FROM `".$this->getTable()."` " .
         "WHERE `plugin_archisw_swcomponents_id` = '" . $plugin_archisw_swcomponents_id . "'
         AND `itemtype` = '" . $items_id . "'
         AND `items_id` = '" . $itemtype . "'";
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 1) {
            return false;
         }
         $this->fields = $DB->fetch_assoc($result);
         if (is_array($this->fields) && count($this->fields)) {
            return true;
         } else {
            return false;
         }
      }
      return false;
   }

   function addItem($values) {

      $this->add(array_slice($values, 0, -2));

   }

   function deleteItemBySwcomponentsAndItem($plugin_archisw_swcomponents_id,$items_id,$itemtype) {

      if ($this->getFromDBbySwcomponentsAndItem($plugin_archisw_swcomponents_id,$items_id,$itemtype)) {
         $this->delete(array('id'=>$this->fields["id"]));
      }
   }

   /**
    * @since version 0.84
   **/
   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }
   /**
    * Show items links to a swcomponent
    *
    * @since version 0.84
    *
    * @param $swcomponent PluginArchiswSwcomponent object
    *
    * @return nothing (HTML display)
    **/
   public static function showForSwcomponent(PluginArchiswSwcomponent $swcomponent) {
      global $DB, $CFG_GLPI;

      $instID = $swcomponent->fields['id'];
      if (!$swcomponent->can($instID, READ))   return false;

      $rand=mt_rand();

      $canedit=$swcomponent->can($instID, UPDATE);

      $query = "SELECT DISTINCT `itemtype`
             FROM `glpi_plugin_archisw_swcomponents_items`
             WHERE `plugin_archisw_swcomponents_id` = '$instID'
             ORDER BY `itemtype`
             LIMIT ".count(PluginArchiswSwcomponent::getTypes(true));

      $result = $DB->query($query);
      $number = $DB->numrows($result);

      if (Session::isMultiEntitiesMode()) {
         $colsup=1;
      } else {
         $colsup=0;
      }

      if ($canedit) {
         echo "<div class='firstbloc'>";
         echo "<form method='post' name='archisw_form$rand' id='archisw_form$rand'
         action='".Toolbox::getItemTypeFormURL("PluginArchiswSwcomponent")."'>";

         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_2'><th colspan='".($canedit?(5+$colsup):(4+$colsup))."'>".
            __('Add an item')."</th></tr>";

         echo "<tr class='tab_bg_1'><td colspan='".(3+$colsup)."' class='center'>";
         echo "<input type='hidden' name='plugin_archisw_swcomponents_id' value='$instID'>";
		 $options=array();
		 $options['items_id_name']='items_id';
		 $options['entity_restrict']=($swcomponent->fields['is_recursive']?-1:$swcomponent->fields['entities_id']);
		 $options['itemtypes']=PluginArchiswSwcomponent::getTypes();
		 $randitemtype=Dropdown::showSelectItemFromItemtypes($options);
         echo "</td>";
		 echo "<td>";
		 echo "<select name='plugin_archisw_swcomponents_itemroles_id' id='dropdown_plugin_archisw_swcomponents_itemroles_id$randitemtype'>";
		 echo "</select>";
		 $entity_restrict = '';
		 $used=array();
		 $params=array('itemtype'=>'__VALUE__',
				'entity_restrict'=>$entity_restrict,
				'rand'=>$randitemtype,
				'myname'=>'plugin_archisw_swcomponents_itemroles_id',
				'used'=>$used
		 );
		 $field_id = Html::cleanId("dropdown_itemtype".$randitemtype);
		 Ajax::updateItemOnSelectEvent($field_id,"dropdown_plugin_archisw_swcomponents_itemroles_id".$randitemtype,
                                            $CFG_GLPI["root_doc"]."/plugins/archisw/ajax/dropdownItemRole.php",
                                            $params, true);
		 echo "</td>";
		 echo "<td>";
         echo "<input name='comment'>";
		 echo "</td>";
         echo "<td colspan='2' class='tab_bg_2'>";
         echo "<input type='submit' name='additem' value=\""._sx('button','Add')."\" class='submit'>";
         echo "</td></tr>";
         echo "</table>" ;
         Html::closeForm();
         echo "</div>" ;
      }

      echo "<div class='spaced'>";
      if ($canedit && $number) {
         Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
         $massiveactionparams = array();
         Html::showMassiveActions($massiveactionparams);
      }
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";

      if ($canedit && $number) {
         echo "<th width='10'>".Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand)."</th>";
      }

      echo "<th>".__('Type')."</th>";
      echo "<th>".__('Name')."</th>";
      echo "<th>".__('Role','archisw')."</th>";
      echo "<th>".__('Comment')."</th>";
      if (Session::isMultiEntitiesMode())
         echo "<th>".__('Entity')."</th>";
      echo "<th>".__('Serial number')."</th>";
//      echo "<th>".__('Inventory number')."</th>";
      echo "</tr>";

      for ($i=0 ; $i < $number ; $i++) {
         $itemType=$DB->result($result, $i, "itemtype");

         if (!($item = getItemForItemtype($itemType))) {
            continue;
         }

         if ($item->canView()) {
            $column="name";
            $itemTable = getTableForItemType($itemType);

             if ($itemType!='Entity') {
                  $query = "SELECT `".$itemTable."`.*, `glpi_plugin_archisw_swcomponents_items`.`id` AS items_id, `glpi_plugin_archisw_swcomponents_items`.`comment` AS table_items_comment, `glpi_entities`.`id` AS entity, `glpi_plugin_archisw_swcomponents_itemroles`.`name` AS role "
                  ." FROM (`".$itemTable."`"
                  ." LEFT JOIN `glpi_entities` ON `glpi_entities`.`id` = `".$itemTable."`.`entities_id`)"
				  .", (`glpi_plugin_archisw_swcomponents_items`"
				  ." LEFT JOIN `glpi_plugin_archisw_swcomponents_itemroles` ON `glpi_plugin_archisw_swcomponents_items`.`plugin_archisw_swcomponents_itemroles_id` = `glpi_plugin_archisw_swcomponents_itemroles`.`id`)"
                  ." WHERE `".$itemTable."`.`id` = `glpi_plugin_archisw_swcomponents_items`.`items_id`
                  AND `glpi_plugin_archisw_swcomponents_items`.`itemtype` = '$itemType'
                  AND `glpi_plugin_archisw_swcomponents_items`.`plugin_archisw_swcomponents_id` = '$instID' "
                  . getEntitiesRestrictRequest(" AND ",$itemTable,'','',$item->maybeRecursive());

                  if ($item->maybeTemplate()) {
                     $query.=" AND ".$itemTable.".is_template='0'";
                  }
                  $query.=" ORDER BY `glpi_entities`.`completename`, `".$itemTable."`.`$column` ";
               } else {
                  $query = "SELECT `".$itemTable."`.*, `glpi_plugin_archisw_swcomponents_items`.`id` AS items_id, `glpi_entities`.`id` AS entity "
                  ." FROM `glpi_plugin_archisw_swcomponents_items`, `".$itemTable
                  ."` WHERE `".$itemTable."`.`id` = `glpi_plugin_archisw_swcomponents_items`.`items_id`
                  AND `glpi_plugin_archisw_swcomponents_items`.`itemtype` = '$itemType'
                  AND `glpi_plugin_archisw_swcomponents_items`.`plugin_archisw_swcomponents_id` = '$instID' "
                  . getEntitiesRestrictRequest(" AND ",$itemTable,'','',$item->maybeRecursive());

                  if ($item->maybeTemplate()) {
                     $query.=" AND ".$itemTable.".is_template='0'";
                  }
                  $query.=" ORDER BY `glpi_entities`.`completename`, `".$itemTable."`.`$column` ";
               }

//file_put_contents("adebug.log","entering swcomponent_item.class - showForSwcomponent : query \n$query\n",FILE_APPEND);
            if ($result_linked=$DB->query($query)) {
               if ($DB->numrows($result_linked)) {

                  Session::initNavigateListItems($itemType,PluginArchiswSwcomponent::getTypeName(2)." = ".$swcomponent->fields['name']);

                  while ($data=$DB->fetch_assoc($result_linked)) {

                     $item->getFromDB($data["id"]);

                     Session::addToNavigateListItems($itemType,$data["id"]);

                     $ID="";

                     if ($_SESSION["glpiis_ids_visible"]||empty($data["name"]))
                        $ID= " (".$data["id"].")";

                     $link=Toolbox::getItemTypeFormURL($itemType);
                     $name= "<a href=\"".$link."?id=".$data["id"]."\">"
                        .$data["name"]."$ID</a>";

                     echo "<tr class='tab_bg_1'>";

                     if ($canedit) {
                        echo "<td width='10'>";
                        Html::showMassiveActionCheckBox(__CLASS__, $data["items_id"]);
                        echo "</td>";
                     }
                     echo "<td class='center'>".$item::getTypeName(1)."</td>";

                     echo "<td class='center' ".(isset($data['is_deleted'])&&$data['is_deleted']?"class='tab_bg_2_2'":"").
                        ">".$name."</td>";
					 echo "<td class='center'>".(isset($data["role"])? "".$data["role"]."" :"-")."</td>";
                     echo "<td class='center'>".(isset($data["table_items_comment"])? "".$data["table_items_comment"]."" :"-")."</td>";
     
                     if (Session::isMultiEntitiesMode())
                        echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entity'])."</td>";

                     echo "<td class='center'>".(isset($data["serial"])? "".$data["serial"]."" :"-")."</td>";
//                     echo "<td class='center'>".(isset($data["otherserial"])? "".$data["otherserial"]."" :"-")."</td>";

                     echo "</tr>";
                  }
               }
            }
         }
      }
      echo "</table>";

      if ($canedit && $number) {
         $paramsma['ontop'] =false;
         Html::showMassiveActions($paramsma);
         Html::closeForm();
      }
      echo "</div>";
   }

   /**
   * Show swcomponents associated to an item
   *
   * @since version 0.84
   *
   * @param $item            CommonDBTM object for which associated swcomponents must be displayed
   * @param $withtemplate    (default '')
   **/
   static function showForItem(CommonDBTM $item, $withtemplate='') {
      global $DB, $CFG_GLPI;

      $swcomponents	= array();
      $swcomponent	= new PluginArchiswSwcomponent();
      $used			= array();

      $ID = $item->getField('id');

      if ($item->isNewID($ID)) {
         return false;
      }
      if (!Session::haveRight('plugin_archisw', READ)) {
         return false;
      }

      if (!$item->can($item->fields['id'], READ)) {
         return false;
      }

      if (empty($withtemplate)) {
         $withtemplate = 0;
      }

      $canedit       =  $item->canadditem('PluginArchiswSwcomponent');
      $rand          = mt_rand();
      $is_recursive  = $item->isRecursive();

    $itemtype = $item->getType();
	  $table = $item->getTable();
	  $query = "SELECT "
	    ."lk.`id` AS items_id, lk.`plugin_archisw_swcomponents_itemroles_id` AS items_itemroles_id, lk.`comment` AS items_comment, `glpi_plugin_archisw_swcomponents`.* ";
      $query .= " FROM ";
	  $query .= " `glpi_plugin_archisw_swcomponents_items` as lk, `glpi_plugin_archisw_swcomponents` ";
      $query .= " LEFT JOIN `glpi_entities` ON (`glpi_entities`.`id` = `glpi_plugin_archisw_swcomponents`.`entities_id`) ";
      $query .= " WHERE lk.`items_id` = '".$ID."'";
	  $query .= " AND lk.`plugin_archisw_swcomponents_id`=`glpi_plugin_archisw_swcomponents`.`id` "
        ." AND lk.`itemtype` = '".$itemtype."'";

      $query.= " ORDER BY `glpi_plugin_archisw_swcomponents`.`name` ";

//file_put_contents("../adebug.log","entering swcomponent_item.class - showForItem\n$query\n",FILE_APPEND);
      $result = $DB->query($query);
      $number = $DB->numrows($result);
      $i      = 0;

      if ($numrows = $DB->numrows($result)) {
         while ($data = $DB->fetch_assoc($result)) {
            $swcomponents[$data['items_id']] = $data;
            $used[$data['id']] = $data['id'];
         }
      }

      if ($canedit && $withtemplate < 2) {
         // Restrict entity for knowbase
         $entities = "";
         $entity   = $_SESSION["glpiactive_entity"];

         if ($item->isEntityAssign()) {
            /// Case of personal items : entity = -1 : create on active entity (Reminder case))
            if ($item->getEntityID() >=0 ) {
               $entity = $item->getEntityID();
            }

            if ($item->isRecursive()) {
               $entities = getSonsOf('glpi_entities',$entity);
            } else {
               $entities = $entity;
            }
         }
         $limit = getEntitiesRestrictRequest(" AND ","glpi_plugin_archisw_swcomponents",'',$entities,true);
         $q = "SELECT COUNT(*)
               FROM `glpi_plugin_archisw_swcomponents`
               WHERE `is_deleted` = '0'
               $limit";

         $result = $DB->query($q);
         $nb     = $DB->result($result,0,0);

         echo "<div class='firstbloc'>";


         if (Session::haveRight('plugin_archisw', READ)
             && ($nb > count($used))) {
            echo "<form name='swcomponent_form$rand' id='swcomponent_form$rand' method='post'
                   action='".Toolbox::getItemTypeFormURL('PluginArchiswSwcomponent')."'>";
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_1'>";
            echo "<td colspan='4' class='center'>";
            echo "<input type='hidden' name='entities_id' value='$entity'>";
            echo "<input type='hidden' name='is_recursive' value='$is_recursive'>";
            echo "<input type='hidden' name='itemtype' value='".$item->getType()."'>";
            echo "<input type='hidden' name='items_id' value='$ID'>";
            if ($item->getType() == 'Ticket') {
               echo "<input type='hidden' name='tickets_id' value='$ID'>";
            }
            
            PluginArchiswSwcomponent::dropdownSwcomponent(array('entity' => $entities ,
                                                     'used'   => $used));

            echo "</td>";
			echo "<td class='center' width='20%'>";
            echo "<input type='submit' name='additem' value=\"".
                     _sx('button', 'Associate an ', 'archisw').PluginArchiswSwcomponent::getTypeName(1)."\" class='submit'>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            Html::closeForm();
         }

         echo "</div>";
      }

      echo "<div class='spaced'>";
      if ($canedit && $number && ($withtemplate < 2)) {
         Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
         $massiveactionparams = array('num_displayed'  => $number);
         Html::showMassiveActions($massiveactionparams);
      }
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      if ($canedit && $number && ($withtemplate < 2)) {
         echo "<th width='10'>".Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand)."</th>";
      }
      echo "<th>".__('Name')."</th>";
      if (Session::isMultiEntitiesMode()) {
         echo "<th>".__('Entity')."</th>";
      }
      echo "<th>".__('Component Owner','archisw')."</th>";
      echo "<th>".__('Type')."</th>";
      echo "<th>".__('Status')."</th>";
      echo "<th>".__('Role','archisw')."</th>";
      echo "<th>".__('Comment')."</th>";
      echo "<th>".__('Supplier')."</th>";
      echo "</tr>";
      $used = array();

      if ($number) {

         Session::initNavigateListItems('PluginArchiswSwcomponent',
                           //TRANS : %1$s is the itemtype name,
                           //        %2$s is the name of the item (used for headings of a list)
                                        sprintf(__('%1$s = %2$s'),
                                                $item->getTypeName(1), $item->getName()));


         foreach  ($swcomponents as $data) {
            $swcomponentID        = $data["id"];
            $link             = NOT_AVAILABLE;

            if ($swcomponent->getFromDB($swcomponentID)) {
               $link         = $swcomponent->getLink();
            }

            Session::addToNavigateListItems('PluginArchiswSwcomponent', $swcomponentID);

            $used[$swcomponentID]   = $swcomponentID;
            $items_id             = $data["items_id"];

            echo "<tr class='tab_bg_1".($data["is_deleted"]?"_2":"")."'>";
            if ($canedit && ($withtemplate < 2)) {
               echo "<td width='10'>";
               Html::showMassiveActionCheckBox(__CLASS__, $data["items_id"]);
               echo "</td>";
            }
            echo "<td class='center'>$link</td>";
            if (Session::isMultiEntitiesMode()) {
               echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities", $data['entities_id']).
                    "</td>";
            }
			echo "<td class='center'>".Dropdown::getDropdownName("glpi_groups",$data["groups_id"])."</td>";

			echo "<td>".Dropdown::getDropdownName("glpi_plugin_archisw_swcomponenttypes",$data["plugin_archisw_swcomponenttypes_id"])."</td>";
			echo "<td>".((isset($data["plugin_archisw_swcomponentstates_id"]) && $data["plugin_archisw_swcomponentstates_id"] != '')? Dropdown::getDropdownName("glpi_plugin_archisw_swcomponentstates",$data["plugin_archisw_swcomponentstates_id"]) :"-")."</td>";
			echo "<td class='center'>".((isset($data["items_itemroles_id"]) && $data["items_itemroles_id"] != 0)? Dropdown::getDropdownName("glpi_plugin_archisw_swcomponents_itemroles",$data["items_itemroles_id"]) :"-")."</td>";
			echo "<td class='center'>".((isset($data["items_comment"]) && $data["items_comment"] != '')? "".$data["items_comment"]."" :"-")."</td>";
            echo "<td>";
            echo "<a href=\"".$CFG_GLPI["root_doc"]."/front/supplier.form.php?id=".$data["suppliers_id"]."\">";
            echo Dropdown::getDropdownName("glpi_suppliers",$data["suppliers_id"]);
            if ($_SESSION["glpiis_ids_visible"] == 1 )
               echo " (".$data["suppliers_id"].")";
            echo "</a></td>";
            echo "</tr>";
            $i++;
         }
      }


      echo "</table>";
      if ($canedit && $number && ($withtemplate < 2)) {
         $massiveactionparams['ontop'] = false;
         Html::showMassiveActions($massiveactionparams);
         Html::closeForm();
      }
      echo "</div>";
   }
}

?>
