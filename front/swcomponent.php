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

include ('../../../inc/includes.php');

$plugin = new Plugin();
if ($plugin->isActivated("environment")) {
   Html::header(PluginArchiswSwcomponent::getTypeName(2)
                  ,'',"assets","pluginenvironmentdisplay","archisw");
} else {
   Html::header(PluginArchiswSwcomponent::getTypeName(2), '', "assets","pluginarchiswmenu");
}
$swcomponent = new PluginArchiswSwcomponent();

if ($swcomponent->canView() || Session::haveRight("config", UPDATE)) {
   Search::show('PluginArchiswSwcomponent');
} else {
   Html::displayRightError();
}

Html::footer();

?>