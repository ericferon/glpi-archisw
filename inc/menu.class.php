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
 
class PluginArchiswMenu extends CommonGLPI {
   static $rightname = 'plugin_archisw';

   static function getMenuName() {
      return _n('Apps structure', 'Apps structures', 2, 'archisw');
   }

   static function getMenuContent() {
      global $CFG_GLPI;

      $menu                                           = array();
      $menu['title']                                  = self::getMenuName();
      $menu['page']                                   = "/plugins/archisw/front/swcomponent.php";
      $menu['links']['search']                        = PluginArchiswSwcomponent::getSearchURL(false);
      if (PluginArchiswSwcomponent::canCreate()) {
         $menu['links']['add']                        = PluginArchiswSwcomponent::getFormURL(false);
      }

      return $menu;
   }

   static function removeRightsFromSession() {
      if (isset($_SESSION['glpimenu']['assets']['types']['PluginArchiswMenu'])) {
         unset($_SESSION['glpimenu']['assets']['types']['PluginArchiswMenu']); 
      }
      if (isset($_SESSION['glpimenu']['assets']['content']['PluginArchiswMenu'])) {
         unset($_SESSION['glpimenu']['assets']['content']['PluginArchiswMenu']); 
      }
   }
}