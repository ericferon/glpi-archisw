<?php
/*
 -------------------------------------------------------------------------
 Archisw plugin for GLPI
 Copyright (C) 2020-2022 by Eric Feron.
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
class PluginArchiswConfigswMenu extends CommonGLPI {
   static $rightname = 'plugin_archisw_configuration';

   static function getMenuName() {
      return _n('Apps structure configuration', 'Apps structures configuration', 2, 'archisw');
   }

   static function getMenuContent() {
      global $CFG_GLPI;

		$menu                                           = [];
		$menu['title']                                  = self::getMenuName();
		$menu['page']                                   = "/".Plugin::getWebDir('archisw', false)."/front/configsw.php";
		$menu['links']['search']                        = PluginArchiswConfigsw::getSearchURL(false);
		if (PluginArchiswConfigsw::canCreate()) {
			$menu['links']['add']                        = PluginArchiswConfigsw::getFormURL(false);
		}
		$menu['icon'] = self::getIcon();

		return $menu;
	}

	static function getIcon() {
		return "fas fa-cog";
	}

   static function removeRightsFromSession() {
      if (isset($_SESSION['glpimenu']['configsw']['types']['PluginArchiswConfigswMenu'])) {
         unset($_SESSION['glpimenu']['configsw']['types']['PluginArchiswConfigswMenu']); 
      }
      if (isset($_SESSION['glpimenu']['configsw']['content']['pluginarchiswconfigswmenu'])) {
         unset($_SESSION['glpimenu']['configsw']['content']['pluginarchiswconfigswmenu']); 
      }
   }
}
