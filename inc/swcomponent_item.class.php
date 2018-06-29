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

   static function showForItem(CommonDBTM $item, $withtemplate='') {
      global $DB, $CFG_GLPI;

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
	  if ($itemtype == "Project")
		$query .= ", lk_funcarea.`items_id` AS funcarea_id, glpi_plugin_archifun_funcareas.`name` AS funcarea_name ";
      $query .= " FROM ";
	  if ($itemtype == "Project")
		$query .= " `$table` as lk_funcarea, `glpi_plugin_archifun_funcareas`, ";
	  $query .= " `glpi_plugin_archisw_swcomponents_items` as lk, `glpi_plugin_archisw_swcomponents` ";
      $query .= " LEFT JOIN `glpi_entities` ON (`glpi_entities`.`id` = `glpi_plugin_archisw_swcomponents`.`entities_id`) ";
      $query .= " WHERE lk.`items_id` = '".$ID."'";
 	  if ($itemtype == "Project")
        $query .= " AND lk_funcarea.`itemtype` = 'PluginArchifunFuncarea' "
		." AND glpi_plugin_archifun_funcareas.id = lk_funcarea.items_id "
        ." AND lk_funcarea.`plugin_archisw_swcomponents_id`=`glpi_plugin_archisw_swcomponents`.`id` "
        . getEntitiesRestrictRequest(" AND ","glpi_plugin_archisw_swcomponents",'','',$PluginArchiswSwcomponent->maybeRecursive());
		$query .= " AND lk.`plugin_archisw_swcomponents_id`=`glpi_plugin_archisw_swcomponents`.`id` "
        ." AND lk.`itemtype` = '".$itemtype."'";

      $query.= " ORDER BY `glpi_plugin_archisw_swcomponents`.`name` ";

//file_put_contents("adebug.log","entering swcomponent_item.class - showForItem\n$query\n",FILE_APPEND);
      $result = $DB->query($query);
      $number = $DB->numrows($result);
      $i      = 0;

      $swcomponents      = array();
      $swcomponent       = new PluginArchiswSwcomponent();
      $used          = array();
      if ($numrows = $DB->numrows($result)) {
         while ($data = $DB->fetch_assoc($result)) {
            $swcomponents[$data['assocID']] = $data;
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

            echo "</td><td class='center' width='20%'>";
            echo "<input type='submit' name='additem' value=\"".
                     _sx('button', 'Associate a swcomponent', 'archisw')."\" class='submit'>";
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
      echo "<th>".__('Component Owner')."</th>";
      echo "<th>".__('Type')."</th>";
      echo "<th>".__('Status')."</th>";
      echo "<th>".__('Role')."</th>";
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
            $assocID             = $data["assocID"];

            echo "<tr class='tab_bg_1".($data["is_deleted"]?"_2":"")."'>";
            if ($canedit && ($withtemplate < 2)) {
               echo "<td width='10'>";
               Html::showMassiveActionCheckBox(__CLASS__, $data["assocID"]);
               echo "</td>";
            }
            echo "<td class='center'>$link</td>";
            if (Session::isMultiEntitiesMode()) {
               echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities", $data['entities_id']).
                    "</td>";
            }
			echo "<td class='center'>".Dropdown::getDropdownName("glpi_groups",$data["groups_id"])."</td>";

			if ($itemtype != 'Project') {
				echo "<td>".Dropdown::getDropdownName("glpi_plugin_archisw_swcomponenttypes",$data["plugin_archisw_swcomponenttypes_id"])."</td>";
			} else {
				echo "<td>".$data["funcarea_name"]."</td>";
			}
			echo "<td>".Dropdown::getDropdownName("glpi_plugin_archisw_swcomponentstates",$data["plugin_archisw_swcomponentstates_id"])."</td>";
			if ($itemtype != 'Project') {
				echo "<td>".Dropdown::getDropdownName("glpi_plugin_archisw_swcomponents_itemroles",$data["items_itemroles_id"])."</td>";
				echo "<td class='center'>".(isset($data["items_comment"])? "".$data["items_comment"]."" :"-")."</td>";
			}
//            echo "<td>".Dropdown::getDropdownName("glpi_plugin_archisw_servertypes",$data["plugin_archisw_servertypes_id"])."</td>";
//            echo "<td>".Dropdown::getDropdownName("glpi_plugin_archisw_swcomponenttypes",$data["plugin_archisw_swcomponenttypes_id"])."</td>";
//            echo "<td>".Dropdown::getDropdownName("glpi_manufacturers",$data["manufacturers_id"])."</td>";
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