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

if (strpos($_SERVER['PHP_SELF'], "dropdownItemRole.php")) {
	$AJAX_INCLUDE = 1;
	if (!defined('GLPI_ROOT'))
		include_once ('../../../inc/includes.php');
	else
		include_once (GLPI_ROOT . '/inc/includes.php');
	header("Content-Type: text/html; charset=UTF-8");
	Html::header_nocache();
}

global $DB, $CFG_GLPI, $LANG;

Session::checkCentralAccess();

// Make a select box
if (isset($_POST["itemtype"])) {
	$out = "";
	$query = [
		'SELECT'=> ['id','name'],
		'FROM'	=> 'glpi_plugin_archisw_swcomponents_itemroles',
		'WHERE'	=> [
			'itemtype' => $_POST['itemtype']
		],
	];
	foreach ($DB->request($query) as $data) {
		$out .= "<option value='".$data['id']."'>".$data['name']."</option>";
	}
	echo $out;
}

?>
