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

include ('../../../inc/includes.php');

if (!isset($_GET["id"])) $_GET["id"] = "";
if (!isset($_GET["withtemplate"])) $_GET["withtemplate"] = "";

$swcomponent=new PluginArchiswSwcomponent();
$swcomponent_item=new PluginArchiswSwcomponent_Item();

if (isset($_POST["add"])) {

   $swcomponent->check(-1, CREATE,$_POST);
	if (isset($_POST['plugin_archisw_swcomponents_id']) && $_POST['plugin_archisw_swcomponents_id'] != '0') {
		// copy parent's value to child
		$swcomponent->getFromDB($_POST['plugin_archisw_swcomponents_id']);
		foreach ($swcomponent->fields as $key => $value) {
			if ($key != 'id' && !isset($_POST[$key])) {
				$_POST[$key] = $value;
			}
		}
	}
   $newID=$swcomponent->add($_POST);
   if ($_SESSION['glpibackcreated']) {
      Html::redirect($swcomponent->getFormURL()."?id=".$newID);
   }
   Html::back();

} else if (isset($_POST["delete"])) {

   $swcomponent->check($_POST['id'], DELETE);
   $swcomponent->delete($_POST);
   $swcomponent->redirectToList();

} else if (isset($_POST["restore"])) {

   $swcomponent->check($_POST['id'], PURGE);
   $swcomponent->restore($_POST);
   $swcomponent->redirectToList();

} else if (isset($_POST["purge"])) {

   $swcomponent->check($_POST['id'], PURGE);
   $swcomponent->delete($_POST,1);
   $swcomponent->redirectToList();

} else if (isset($_POST["update"])) {

   $swcomponent->check($_POST['id'], UPDATE);
   $swcomponent->update($_POST);
   Html::back();

} else if (isset($_POST["additem"])) {

   if (!empty($_POST['itemtype'])&&$_POST['items_id']>0) {
      $swcomponent_item->check(-1, UPDATE, $_POST);
      $swcomponent_item->addItem($_POST);
   }
   Html::back();

} else if (isset($_POST["deleteitem"])) {

   foreach ($_POST["item"] as $key => $val) {
         $input = array('id' => $key);
         if ($val==1) {
            $swcomponent_item->check($key, UPDATE);
            $swcomponent_item->delete($input);
         }
      }
   Html::back();

} else if (isset($_POST["deletearchisw"])) {

   $input = array('id' => $_POST["id"]);
   $swcomponent_item->check($_POST["id"], UPDATE);
   $swcomponent_item->delete($input);
   Html::back();

} else {

   $swcomponent->checkGlobal(READ);

   $plugin = new Plugin();
   if ($plugin->isActivated("environment")) {
      Html::header(PluginArchiswSwcomponent::getTypeName(2),
                     '',"assets","pluginenvironmentdisplay","archisw");
   } else {
      Html::header(PluginArchiswSwcomponent::getTypeName(2), '', "assets",
                   "pluginarchiswmenu");
   }
   $swcomponent->display($_GET);

   Html::footer();
}

?>