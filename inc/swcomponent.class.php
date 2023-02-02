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

   public 	 $dohistory  = true;
   static 	 $rightname  = "plugin_archisw";
   protected $usenotepad = true;
   
   static $types = ['Computer', 'Project', 'ProjectTask', 'User', 'Software', 'Group', 'Entity', 'Contract', 'Appliance'];

   /**
    * @since version 0.84
   **/
   function pre_deleteItem() {
      global $GLPI_CACHE;

      // Security do not delete root entity
      if ($this->input['id'] == 0) {
         return false;
      }

      //Cleaning sons calls getAncestorsOf and thus... Re-create cache. Call it before clean.
      $this->cleanParentsSons();
      $ckey = 'ancestors_cache_' . $this->getTable() . '_' . $this->getID();
      $GLPI_CACHE->delete($ckey);
      
      return true;
   }

   static function getTypeName($nb=0) {

      return _n('Apps Structure', 'Apps Structures', $nb, 'archisw');
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
	   switch ($item->getType()) {
			case 'Supplier' :
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

      $dbu = new DbUtils();
      return $dbu->countElementsInTable('glpi_plugin_archisw_swcomponents',
                                  ['suppliers_id' => $item->getID()]);
   }

   //clean if swcomponent are deleted
   function cleanDBonPurge() {

//      $temp = new PluginArchiswSwcomponent_Item();
//      $temp->deleteByCriteria(['plugin_archisw_swcomponents_id' => $this->fields['id']));
   }

   // search fields from GLPI 9.3 on
   function rawSearchOptions() {
      global $DB, $CFG_GLPI;

      $tab = [];
      if (version_compare(GLPI_VERSION,'9.2','le')) return $tab;

      $tab[] = [
         'id'   => 'common',
         'name' => self::getTypeName(2)
      ];

      $tab[] = [
         'id'            => '1',
         'table'         => $this->getTable(),
         'field'         => 'name',
         'name'          => __('Name'),
         'datatype'      => 'itemlink',
         'itemlink_type' => $this->getType()
      ];

      $tab[] = [
         'id'            => '900',
         'table'         => $this->getTable(),
         'field'         => 'date_mod',
         'massiveaction' => false,
         'name'          => __('Last update'),
         'datatype'      => 'datetime'
      ];

/*      $tab[] = [
         'id'       => '2',
         'table'    => 'glpi_plugin_archisw_swcomponenttypes',
         'field'    => 'name',
         'name'     => PluginArchiswSwcomponentType::getTypeName(1),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'       => '3',
         'table'    => $this->getTable(),
         'field'    => 'level',
         'name'     => __('Level','archisw'),
         'datatype' => 'text'
      ];

      $tab[] = [
         'id'       => '4',
         'table'    => 'glpi_plugin_archisw_swcomponentstates',
         'field'    => 'name',
         'name'     => PluginArchiswSwcomponentState::getTypeName(1),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'       => '5',
         'table'    => $this->getTable(),
         'field'    => 'description',
         'name'     => __('Description'),
         'datatype' => 'text'
      ];

      $tab[] = [
         'id'       => '6',
         'table'    => 'glpi_plugin_archisw_swcomponenttechnics',
         'field'    => 'name',
         'name'     => PluginArchiswSwcomponentTechnic::getTypeName(1),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'       => '7',
         'table'    => 'glpi_plugin_archisw_swcomponentdbs',
         'field'    => 'name',
         'name'     => PluginArchiswSwcomponentDb::getTypeName(1),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'       => '8',
         'table'    => 'glpi_locations',
         'field'    => 'completename',
         'name'     => Location::getTypeName(1),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'       => '9',
         'table'    => 'glpi_suppliers',
         'field'    => 'name',
         'name'     => Supplier::getTypeName(1),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'        => '10',
         'table'     => 'glpi_manufacturers',
         'field'     => 'name',
         'name'      => Manufacturer::getTypeName(1),
         'datatype'  => 'dropdown'
      ];

      $tab[] = [
         'id'        => '11',
         'table'     => 'glpi_users',
         'field'     => 'name',
         'linkfield' => 'users_id',
         'name'      => __('Component Maintainer','archisw'),
         'datatype'  => 'dropdown',
         'right'     => 'interface'
      ];

      $tab[] = [
         'id'        => '12',
         'table'     => 'glpi_groups',
         'field'     => 'name',
         'linkfield' => 'groups_id',
         'name'      => __('Component Owner','archisw'),
         'condition' => ['is_assign' => 1],
         'datatype'  => 'dropdown'
      ];

      $tab[] = [
         'id'            => '15',
         'table'         => $this->getTable(),
         'field'         => 'is_helpdesk_visible',
         'name'          => __('Associable to a ticket'),
         'datatype'      => 'bool'
      ];

      $tab[] = [
         'id'            => '16',
         'table'         => $this->getTable(),
         'field'         => 'date_mod',
         'massiveaction' => false,
         'name'          => __('Last update'),
         'datatype'      => 'datetime'
      ];

      $tab[] = [
         'id'       => '17',
         'table'    => 'glpi_plugin_archisw_standards',
         'field'    => 'name',
         'name'     => PluginArchiswStandard::getTypeName(1),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'       => '20',
         'table'    => 'glpi_plugin_archisw_swcomponentinstances',
         'field'    => 'name',
         'name'     => PluginArchiswSwcomponentInstance::getTypeName(1),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'       => '21',
         'table'    => 'glpi_plugin_archisw_swcomponenttargets',
         'field'    => 'name',
         'name'     => PluginArchiswSwcomponentTarget::getTypeName(1),
         'datatype' => 'dropdown'
      ];
*/

      $linktable = [];
      $tablequery = "SELECT * FROM `glpi_plugin_archisw_configlinks`";
      $tableresult = $DB->query($tablequery);
      while ($tabledata = $DB->fetchAssoc($tableresult)) {
         $linktable[$tabledata['id']]['name'] = $tabledata['name'];
         $linktable[$tabledata['id']]['has_dropdown'] = $tabledata['has_dropdown'];
         $linktable[$tabledata['id']]['is_entity_limited'] = $tabledata['is_entity_limited'];
      }

      $datatypetable = [];
      $datatypequery = "SELECT * FROM `glpi_plugin_archisw_configdatatypes`";
      $datatyperesult = $DB->query($datatypequery);
      while ($datatypedata = $DB->fetchAssoc($datatyperesult)) {
         $datatypetable[$datatypedata['id']]['name'] = $datatypedata['name'];
      }

      $fieldquery = "SELECT * 
                FROM `glpi_plugin_archisw_configs` 
                WHERE `is_deleted` = 0 
                ORDER BY `description`";
      $fieldresult = $DB->query($fieldquery);
      $rowcount = $DB->numrows($fieldresult);
      $tabid = 1;
      $tabtable = $this->getTable();
      while ($fielddata = $DB->fetchAssoc($fieldresult)) {
         $tabid++;
         $datatypeid = $fielddata['plugin_archisw_configdatatypes_id'];
         switch($datatypeid) {
            case 1: //Text
            case 2: //Boolean
            case 3: //Date
            case 4: //Date and time
            case 5: //Number
            case 8: //Textarea
               $tab[] = [
                  'id'       => $tabid,
                  'table'    => $tabtable,
                  'field'    => $fielddata['name'],
                  'name'     => __($fielddata['description'],'archisw'),
                  'datatype' => $datatypetable[$datatypeid]['name'],
                  'massiveaction' => $fielddata['massiveaction'],
                  'nosearch' => $fielddata['nosearch']
               ];
               break;
            case 6: //Dropdown
            case 9: //TreeDropdown
               $linktableid = $fielddata['plugin_archisw_configlinks_id'];
               $itemtype = $linktable[$linktableid]['name'];
               $tablename = $this->getTable($itemtype);
               $tab[] = [
                  'id'       => $tabid,
                  'table'    => $tablename,
                  'field'    => 'name',
                  'name'     => __($fielddata['description'],'archisw'),
                  'datatype' => $datatypetable[$datatypeid]['name']
               ];
               break;
            case 7: //Itemlink
               break;
         }
      }

      $tab[] = [
         'id'            => '71',
         'table'         => 'glpi_plugin_archisw_swcomponents_items',
         'field'         => 'items_id',
         'nosearch'      => true,
         'massiveaction' => false,
         'name'          => _n('Associated item', 'Associated items', 2),
         'forcegroupby'  => true,
         'joinparams'    => [
            'jointype' => 'child'
         ]
      ];

      $tab[] = [
         'id'            => '72',
         'table'         => $this->getTable(),
         'field'         => 'id',
         'name'          => __('ID'),
         'datatype'      => 'number'
      ];

      $tab[] = [
         'id'       => '80',
         'table'    => $this->getTable(),
         'field'    => 'completename',
         'name'     => __('Apps Structure','archisw'),
         'datatype' => 'dropdown'
      ];

      $tab[] = [
         'id'    => '81',
         'table' => 'glpi_entities',
         'field' => 'entities_id',
         'name'  => __('Entity') . "-" . __('ID')
      ];

      $tab[] = [
         'id'                 => '100',
         'table'        	  => 'glpi_projects',
         'field'              => 'name',
         'name'               => Project::getTypeName(2)." - ".__('Name'),
         'linkfield'		  => 'items_id',
         'datatype'           => 'itemlink',
         'massiveaction'      => false,
         'forcegroupby'       => true,
         'itemlink_type'	  => Project::getType(),
         'joinparams'    	  => [
								'beforejoin'=> ['table'      => 'glpi_plugin_archisw_swcomponents_items',
												'joinparams' => ['jointype' => 'itemtype_item',
																'linkfield'		  => 'plugin_archisw_swcomponents_id'
																]
												]
								]
      ];

      $tab[] = [
         'id'                 => '101',
         'table'        	  => 'glpi_projecttasks',
         'field'              => 'name',
         'name'               => ProjectTask::getTypeName(2)." - ".__('Name'),
         'linkfield'		  => 'items_id',
         'datatype'           => 'itemlink',
         'massiveaction'      => false,
         'forcegroupby'       => true,
         'itemlink_type'	  => ProjectTask::getType(),
         'joinparams'    	  => [
								'beforejoin'=> ['table'      => 'glpi_plugin_archisw_swcomponents_items',
												'joinparams' => ['jointype' => 'itemtype_item',
																'linkfield'		  => 'plugin_archisw_swcomponents_id'
																]
												]
								]
      ];
      return $tab;
   }

   //define header form
   function defineTabs($options=[]) {

      $ong = [];
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginArchiswSwcomponent', $ong, $options);
      $this->addStandardTab('PluginArchiswSwcomponent_Item', $ong, $options);
      $this->addImpactTab($ong, $options);
      $this->addStandardTab('Ticket', $ong, $options);
      $this->addStandardTab('KnowbaseItem_Item', $ong, $options);
      $this->addStandardTab('Item_Problem', $ong, $options);
      $this->addStandardTab('Change_Item', $ong, $options);
      $this->addStandardTab('Document_Item', $ong, $options);
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
   function showForm ($ID, $options=[]) {

      global $DB, $CFG_GLPI;
   
      // Because a lot of informations, we use 3 (6) columns
      // Make <table> aware of it
      $options['colspan']=4;

      $this->initForm($ID, $options);
      $this->showFormHeader($options);
      
		// Line: 1
		echo "<tr class='tab_bg_1'>";
//			echo "<th rowspan=3></th>";

			// Name of SwComponent
			echo "<td>".__('Name')."</td>";
			echo "<td>";
            echo Html::input('name',['value' => $this->fields['name'], 'id' => "name"]);
			echo "</td>";

            // Parent of swcomponent
  			echo "<td>".__('As child of','archisw').": </td>";
            echo "<td>";
            Dropdown::show('PluginArchiswSwcomponent', ['value' => $this->fields["plugin_archisw_swcomponents_id"]]);
            echo "</td>";

            /*			// Version
			echo "<td>".__('Version', 'archisw')."</td>";
			echo "<td>";
            echo Html::input('version',['value' => $this->fields['version'], 'id' => "version", 'size' => 4]);
			echo "</td>";

	      // Use startdate of the swcomponent
            echo "<td>".__('In use since year','archisw')."</td>";
            echo "<td>";
            echo Html::input('startyear',['value' => $this->fields['startyear'], 'id' => "startyear", 'size' => 4]);
            echo "</td>";
      echo "</tr>";

		// Line: 2
      echo "<tr class='tab_bg_1'>";
	      //completename of swcomponent
  			echo "<td>".__('As child of','archisw').": </td>";
            echo "<td>";
            Dropdown::show('PluginArchiswSwcomponent', ['value' => $this->fields["plugin_archisw_swcomponents_id"]]);
            echo "</td>";

	      //level of swcomponent
            echo "<td>".__('Level','archisw').": </td>";
            echo "<td>";
            echo Html::input('level',['value' => $this->fields['level'], 'id' => "level", 'size' => 2, 'readonly' => true]);
            echo "</td>";

	      //shortname of swcomponent
            echo "<td>".__('Short code','archisw')."</td>";
            echo "<td>";
            echo Html::input('shortname',['value' => $this->fields['shortname'], 'id' => "shortname", 'size' => 5]);
            echo "</td>";
      echo "</tr>";

		// Line: 3
		echo "<tr class='tab_bg_1'>";
	      echo "<td>".__('Description').":	</td>";
      	echo "<td class='top left' colspan='5'><textarea cols='100' rows='3' name='description' >".$this->fields["description"]."</textarea>";
      echo "</tr>";

      // Accordion separator
      echo "<tr class='badge accordion-header'><td><button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='.maintbl'>Main characteristics</button></td></tr>";

		// Line: 4
      echo "<tr class='tab_bg_1 maintbl accordion-collapse collapse show'>";
	      //status of swcomponent
	      echo "<td>".__('Status')."</td>";
	      echo "<td>";
	      Dropdown::show('PluginArchiswSwcomponentState', ['value' => $this->fields["plugin_archisw_swcomponentstates_id"]]);
	      echo "</td>";

	      //status date of swcomponent
	      echo "<td>".__('Status Startdate','archisw')."</td>";
	      echo "<td>";
	      Html::showDateField("statedate", ['value' => empty($this->fields["statedate"])?date("Y-m-d"):$this->fields["statedate"], 'required' => true]);
	      echo "</td>";

	      //status of swcomponent
	      echo "<td>".__('Standardization Status', 'archisw')."</td>";
	      echo "<td>";
	      Dropdown::show('PluginArchiswStandard', ['value' => $this->fields["plugin_archisw_standards_id"]]);
	      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1 maintbl accordion-collapse collapse show'>";
	      // Type of swcomponent
	      echo "<td>".__('Type')."</td>";
			echo "<td>";
//      Dropdown::show('PluginArchiswSwcomponentType', ['value' => $this->fields["plugin_archisw_swcomponenttypes_id"],'entity' => $this->fields["entities_id"]]);
	      Dropdown::show('PluginArchiswSwcomponentType', ['value' => $this->fields["plugin_archisw_swcomponenttypes_id"]]);
	      echo "</td>";

	      // Language of swcomponent
	      echo "<td >".__('Development Language','archisw')."</td>";
	      echo "<td>";
	      Dropdown::show('PluginArchiswSwcomponentTechnic', ['value' => $this->fields["plugin_archisw_swcomponenttechnics_id"]]);
	      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1 maintbl accordion-collapse collapse show'>";
	      //instances of swcomponent
      echo "<td>".__('Instances','archisw')."</td>";
	      echo "<td>";
	      Dropdown::show('PluginArchiswSwcomponentInstance', ['value' => $this->fields["plugin_archisw_swcomponentinstances_id"]]);
	      echo "</td>";
	      //db
	      echo "<td>".__('Databases','archisw')."</td><td>";
	      Dropdown::show('PluginArchiswSwcomponentDb', ['value' => $this->fields["plugin_archisw_swcomponentdbs_id"]]);
	      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1 maintbl accordion-collapse collapse show'>";
	      //target
	      echo "<td>".__('Targets','archisw')."</td>";
		   echo "<td>";
	      Dropdown::show('PluginArchiswSwcomponentTarget', ['value' => $this->fields["plugin_archisw_swcomponenttargets_id"]]);
	      echo "</td>";
	      //#users
	      echo "<td>".__('# users','archisw')."</td>";
	      echo "<td>";
	      Dropdown::show('PluginArchiswSwcomponentUser', ['value' => $this->fields["plugin_archisw_swcomponentusers_id"]]);
	      echo "</td>";
	      echo "<td>".__('License metric','archisw')."</td>";
	      echo "<td>";
	      Dropdown::show('PluginArchiswSwcomponentLicense', ['value' => $this->fields["plugin_archisw_swcomponentlicenses_id"]]);
	      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1 maintbl accordion-collapse collapse show'>";
	      //groups
	      echo "<td>".__('Component Owner','archisw')."</td><td>";
	      Group::dropdown(['name'      => 'groups_id', 
	                        'value'     => $this->fields['groups_id'], 
	                        'entity'    => $this->fields['entities_id'], 
	                        'condition' => ['is_assign' => 1]
	                        ]);
	      echo "</td>";

	      //supplier of swcomponent
	      echo "<td>".__('Supplier','archisw')."</td>";
	      echo "<td>";
	      Dropdown::show('Supplier', ['name' => "suppliers_id", 'value' => $this->fields["suppliers_id"],'entity' => $this->fields["entities_id"]]);
	      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1 maintbl accordion-collapse collapse show'>";
	      //users
	      echo "<td>".__('Component Maintainer','archisw')."</td><td>";
	      User::dropdown(['name' => "users_id", 'value' => $this->fields["users_id"], 'entity' => $this->fields["entities_id"], 'right' => 'interface']);
	      echo "</td>";

	      //manufacturer of swcomponent
	      echo "<td>".__('Editor','archisw')."</td>";
	      echo "<td>";
	      Dropdown::show('Manufacturer', ['value' => $this->fields["manufacturers_id"],'entity' => $this->fields["entities_id"]]);
	      echo "</td>";

	      //service level agreement
	      echo "<td>".__('Service level','archisw')."</td>";
	      echo "<td>";
	//      Dropdown::show('PluginArchiswSwcomponentType', ['value' => $this->fields["plugin_archisw_swcomponenttypes_id"],'entity' => $this->fields["entities_id"]]);
	      Dropdown::show('PluginArchiswSwcomponentSla', ['value' => $this->fields["plugin_archisw_swcomponentslas_id"]]);
	      echo "</td>";
      echo "</tr>";

      // Accordion separator
      echo "<tr class='badge accordion-header'><td><button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='.othertbl'>Locations</button></td></tr>";

      echo "<tr class='tab_bg_1 othertbl accordion-collapse collapse show'>";
	      //url of swcomponent
          echo "<td>";
	      echo Html::link(__('URL Production', 'archisw'), $this->fields["address"]);
          echo "<td>";
	      echo "<td colspan='4'>";
            echo Html::input('address',['value' => $this->fields['address'], 'id' => "address", 'width' => "100%"]);
		  echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1 othertbl accordion-collapse collapse show'>";
          echo "<td>";
	      echo Html::link(__('URL QA', 'archisw'), $this->fields["address_qa"]);
          echo "<td>";
			echo "<td colspan='4'>";
            echo Html::input('address_qa',['value' => $this->fields['address_qa'], 'id' => "address_qa", 'width' => "100%"]);
			echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1 othertbl accordion-collapse collapse show'>";
          echo "<td>";
	      echo Html::link(__('URL Health Check', 'archisw'), $this->fields["health_check"]);
          echo "<td>";
			echo "<td colspan='4'>";
            echo Html::input('health_check',['value' => $this->fields['health_check'], 'id' => "health_check", 'width' => "100%"]);
			echo "</td>";
      echo "</tr>";

		// News fields for version 2.2.0
      echo "<tr class='tab_bg_1 othertbl accordion-collapse collapse show'>";
          echo "<td>";
	      echo Html::link(__('Source Repository', 'archisw'), $this->fields["repo"]);
          echo "<td>";
			echo "<td colspan='4'>";
            echo Html::input('repo',['value' => $this->fields['repo'], 'id' => "repo", 'width' => "100%"]);
			echo "</td>";
      echo "</tr>";

		// Just a separator
      echo "<tr><td></td></tr>";

      echo "<tr class='tab_bg_1 othertbl accordion-collapse collapse show'>";
	      //location of swcomponent
	      echo "<td>".__('Location')."</td>";
	      echo "<td>";
	      Dropdown::show('Location', ['value' => $this->fields["locations_id"],'entity' => $this->fields["entities_id"]]);
	      echo "</td>";
	      echo "</td>";
	      //is_helpdesk_visible
	      echo "<td>" . __('Associable to a ticket')."</td><td>";
	      Dropdown::showYesNo('is_helpdesk_visible',$this->fields['is_helpdesk_visible']);
	      echo "</td>";
      echo "</tr>";
*/
      $linktable = [];
      $tablequery = "SELECT * FROM `glpi_plugin_archisw_configlinks`";
      $tableresult = $DB->query($tablequery);
      while ($tabledata = $DB->fetchAssoc($tableresult)) {
         $linktable[$tabledata['id']]['name'] = $tabledata['name'];
         $linktable[$tabledata['id']]['has_dropdown'] = $tabledata['has_dropdown'];
         $linktable[$tabledata['id']]['is_entity_limited'] = $tabledata['is_entity_limited'];
      }

      $fieldquery = "SELECT * 
                FROM `glpi_plugin_archisw_configs` 
                WHERE `is_deleted` = 0 AND `plugin_archisw_configfieldgroups_id` = 0 
                ORDER BY `row`, `plugin_archisw_confighaligns_id`";
      $fieldresult = $DB->query($fieldquery);
      $rowcount = $DB->numrows($fieldresult);
      if ($rowcount > 0) {
         $rownbr = '1';
         $halign = 5;
         $tonextrow = false;
         while ($fielddata = $DB->fetchAssoc($fieldresult)) {
            if ($fielddata['row'] != $rownbr) {
               if ($rownbr != '1') {
                  // If not the first row, end preceding table row
                  echo "</tr>";
               }
               // Set current rownbr
               $rownbr = $fielddata['row'];
               // Start new table row
               echo "<tr class='tab_bg_1'>";
               $halign = 1;
               $tonextrow = false;
            } else if ($tonextrow) {
               continue; // skip this field which is located on the same row (and should not)
            }
            
            //Display field
            switch($fielddata['plugin_archisw_confighaligns_id']) {
               case 1: // Full row
                  if ($halign == 1) {
                     $colspan = 5;
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = true;
                  }
                  break;
               case 2: // Left column
                  if ($halign == 1) {
                     $colspan = 1;
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = false;
                  }
                  break;
               case 3: // Left+Center columns
                  if ($halign == 1) {
                     $colspan = 3;
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = true;
                  }
                  break;
               case 4: // Center column
                  if ($halign <= 3) {
                     $colspan = 1;
                     while ($halign < 3) { // fill empty columns
                        echo "<td/>";
                        $halign++;
                     }
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = false;
                  }
                  break;
               case 5: // Center+Right columns
                  if ($halign <= 3) {
                     $colspan = 3;
                     while ($halign < 3) { // fill empty columns
                        echo "<td/>";
                        $halign++;
                     }
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = true;
                  }
                  break;
               case 6: // Right column
                  if ($halign <= 5) {
                     $colspan = 1;
                     while ($halign < 5) { // fill empty columns
                        echo "<td/>";
                        $halign++;
                     }
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = false;
                  }
                  break;
               }
            }
            // End last table row
            echo "</tr>";
      }

      // Generate accordions according to groups named in configfieldgroups

      $fgroupquery = "SELECT * 
                FROM `glpi_plugin_archisw_configfieldgroups` 
                ORDER BY `sortorder`";
      $fgroupresult = $DB->query($fgroupquery);

      while ($fgroupdata = $DB->fetchAssoc($fgroupresult)) {
         $fgroupid = $fgroupdata['id'];
         $fgroupname = $fgroupdata['name']."tbl"; //name of the grouping table
         $fgroupcomment = $fgroupdata['comment'];
         $fgroupexpanded = ($fgroupdata['is_visible'] != 0)?'collapse show':'collapse';

         $fieldquery = "SELECT * 
                FROM `glpi_plugin_archisw_configs` 
                WHERE `is_deleted` = 0 AND `plugin_archisw_configfieldgroups_id` = $fgroupid 
                ORDER BY `row`, `plugin_archisw_confighaligns_id`";
         $fieldresult = $DB->query($fieldquery);
         $rowcount = $DB->numrows($fieldresult);
         if ($rowcount > 0) {
            // Accordion separator
            echo "<tr class='badge accordion-header'><td><button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='.".$fgroupname."'>".$fgroupcomment."</button></td></tr>";

            $rownbr = '';
            while ($fielddata = $DB->fetchAssoc($fieldresult)) {
               if ($fielddata['row'] != $rownbr) {
                  if ($rownbr != '') {
                     // If not the first row, end preceding table row
                     echo "</tr>";
                  }
                  // Set current rownbr
                  $rownbr = $fielddata['row'];
                  // Start new table row
                  echo "<tr class='tab_bg_1 ".$fgroupname." accordion-collapse  ".$fgroupexpanded."'>";
                  $halign = 1;
                  $tonextrow = false;
               } else if ($tonextrow) {
                  continue; // skip this field which is located on the same row (and should not)
               }
            
               //Display field
               switch($fielddata['plugin_archisw_confighaligns_id']) {
               case 1: // Full row
                  if ($halign == 1) {
                     $colspan = 5;
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = true;
                  }
                  break;
               case 2: // Left column
                  if ($halign == 1) {
                     $colspan = 1;
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = false;
                  }
                  break;
               case 3: // Left+Center columns
                  if ($halign == 1) {
                     $colspan = 3;
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = true;
                  }
                  break;
               case 4: // Center column
                  if ($halign <= 3) {
                     $colspan = 1;
                     while ($halign < 3) { // fill empty columns
                        echo "<td/>";
                        $halign++;
                     }
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = false;
                  }
                  break;
               case 5: // Center+Right columns
                  if ($halign <= 3) {
                     $colspan = 3;
                     while ($halign < 3) { // fill empty columns
                        echo "<td/>";
                        $halign++;
                     }
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = true;
                  }
                  break;
               case 6: // Right column
                  if ($halign <= 5) {
                     $colspan = 1;
                     while ($halign < 5) { // fill empty columns
                        echo "<td/>";
                        $halign++;
                     }
                     $this->displayField($fielddata, $colspan, $linktable);
                     $halign += 1 + $colspan; // move halign to next column to write into
                     $tonextrow = false;
                  }
                  break;
               }
            }
            // End last table row
            echo "</tr>";
         }
      }

/*		// Last line
      echo "<tr class='tab_bg_1'>";
	      //comment about swcomponent
	      echo "<td>".__('Comment').":	</td>";
	      echo "<td class='top left' colspan='5'><textarea cols='100' rows='3' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</tr>";
*/
      $this->showFormButtons($options);

      return true;
   }

   function displayField($fielddata, $colspan = 1, $linktable=[]) {
      $fieldname = $fielddata['name'];
      $fielddescription = $fielddata['description'];
      $fieldreadonly = $fielddata['is_readonly']?'true':'false';
      $params = [];
      $params['value'] = $this->fields[$fieldname];
      if ($fielddata['is_readonly']) {
         $params['readonly'] = 'true';
      }
      switch($fielddata['plugin_archisw_configdatatypes_id']) {
         case 1: //Text
            echo "<td>".__($fielddescription, 'archisw')."</td>";
            echo "<td colspan='".$colspan."'>";
            $params['id'] = $fieldname;
            $params['width'] = '100%';
            echo Html::input($fieldname,$params);
            echo "</td>";
            break;
         case 2: //Boolean
            echo "<td>".__($fielddescription, 'archisw')."</td>";
            echo "<td colspan='".$colspan."'>";
            Dropdown::showYesNo($fieldname,$this->fields[$fieldname], -1);
            echo "</td>";
            break;
         case 3: //Date
            echo "<td>".__($fielddescription, 'archisw')."</td>";
            echo "<td colspan='".$colspan."'>";
            Html::showDateField($fieldname, ['value' => empty($this->fields[$fieldname])?date("Y-m-d"):$this->fields[$fieldname], 'readonly' => $fieldreadonly]);
            echo "</td>";
            break;
         case 4: //Date and time
            echo "<td>".__($fielddescription, 'archisw')."</td>";
            echo "<td colspan='".$colspan."'>";
            Html::showDateTimeField($fieldname, ['value' => empty($this->fields[$fieldname])?date("Y-m-d H:i:s"):$this->fields[$fieldname], 'readonly' => $fieldreadonly]);
            echo "</td>";
            break;
         case 5: //Number
            echo "<td>".__($fielddescription, 'archisw')."</td>";
            echo "<td colspan='".$colspan."'>";
            Dropdown::showNumber($fieldname, $params);
            echo "</td>";
            break;
         case 6: //Dropdown
         case 9: //Dropdown
            if ($linktable[$fielddata['plugin_archisw_configlinks_id']]['is_entity_limited']) {
               $params['entity'] = $this->fields["entities_id"];
            }
            echo "<td>".__($fielddescription, 'archisw')."</td>";
            echo "<td colspan='".$colspan."'>";
            if ($linktable[$fielddata['plugin_archisw_configlinks_id']]['has_dropdown']) {
               $linktable[$fielddata['plugin_archisw_configlinks_id']]['name']::dropdown($params);
            }
            else {
               Dropdown::show($linktable[$fielddata['plugin_archisw_configlinks_id']]['name'], $params);
            }
            echo "</td>";
            break;
         case 7: //Itemlink
            echo "<td>";
            echo Html::link(__($fielddescription, 'archisw'), $this->fields[$fieldname]);
            echo "</td>";
            echo "<td colspan='".$colspan."'>";
            $params['id'] = $fieldname;
            $params['width'] = '100%';
            echo Html::input($fieldname,$params);
            echo "</td>";
            break;
         case 8: //Textarea
            echo "<td>".__($fielddescription, 'archisw')."</td>";
            echo "<td colspan='".$colspan."'>";
            echo Html::textarea(['name' => $fieldname, 'value' => $this->fields[$fieldname], 'editor_id' => $fieldname, 
                                'enable_richtext' => true, 'display' => false, 'rows' => 3, 'readonly' => $fieldreadonly]);
            echo "</td>";
            break;      
      }
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
   static function dropdownSwcomponent($options=[], $itemtype='') {
      global $DB, $CFG_GLPI;


      $p['name']    = 'plugin_archisw_swcomponents_id';
      $p['entity']  = '';
      $p['used']    = [];
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

      $values = [0 => Dropdown::EMPTY_VALUE];

      while ($data = $DB->fetchAssoc($result)) {
         $values[$data['id']] = $data['name'];
      }
      $rand = mt_rand();
      $out  = Dropdown::showFromArray('_swcomponenttype', $values, [/*'width'   => '15%',*/
                                                                     'rand'    => $rand,
                                                                     'display' => false]);
      $field_id = Html::cleanId("dropdown__swcomponenttype$rand");

      $params   = ['swcomponenttype' => '__VALUE__',
                        'entity' => $p['entity'],
                        'rand'   => $rand,
                        'myname' => $p['name'],
                        'used'   => $p['used']];

      $out .= Ajax::updateItemOnSelectEvent($field_id,"show_".$p['name'].$rand,
                                            Plugin::getWebDir('archisw')."/ajax/dropdownTypeArchisw.php",
                                            $params, false);
      $out .= "<span id='show_".$p['name']."$rand'>";
      $out .= "</span>\n";

      $params['swcomponenttype'] = 0;
      $out .= Ajax::updateItem("show_".$p['name'].$rand,
                               Plugin::getWebDir('archisw')."/ajax/dropdownTypeArchisw.php",
                               $params, false);
      $query = "SELECT `id`,`name`
                FROM `glpi_plugin_archisw_swcomponents_itemroles`
                WHERE `itemtype` = '".(isset($_GET['_itemtype'])?$_GET['_itemtype']:$itemtype)."'" ;
      $result = $DB->query($query);

      $values = [0 => Dropdown::EMPTY_VALUE];

      while ($data = $DB->fetchAssoc($result)) {
         $values[$data['id']] = $data['name'];
      }
      $out .= Dropdown::showFromArray('plugin_archisw_swcomponents_itemroles_id', $values, [/*'width'   => '20%',*/
                                                                     'rand'    => $rand,
                                                                     'display' => false]);
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

      if ($withtemplate!=2) echo "<form method='post' action=\"".Plugin::getWebDir('archisw')."/front/swcomponent.form.php\">";

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
            echo "<td class='center'><a href='".Plugin::getWebDir('archisw')."/front/swcomponent.form.php?id=".$data["id"]."'>".$data["name"];
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
            self::dropdownSwcomponent([], array_keys($ma->getItems())[0]);
            echo "&nbsp;".
                 Html::submit(_x('button','Post'), ['name' => 'massiveaction']);
            return true;
            break;
         case "install" :
            Dropdown::showSelectItemFromItemtypes(['items_id_name' => 'item_item',
                                                        'itemtype_name' => 'typeitem',
                                                        'itemtypes'     => self::getTypes(true),
                                                        'checkright'
                                                                        => true,
                                                  ]);
            echo Html::submit(_x('button', 'Post'), ['name' => 'massiveaction']);
            return true;
            break;
         case "uninstall" :
            Dropdown::showSelectItemFromItemtypes(['items_id_name' => 'item_item',
                                                        'itemtype_name' => 'typeitem',
                                                        'itemtypes'     => self::getTypes(true),
                                                        'checkright'
                                                                        => true,
                                                  ]);
            echo Html::submit(_x('button', 'Post'), ['name' => 'massiveaction']);
            return true;
            break;
         case "duplicate" :
		    $options = [];
			$options['value'] = 1;
			$options['min'] = 1;
			$options['max'] = 20;
			$options['unit'] = "times";
            Dropdown::showNumber('repeat', $options);
            echo Html::submit(_x('button','Post'), ['name' => 'massiveaction']);
            return true;
            break;
         case "transfer" :
            Dropdown::show('Entity');
            echo Html::submit(_x('button','Post'), ['name' => 'massiveaction']);
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

      $swcomponent_item = new PluginArchiswSwcomponent_Item();
      
      switch ($ma->getAction()) {
         case "plugin_archisw_add_item":
            $input = $ma->getInput();
            foreach ($ids as $id) {
               $input = ['plugin_archisw_swcomponents_id' 					=> $input['plugin_archisw_swcomponents_id'],
                                 'items_id'      							=> $id,
                                 'itemtype'      							=> $item->getType(),
                                 'plugin_archisw_swcomponents_itemroles_id'	=> $input['plugin_archisw_swcomponents_itemroles_id'],
                                 'comment'      							=> $input['comment']];
               if ($swcomponent_item->can(-1,CREATE,$input)) {
                  if ($swcomponent_item->add($input)) {
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                  } else {
                     $ma->itemDone($item->getType(), $ids, MassiveAction::ACTION_KO);
                  }
               } else {
                  $ma->itemDone($item->getType(), $ids, MassiveAction::ACTION_NORIGHT);
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
                  $values = ['plugin_archisw_swcomponents_id' => $key,
                                 'items_id'      => $input["item_item"],
                                 'itemtype'      => $input['typeitem']];
                  if ($swcomponent_item->add($values)) {
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
                  if ($swcomponent_item->deleteItemBySwcomponentsAndItem($key,$input['item_item'],$input['typeitem'])) {
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
				  $success = [];
				  $failure = [];
                  $item->getFromDB($key);
				  $values = $item->fields;
				  $name = $values["name"];

                  unset($values["id"]);
                  unset($values["sons_cache"]);
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
