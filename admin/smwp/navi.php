<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* navi.php                                        *
*                                                 *
* Copyright (C) 2008-20XX By Andreas Dahl         *
*                                                 *
* www.forum.sourceserver.info                     *
* www.hsfighter.net                               *
*                                                 *
**************************************************/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

For support and installation notes visit:
EN: http://forums.alliedmods.net/showthread.php?t=60174
DE: http://www.sourceserver.info/viewtopic.php?f=48&t=451
*/



  $tpl->set_var(array(
     "navi_news"              => $viewtext['Menu'][1],
     "navi_clients"           => $viewtext['Menu'][2],
     "navi_user"              => $viewtext['Menu'][3],
     "navi_logout"            => $viewtext['Menu'][4],
     "navi_inteface"          => $viewtext['Menu'][5],
     "navi_clientflags"       => $viewtext['Menu'][6],
     "navi_sm"                => $viewtext['Menu'][7],
     "navi_groups"            => $viewtext['Menu'][8],
     "navi_groupflags"        => $viewtext['Menu'][9],
     "navi_overrides"         => $viewtext['Menu'][10],
     "navi_groupoverrides"    => $viewtext['Menu'][11],
     "navi_profil"            => $viewtext['Menu'][12],
     "navi_export"            => $viewtext['Menu'][13],
     "navi_credits"           => $viewtext['Menu'][14],
     "navi_settings"          => $viewtext['Menu'][15],
     "navi_management"        => $viewtext['Menu'][15],
     "navi_plugins"           => $viewtext['Menu'][16],
     "navi_server"            => $viewtext['Menu'][17],
     "navi_mods"              => $viewtext['Menu'][19],
	 "navi_home"              => $viewtext['Menu'][20],
     "navi_profil_checkid"    => $usercheck['id']
  ));
  
  

 $tpl->set_block("left_navi", "naviservergroupsblock", "naviservergroupsblock_handle");

 if ($settings['servergroups_enabled'] == 1){
   $tpl->set_var(array("navi_servergroups" => $viewtext['Menu'][18]));
   $tpl->parse("naviservergroupsblock_handle", "naviservergroupsblock", true);
 }
 
  $tpl->set_block("left_navi", "pluginnaviblock", "pluginnaviblock_handle");
  $tpl->set_block("left_navi", "hrlineblock", "hrlineblock_handle");
  
  $sql = "SELECT * FROM ".$table."_plugins WHERE Showplug = 1 ORDER BY id ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

	if ($row['MMS'] <> NULL){
        $sectionlink = 'servermanagement';      
      }else{
        $sectionlink = 'plugins';      
      }
	
      $tpl->set_var(array(
        "navi_plugin_name"              => $row['Name'],
        "navi_plugin_systemname"        => $row['Systemname'],
	  "sectionlink"		 	    => $sectionlink
      ));

      $tpl->parse("pluginnaviblock_handle", "pluginnaviblock", true);
    }
    $tpl->parse("hrlineblock_handle", "hrlineblock", true);
  }
?>
