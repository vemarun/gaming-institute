<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* settings.php                                    *
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

if ($usercheck['editinterfacesettings'] == "1"){ //-+-+-

$cat = (isset($_GET['cat'])) ? $_GET['cat']:'';

if ($cat == ""){ // ---- Cat ----

$tpl->set_file("inhalt", "templates/settings.tpl.htm");

$tpl->set_var(array(
   "menue_settings"                  => $viewtext['Menu'][15],
));

if ($action == ""){//------------

$tpl->set_var(array(
   "section"                                 			=> $section,
   "menue_settings_general"                  			=> $viewtext['Menu'][5],
   "menue_settings_servergroups"             			=> $viewtext['Menu'][18],
   "menue_settings_server"             	 	 			=> $viewtext['System'][89],
   "menue_settings_server_timeout_default_discription" 	=> $viewtext['System'][62],
   "menue_settings_sqladmins"                			=> $viewtext['Menu'][7],
   "apply_settings_button_text"              			=> $viewtext['System'][7],
   "menue_settings_default_language"         			=> $viewtext['System'][105],
   "menue_settings_show_users"               			=> $viewtext['Settings'][2],
   "menue_settings_servergroups_enabled"     			=> $viewtext['Menu'][18],
   "menue_servergroups_enabled_enabledtext"  			=> $viewtext['System'][51],
   "menue_servergroups_enabled_disabledtext" 			=> $viewtext['System'][52],
   "server_timeout_infotext"							=> $viewtext['Settings'][6],
   "show_users"                              			=> $settings['show_users'],
   "menue_settings_show_clients"             			=> $viewtext['Settings'][0],
   "show_clients"                            			=> $settings['show_clients'],
   "menue_settings_show_groups"              			=> $viewtext['Settings'][1],
   "show_groups"                             			=> $settings['show_groups'],
   "menue_settings_show_server"              			=> $viewtext['Settings'][3],
   "show_server"                             			=> $settings['show_server'],
   "menue_settings_show_servergroups"        			=> $viewtext['Settings'][4],
   "show_servergroups"                       			=> $settings['show_servergroups'],
   "menue_settings_server_timeout"			 			=> $viewtext['Settings'][5],
   "server_timeout"                          			=> $settings['server_timeout']
));

$tpl->set_block("inhalt", "defaultlanguagesblock", "defaultlanguagesblock_handle");

// --

//$languagearray = language();
include ("inc/languages.php");

foreach($languagearray['view'] as $key => $value) {

  $tpl->set_var(array(
    "scrolldefaultlanguagesvalue"  => $key,
    "defaultlanguages"             => $value,
  ));
  
  if ($key == $settings['default_language']){
    $tpl->set_var(array("scrolldefaultlanguagesselected" => "SELECTED"));
  }else{
    $tpl->set_var(array("scrolldefaultlanguagesselected" => ""));
  }

  $tpl->parse("defaultlanguagesblock_handle", "defaultlanguagesblock", true);
}

// --

if ($settings['servergroups_enabled'] == 1){

  $tpl->set_var(array(
    "servergroups_enabled_checkedenabled"  => "checked",
    "servergroups_enabled_checkeddisbaled" => ""
  ));
}else{

  $tpl->set_var(array(
    "servergroups_enabled_checkedenabled"  => "",
    "servergroups_enabled_checkeddisbaled" => "checked"
  ));
}

// --

}//-----------

if ($action == "applysettings"){

$settings['default_language'] = (isset($_POST['default_language'])) ? $_POST['default_language']:'';
$settings['show_users']     = (isset($_POST['show_users'])) ? $_POST['show_users']:'';
$settings['show_clients']     = (isset($_POST['show_clients'])) ? $_POST['show_clients']:'';
$settings['show_groups']      = (isset($_POST['show_groups'])) ? $_POST['show_groups']:'';
$settings['show_server']      = (isset($_POST['show_server'])) ? $_POST['show_server']:'';
$settings['show_servergroups']      = (isset($_POST['show_servergroups'])) ? $_POST['show_servergroups']:'';
$settings['servergroups_enabled']      = (isset($_POST['servergroups_enabled'])) ? $_POST['servergroups_enabled']:'';
$settings['server_timeout']      = (isset($_POST['server_timeout'])) ? $_POST['server_timeout']:'';

if(isset($settings['show_users'])) settype($settings['show_users'], "integer");
if(isset($settings['show_clients'])) settype($settings['show_clients'], "integer");
if(isset($settings['show_groups'])) settype($settings['show_groups'], "integer");
if(isset($settings['show_server'])) settype($settings['show_server'], "integer");
if(isset($settings['show_servergroups'])) settype($settings['show_servergroups'], "integer");
if(isset($settings['servergroups_enabled'])) settype($settings['servergroups_enabled'], "integer");
if(isset($settings['server_timeout'])) settype($settings['server_timeout'] , "integer");

if ((!$settings['servergroups_enabled'] == 1) and (!$settings['servergroups_enabled'] == 0)) $settings['show_servergroups'] = 0;
if ($settings['show_users'] == 0) $settings['show_users'] = 15;
if ($settings['show_clients'] == 0) $settings['show_clients'] = 15;
if ($settings['show_groups'] == 0) $settings['show_groups'] = 15;
if ($settings['show_server'] == 0) $settings['show_server'] = 15;
if ($settings['show_servergroups'] == 0) $settings['show_servergroups'] = 15;
if ($settings['server_timeout']  == 0) $settings['server_timeout']  = 2;


sqlexec("UPDATE ".$table."_settings SET Value='".$settings['default_language']."' WHERE Name = 'default_language'");
sqlexec("UPDATE ".$table."_settings SET Value='".$settings['show_users']."' WHERE Name = 'show_users'");
sqlexec("UPDATE ".$table."_settings SET Value='".$settings['show_clients']."' WHERE Name = 'show_clients'");
sqlexec("UPDATE ".$table."_settings SET Value='".$settings['show_groups']."' WHERE Name = 'show_groups'");
sqlexec("UPDATE ".$table."_settings SET Value='".$settings['show_server']."' WHERE Name = 'show_server'");
sqlexec("UPDATE ".$table."_settings SET Value='".$settings['show_servergroups']."' WHERE Name = 'show_servergroups'");
sqlexec("UPDATE ".$table."_settings SET Value='".$settings['servergroups_enabled']."' WHERE Name = 'servergroups_enabled'");
sqlexec("UPDATE ".$table."_settings SET Value='".$settings['server_timeout']."' WHERE Name = 'server_timeout'");

$tpl = Infobox($tpl, $viewtext['Menu'][5], $viewtext['System'][112], $viewtext['System'][3], 'index.php?section='.$section.'');

}


}else{ // ---- Cat ----

$tpl->set_file("inhalt", "templates/settings_".$cat.".tpl.htm");


} // ---- Cat ----




}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-

?>
