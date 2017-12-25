<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* setup.php			                              *
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

error_reporting(E_ALL);

$newver = 'v2.1 Final'; // <-- DO NOT CHANGE THIS LINE!!!
$newbuild = '21100'; // <-- DO NOT CHANGE THIS LINE!!!
$newdb = '2'; // <-- DO NOT CHANGE THIS LINE!!!

$default_language = (isset($_POST['default_language'])) ? $_POST['default_language']:'';

$lang = (isset($_GET['lang'])) ? $_GET['lang']:'en';
$setlanguage = (isset($_GET['setlanguage'])) ? $_GET['setlanguage']:'';
$action = (isset($_GET['action'])) ? $_GET['action']:'';

$time = time();

if ($default_language != "") $setlanguage = $default_language;

if ($setlanguage != "") $lang = $setlanguage;

if ($lang != ""){
output_add_rewrite_var('lang', $lang);
$language = $lang;
}

include ("inc/template.inc.php");
include ("inc/config.php");
include ("inc/function.php");

$sqlresult = MySQLConnect($servername.':'.$dbport, $dbusername, $dbpassword, $dbname, '0');

if ($sqlresult === True){

    if (Table_Exists($table."_settings")=== True){

      $settings = array();
      $sql = "SELECT * FROM ".$table."_settings";
      $result = mysql_query($sql) OR die(mysql_error());
        if(mysql_num_rows($result)){
          while($row = mysql_fetch_assoc($result)){
            $settings[$row['Name']] = $row['Value'];
          }
       }

       if ($setlanguage == "") $language = $settings['default_language'];
    }

}

//include ("inc/language_".$language.".php");
$in = Languagefile("inc/language_".$language.".txt");
$viewtext = $in['Language'];

$tpl = new Template();
$tpl->set_file("header",       "templates/header.tpl.htm");
$tpl->set_file("footer",       "templates/footer.tpl.htm");
$tpl->set_file("blank",        "templates/blank.tpl.htm");
$tpl->set_file("inhalt",       "templates/404.tpl.htm");


   if ($sqlresult === True){
    if (Table_Exists($table."_users")=== True){
      $msghead = $viewtext['Setup'][1];
    }else{
      $msghead = $viewtext['Setup'][0];
    }
    }else{
       $msghead = $viewtext['Setup'][0];
    
    }
    $msghead = str_replace('%ver%', $newver, $msghead);

include ("inc/languages.php");
// $languagearray = language();
    
$tpl->set_file("blank",      "templates/blank.tpl.htm");
$tpl->set_var(array(
   "header"        => $tpl->parse("out", "header"),
   "footer"        => $tpl->parse("out", "footer"),
   "logo"          => '<IMG src="inc/pics/logo.jpg" alt="Logo" border=0>',
   "version"       => $newver.'&nbsp;Setup',
   "smwaversion"   => $newver,
   "language"      => '&setlanguage='.$language.'',
   "install_menue" => $msghead,
   "filename"      => $_SERVER['PHP_SELF'],
   "chartset"	  => $languagearray['chartset'][$language]
   ));
   


    $tpl->set_var(array(
   "404_header"       => $viewtext['System'][13],
   "404_text"         => $viewtext['System'][14],
   "404_backlink"     => $_SERVER['PHP_SELF'],
   "404_button_text"  => $viewtext['System'][3],
));

//==============================================================================

if ($sqlresult === True){


if ($action == ""){

  $tpl->set_file("inhalt",      "templates/install.tpl.htm");
  
  $tpl->set_var(array(
  "install_default_language_discription" => $viewtext['Setup'][11],
  "apply_install_button_text" => $viewtext['System'][7]
  ));

  $tpl->set_block("inhalt", "defaultlanguagesblock", "defaultlanguagesblock_handle");
  $tpl->set_block("inhalt", "defaultflaglanguagesblock", "defaultflaglanguagesblock_handle");



  foreach($languagearray['view'] as $key => $value) {
    $tpl->set_var(array(
      "defaultlanguagesvalue"        => $key,
      "defaultlanguages"             => $value,
    ));

    if ($key == $language){
      $tpl->set_var(array("defaultlanguagesselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("defaultlanguagesselected" => ""));
    }
    $tpl->parse("defaultlanguagesblock_handle", "defaultlanguagesblock", true);
    $tpl->parse("defaultflaglanguagesblock_handle", "defaultflaglanguagesblock", true);
  }
}

//==============================================================================


if ($action == "apply"){

$msg = '';

if ($default_language == ""){
  $tpl = Infobox($tpl, $viewtext['Setup'][0], $viewtext['System'][25], $viewtext['System'][4], $_SERVER['PHP_SELF']);
}else{

// Table Users >----------------------------------------------------------------

  $msg = $msg.str_replace('%table%', $table."_users", $viewtext['Setup'][3]);
  if (Table_Exists($table."_users")=== True){
    $msg = str_replace('%msg%', $viewtext['Setup'][8], $msg);
  }else{
    $msg = str_replace('%msg%', $viewtext['Setup'][9], $msg);

    sqlexec("CREATE TABLE ".$table."_users
    ( UserID                    int(11) PRIMARY KEY auto_increment,
      UserName                  varchar(30) NOT NULL default '',
      UserPass                  varchar(32) NOT NULL default '',
      UserMail                  varchar(150) NOT NULL default'',
      UserLanguage              varchar(8) NOT NULL default'',
      UserSession               varchar(32),
      UserOnline                int(30),
      UserEditUsers             int(1),
      UserSQLAdmins             int(1),
      UserEditPluginsettings    int(1),
      UserEditPermissions       int(1),
      UserEditInterfacesettings int(1),
      UserEditMods              int(1),
      UserServersettings        int(1),
      UserOwner                 int(1),
      UserDatum                 varchar(10) NOT NULL default '0',
      UNIQUE KEY UserName (UserName),
      UNIQUE KEY UserMail (UserMail))");
  
    sqlexec("INSERT INTO ".$table."_users SET
               UserName='admin',
               UserPass=MD5('123456'),
               UserMail='admin@admin.de',
               UserLanguage='default',
               UserEditUsers='1',
               UserSQLAdmins='1',
               UserEditPluginsettings='1',
               UserEditPermissions='1',
               UserEditInterfacesettings='1',
               UserServersettings='1',
               UserOwner='1',
               UserDatum = ".$time."");

    $msg = $msg.$viewtext['Setup'][5];
  }
  
  
// Table User Colums >----------------------------------------------------------
  
  $msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_users", $viewtext['Setup'][2]);
  
  $num = 0;
  
  if (CheckColumnExist($table."_users", 'UserLanguage') == False){
   sqlexec("ALTER TABLE ".$table."_users ADD UserLanguage VARCHAR(8) NOT NULL default'' AFTER UserMail");
   sqlexec("UPDATE ".$table."_users SET UserLanguage='default'");
   $num++;
  }
  
  if (CheckColumnExist($table."_users", 'UserSQLAdmins') == False){
   sqlexec("ALTER TABLE ".$table."_users ADD UserSQLAdmins int(1) AFTER UserEditUsers");
   sqlexec("UPDATE ".$table."_users SET UserSQLAdmins='1'");
   $num++;
  }
  
    if (CheckColumnExist($table."_users", 'UserEditPluginsettings') == False){
   sqlexec("ALTER TABLE ".$table."_users ADD UserEditPluginsettings int(1) AFTER UserSQLAdmins");
   sqlexec("UPDATE ".$table."_users SET UserEditPluginsettings='1'");
   $num++;
  }
  
    if (CheckColumnExist($table."_users", 'UserEditPermissions') == False){
   sqlexec("ALTER TABLE ".$table."_users ADD UserEditPermissions int(1) AFTER UserEditPluginsettings");
   sqlexec("UPDATE ".$table."_users SET UserEditPermissions='1'");
   $num++;
  }

    if (CheckColumnExist($table."_users", 'UserEditInterfacesettings') == False){
   sqlexec("ALTER TABLE ".$table."_users ADD UserEditInterfacesettings int(1) AFTER UserEditPermissions");
   sqlexec("UPDATE ".$table."_users SET UserEditInterfacesettings='1'");
   $num++;
  }

    if (CheckColumnExist($table."_users", 'UserEditMods') == False){
   sqlexec("ALTER TABLE ".$table."_users ADD UserEditMods int(1) AFTER UserEditInterfacesettings");
   sqlexec("UPDATE ".$table."_users SET UserEditMods='1'");
   $num++;
  }

    if (CheckColumnExist($table."_users", 'UserServersettings') == False){
   sqlexec("ALTER TABLE ".$table."_users ADD UserServersettings int(1) AFTER UserEditMods");
   sqlexec("UPDATE ".$table."_users SET UserServersettings='1'");
   $num++;
  }

  if ($num == 0){
    $msg = $msg.$viewtext['Setup'][10];
  }else{
    if ($num == 1){
      $msg = $msg.$viewtext['Setup'][12];
    }else{
      $msg = $msg.str_replace('%num%', $num, $viewtext['Setup'][13]);
    }
  }
  
  // Table Settings >-------------------------------------------------------------

  $msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_settings", $viewtext['Setup'][3]);
  if (Table_Exists($table."_settings")=== True){
    $msg = str_replace('%msg%', $viewtext['Setup'][8], $msg);
  }else{
    $msg = str_replace('%msg%', $viewtext['Setup'][9], $msg);

  sqlexec("CREATE TABLE ".$table."_settings
  ( ID           int(11) PRIMARY KEY auto_increment,
    Name         varchar(64) NOT NULL default '',
    Value        varchar(255) NOT NULL default '',
    UNIQUE KEY Name (Name))");

   $msg = $msg.$viewtext['Setup'][5];
  }





  
  
  
// Table Settings Entrys >------------------------------------------------------

$msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_settings", $viewtext['Setup'][4]);

$num = 0;

$entry  = Gettableentry($table."_settings", 'Name', 'version', 'Value');
if ($entry == False){
    sqlexec("INSERT INTO ".$table."_settings SET Name='version', Value='".$newver."'");
  $num++;
  $oldversion = $newver;
}else{
  $oldversion = $entry;
  if ($entry <> $newver){
    sqlexec("UPDATE ".$table."_settings SET Value='".$newver."' WHERE Name = 'version'");
    $num++;
  }
}


$entry  = Gettableentry($table."_settings", 'Name', 'db', 'Value');
if ($entry == False){
    sqlexec("INSERT INTO ".$table."_settings SET Name='db', Value='".$newdb."'");
  $num++;
  $olddb = $newdb;
}else{
  $olddb = $entry;
  if ($entry <> $newdb){
    sqlexec("UPDATE ".$table."_settings SET Value='".$newdb."' WHERE Name = 'db'");
    $num++;
  }
}

$entry  = Gettableentry($table."_settings", 'Name', 'build', 'Value');
if ($entry == False){

  $changeentry = Gettableentry($table."_settings", 'Name', 'mysqlbans_date_format', 'Value');
  if ($changeentry <> False){
    $changeentry = replace_date($changeentry);
    sqlexec("UPDATE ".$table."_settings SET Value='".$changeentry."' WHERE Name = 'mysqlbans_date_format'");
    $num++;
  }

  $changeentry = Gettableentry($table."_settings", 'Name', 'mysqlbans_date_format_playerview', 'Value');
  if ($changeentry <> False){
    $changeentry = replace_date($changeentry);
    sqlexec("UPDATE ".$table."_settings SET Value='".$changeentry."' WHERE Name = 'mysqlbans_date_format_playerview'");
    $num++;
  }

  $changeentry = Gettableentry($table."_settings", 'Name', 'maprate_date_format', 'Value');
  if ($changeentry <> False){
    $changeentry = replace_date($changeentry);
        sqlexec("UPDATE ".$table."_settings SET Value='".$changeentry."' WHERE Name = 'maprate_date_format'");
    $num++;
  }
  
  sqlexec("INSERT INTO ".$table."_settings SET Name='build', Value='".$newbuild."'");
  $oldbuild = $newbuild;
  $num++;
}else{
  $oldbuild = $entry;
  if ($entry <> $newbuild){
    sqlexec("UPDATE ".$table."_settings SET Value='".$newbuild."' WHERE Name = 'build'");
    $num++;
  }
}


 // Table Settings Columns >------------------------------------------------------
 

  $entry  = Gettableentry($table."_settings", 'Name', 'build', 'Value');
if ($entry <> False){
  if ('21011' > $oldbuild){
    sqlexec("ALTER TABLE smwa_settings CHANGE Name Name VARCHAR( 64 )");

  }
}
// Table Settings Entrys Continue>------------------------------------------------------

$entry  = Gettableentry($table."_settings", 'Name', 'default_language', 'Value');
if ($entry == False){
    sqlexec("INSERT INTO ".$table."_settings SET Name='default_language', Value='".$language."'");
  $num++;
}else{
  if ($entry <> $language){
    sqlexec("UPDATE ".$table."_settings SET Value='".$language."' WHERE Name = 'default_language'");
    $num++;
  }
}




if (Gettableentry($table."_settings", 'Name', 'show_users', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='show_users', Value='15'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'show_clients', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='show_clients', Value='15'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'show_groups', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='show_groups', Value='15'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'show_server', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='show_server', Value='15'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'show_servergroups', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='show_servergroups', Value='15'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'servergroups_enabled', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='servergroups_enabled', Value='0'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'server_timeout', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='server_timeout', Value='2'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'mysqlbans_show_bans', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='mysqlbans_show_bans', Value='15'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'mysqlbans_date_format', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='mysqlbans_date_format', Value='m-d-y'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'mysqlbans_date_format_playerview', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='mysqlbans_date_format_playerview', Value='m-d-Y H:i:s'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'maprate_show_maps', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='maprate_show_maps', Value='15'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'maprate_show_maps_detailed', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='maprate_show_maps_detailed', Value='4'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'maprate_show_votes', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='maprate_show_votes', Value='15'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'maprate_date_format', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='maprate_date_format', Value='m-d-Y H:i:s'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'maprate_startpage', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='maprate_startpage', Value='1'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_show_entrys', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_show_entrys', Value='80'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_date_format', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_date_format', Value='m-d-y H:i:s'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_hide_connections', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_hide_connections', Value='1'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_hide_playersonline', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_hide_playersonline', Value='1'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_hide_mapinfos', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_hide_mapinfos', Value='1'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_hide_console', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_hide_console', Value='0'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_filter', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_filter', Value='rank,statsme,votemap,nextmap,rtv,rpgmenu,guns,!guns,timeleft'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_color_default', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_color_default', Value='#000000'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_highlightingcolor_admin', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_highlightingcolor_admin', Value='#FF8000'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'chatlogex_highlightingcolor_ban', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='chatlogex_highlightingcolor_ban', Value='#FF0000'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'advertisements_show_advertisements', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='advertisements_show_advertisements', Value='30'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'advertisements_date_format', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='advertisements_date_format', Value='m-d-Y H:i:s'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'watchlist_show_entrys', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='watchlist_show_entrys', Value='15'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'basicplayertracker_show_entrys', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='basicplayertracker_show_entrys', Value='30'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'sqlfeedback_show_entrys', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='sqlfeedback_show_entrys', Value='30'");
$num++;
}
if (Gettableentry($table."_settings", 'Name', 'sqlfeedback_date_format', 'Value') == False){
sqlexec("INSERT INTO ".$table."_settings SET Name='sqlfeedback_date_format', Value='m-d-Y'");
$num++;
}




if ($num == 0){
  $msg = $msg.$viewtext['Setup'][10];
}else{
  if ($num == 1){
    $msg = $msg.$viewtext['Setup'][6];
  }else{
    $msg = $msg.str_replace('%num%', $num, $viewtext['Setup'][7]);
  }
}


  
  // Table Plugins >--------------------------------------------------------------

  $msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_plugins", $viewtext['Setup'][3]);
  if (Table_Exists($table."_plugins")=== True){
    $msg = str_replace('%msg%', $viewtext['Setup'][8], $msg);
  }else{
    $msg = str_replace('%msg%', $viewtext['Setup'][9], $msg);

sqlexec("CREATE TABLE ".$table."_plugins
( ID          int(11) PRIMARY KEY auto_increment,
  Name        varchar(64) NOT NULL default '',
  Systemname  varchar(32) NOT NULL default '',
  MMS         varchar(64) NULL,
  MMSColum    varchar(64) NULL,
  Version     varchar(16) default NULL,
  Tablename   varchar(32) NOT NULL default '',
  Author      varchar(64) default NULL,
  Support     varchar(255) NOT NULL default '',
  Authorlink  varchar(255) NOT NULL default '',
  Showplug    int(1) default '0',
  UNIQUE KEY Systemname (Systemname),
  UNIQUE KEY Name (Name))");

   $msg = $msg.$viewtext['Setup'][5];
  }


// Table Plugins Columns >-------------------------------------------------------

  $msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_plugins", $viewtext['Setup'][2]);

  $num = 0;

  if (CheckColumnExist($table."_plugins", 'MMS') == False){
   sqlexec("ALTER TABLE ".$table."_plugins ADD MMS varchar(64) NULL AFTER Systemname");
   $num++;
  }
  
  if (CheckColumnExist($table."_plugins", 'MMSColum') == False){
   sqlexec("ALTER TABLE ".$table."_plugins ADD MMSColum varchar(64) NULL AFTER MMS");
   $num++;
  }

  if ($num == 0){
    $msg = $msg.$viewtext['Setup'][10];
  }else{
    if ($num == 1){
      $msg = $msg.$viewtext['Setup'][12];
    }else{
      $msg = $msg.str_replace('%num%', $num, $viewtext['Setup'][13]);
    }
  }



// Table Plugins Entrys >-------------------------------------------------------

$msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_plugins", $viewtext['Setup'][4]);

$num = 0;

$entry  = Gettableentry($table."_plugins", 'Systemname', 'mysqlbans', 'Version');
if ($entry == False){

sqlexec("INSERT
        INTO
          ".$table."_plugins
        SET
          Name='MySQL Banning',
          Systemname='mysqlbans',
          Tablename = 'mysql_bans',
          Version = '4.8',
          Support='http://forums.alliedmods.net/showpost.php?p=962721&postcount=144',
          Author='MoggieX and -=|JFH|=-Naris',
          Authorlink='http://forums.alliedmods.net/member.php?u=29771'");

if (Table_Exists('mysql_bans')=== True) sqlexec("UPDATE ".$table."_plugins SET Showplug='1' WHERE Systemname = 'mysqlbans'");
  $num++;
}else{
  if ($entry <> "4.8"){
      sqlexec("UPDATE ".$table."_plugins SET Version='4.8' WHERE Systemname = 'mysqlbans'");
      sqlexec("UPDATE ".$table."_plugins SET Author='MoggieX and -=|JFH|=-Naris' WHERE Systemname = 'mysqlbans'");
      sqlexec("UPDATE ".$table."_plugins SET Support='http://forums.alliedmods.net/showpost.php?p=962721&postcount=144' WHERE Systemname = 'mysqlbans'");
    $num++;
  }
}

$entry  = Gettableentry($table."_plugins", 'Systemname', 'maprate', 'Version');
if ($entry == False){

sqlexec("INSERT
        INTO
          ".$table."_plugins
        SET
          Name='Map Rate',
          Systemname='maprate',
          Tablename = 'map_ratings',
          Version = '0.10',
          Support='http://forums.alliedmods.net/showthread.php?t=69593',
          Author='FLOOR_MASTER',
          Authorlink='http://forums.alliedmods.net/member.php?u=36026'");
          
          if (Table_Exists('map_ratings')=== True) sqlexec("UPDATE ".$table."_plugins SET Showplug='1' WHERE Systemname = 'mysqlbans'");
  $num++;
}else{
  if ($entry <> "0.10"){
      sqlexec("UPDATE ".$table."_plugins SET Version='0.10' WHERE Systemname = 'maprate'");
    $num++;
  }
}


$entry  = Gettableentry($table."_plugins", 'Systemname', 'serverconfigs', 'Version');
if ($entry == False){

sqlexec("INSERT
        INTO
          ".$table."_plugins
        SET
          Name='Server Configs',
          Systemname='serverconfigs',
          MMS = 'sm_myscal_server_no',
          MMSColum = 'Server_ID',
          Tablename = 'sm_servercfg',
          Version = '1.1',
          Support='http://forums.alliedmods.net/showthread.php?t=73119',
          Author='MoggieX',
          Authorlink='http://forums.alliedmods.net/member.php?u=29771'");


if (Table_Exists('sm_servercfg')=== True) sqlexec("UPDATE ".$table."_plugins SET Showplug='1' WHERE Systemname = 'serverconfigs'");
  $num++;
}else{
  if ($entry <> "1.1"){
      sqlexec("UPDATE ".$table."_plugins SET Version='1.1' WHERE Systemname = 'serverconfigs'");
    $num++;
  }
}

$entry  = Gettableentry($table."_plugins", 'Systemname', 'serverconfigs', 'MMSColum');
  if ($entry <> 'Server_ID'){
    sqlexec("UPDATE ".$table."_plugins SET MMSColum='Server_ID' WHERE Systemname = 'serverconfigs'");
    $num++;
  }

$entry  = Gettableentry($table."_plugins", 'Systemname', 'chatlogextended', 'Version');
if ($entry == False){

sqlexec("INSERT
        INTO
          ".$table."_plugins
        SET
          Name='ChatLog Extended',
          Systemname='chatlogextended',
          MMS = 'sm_chatlog_id',
          MMSColum = 'srvid',
          Tablename = 'chatlogs',
          Version = '1.3.7',
          Support='http://forums.alliedmods.net/showthread.php?p=817310',
          Author='muukis',
          Authorlink='http://forums.alliedmods.net/member.php?u=52082'");


if (Table_Exists('chatlogs')=== True) sqlexec("UPDATE ".$table."_plugins SET Showplug='1' WHERE Systemname = 'chatlogextended'");
  $num++;
}else{
  if ($entry <> "1.3.7"){
      sqlexec("UPDATE ".$table."_plugins SET Version='1.3.7' WHERE Systemname = 'chatlogextended'");
    $num++;
  }
}


$entry  = Gettableentry($table."_plugins", 'Systemname', 'advertisements', 'Version');
if ($entry == False){
sqlexec("INSERT
        INTO
          ".$table."_plugins
        SET
          Name='Advertisements',
          Systemname='advertisements',
          Tablename = 'adsmysql',
          Version = '1.2.100',
          Support='http://forums.alliedmods.net/showthread.php?p=754041',
          Author='strontiumdog',
          Authorlink='http://forums.alliedmods.net/member.php?u=24573'");

if (Table_Exists('adsmysql')=== True) sqlexec("UPDATE ".$table."_plugins SET Showplug='1' WHERE Systemname = 'advertisements'");
  $num++;
}else{
  if ($entry <> "1.2.100"){
      sqlexec("UPDATE ".$table."_plugins SET Version='1.2.100' WHERE Systemname = 'advertisements'");
    $num++;
  }
}


$entry  = Gettableentry($table."_plugins", 'Systemname', 'basicplayertracker', 'Version');
if ($entry == False){
sqlexec("INSERT
        INTO
          ".$table."_plugins
        SET
          Name='Basic Player Tracker',
          Systemname='basicplayertracker',
          Tablename = 'player_tracker',
          Version = '1.5',
          Support='http://forums.alliedmods.net/showthread.php?t=105155',
          Author='msleeper',
          Authorlink='http://forums.alliedmods.net/member.php?u=37521'");

if (Table_Exists('player_tracker')=== True) sqlexec("UPDATE ".$table."_plugins SET Showplug='1' WHERE Systemname = 'basicplayertracker'");
  $num++;
}else{
  if ($entry <> "1.5"){
      sqlexec("UPDATE ".$table."_plugins SET Version='1.5' WHERE Systemname = 'basicplayertracker'");
    $num++;
  }
}

$entry  = Gettableentry($table."_plugins", 'Systemname', 'sqlfeedback', 'Version');
if ($entry == False){
sqlexec("INSERT
        INTO
          ".$table."_plugins
        SET
          Name='SQL Feedback',
          Systemname='sqlfeedback',
          Tablename = 'feedback',
          Version = '1.2.0',
          Support='http://forums.alliedmods.net/showthread.php?t=66760',
          Author='bl4nk',
          Authorlink='http://forums.alliedmods.net/member.php?u=28850'");

if (Table_Exists('feedback')=== True) sqlexec("UPDATE ".$table."_plugins SET Showplug='1' WHERE Systemname = 'sqlfeedback'");
  $num++;
}else{
  if ($entry <> "1.2.0"){
      sqlexec("UPDATE ".$table."_plugins SET Version='1.2.0' WHERE Systemname = 'sqlfeedback'");
    $num++;
  }
}


/*$entry  = Gettableentry($table."_plugins", 'Systemname', 'adminlogging', 'Version');
if ($entry == False){

sqlexec("INSERT
        INTO
          ".$table."_plugins
        SET
          Name='Admin logging',
          Systemname='adminlogging',
          Tablename = 'sm_logging',
          Version = '2.00',
          Support='http://forums.alliedmods.net/showthread.php?p=527208',
          Author='MoggieX',
          Authorlink='http://forums.alliedmods.net/member.php?u=29771'");
          
          if (Table_Exists('sm_logging')=== True) sqlexec("UPDATE ".$table."_plugins SET Showplug='1' WHERE Systemname = 'adminlogging'");
  $num++;
}else{
  if ($entry <> "2.00"){
      sqlexec("UPDATE ".$table."_plugins SET Version='2.00' WHERE Systemname = 'adminlogging'");
    $num++;
  }
}*/


/*$sql = "INSERT
        INTO
          ".$table."_plugins
        SET
          Name='RPGx',
          Systemname='rpgx',
          Tablename = 'rpg',
          Version = '1.1.3',
          Support='http://forums.alliedmods.net/showthread.php?p=493552',
          Author='sumguy14',
          Authorlink='http://forums.alliedmods.net/member.php?u=16543'";*/


if ($num == 0){
  $msg = $msg.$viewtext['Setup'][10];
}else{
  if ($num == 1){
    $msg = $msg.$viewtext['Setup'][6];
  }else{
    $msg = $msg.str_replace('%num%', $num, $viewtext['Setup'][7]);
  }
}


// Table Server >---------------------------------------------------------------

  $msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_server", $viewtext['Setup'][3]);
  if (Table_Exists($table."_server")=== True){
    $msg = str_replace('%msg%', $viewtext['Setup'][8], $msg);
  }else{
    $msg = str_replace('%msg%', $viewtext['Setup'][9], $msg);

 sqlexec("CREATE TABLE ".$table."_server(
ID            int(11) PRIMARY KEY auto_increment,
Ip 		      varchar(21) NOT NULL default '',
Name_Short    varchar(24) NOT NULL default '',
rcon             varchar(255) NOT NULL default '',
ftp_ip           varchar(255) NOT NULL default '',
ftp_username	 varchar(255) NOT NULL default '',
ftp_pw           varchar(255) NOT NULL default '',
ftp_path         varchar(255) NOT NULL default '',
UNIQUE KEY (Ip)
)");

   $msg = $msg.$viewtext['Setup'][5];
  }
  

  
  // Table Server Columns >-------------------------------------------------------

  $msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_server", $viewtext['Setup'][2]);

  $num = 0;

  if (CheckColumnExist($table."_server", 'rcon') == False){
   sqlexec("ALTER TABLE ".$table."_server ADD rcon varchar(255) NULL AFTER Name_Short");
   $num++;
  }

  if (CheckColumnExist($table."_server", 'ftp_ip') == False){
   sqlexec("ALTER TABLE ".$table."_server ADD ftp_ip varchar(255) NULL AFTER rcon");
   $num++;
  }
  
  if (CheckColumnExist($table."_server", 'ftp_username') == False){
   sqlexec("ALTER TABLE ".$table."_server ADD ftp_username varchar(255) NULL AFTER ftp_ip");
   $num++;
  }
  
    if (CheckColumnExist($table."_server", 'ftp_pw') == False){
   sqlexec("ALTER TABLE ".$table."_server ADD ftp_pw varchar(255) NULL AFTER ftp_username");
   $num++;
  }
  
    if (CheckColumnExist($table."_server", 'ftp_path') == False){
   sqlexec("ALTER TABLE ".$table."_server ADD ftp_path varchar(255) NULL AFTER ftp_pw");
   $num++;
  }

  if ($num == 0){
    $msg = $msg.$viewtext['Setup'][10];
  }else{
    if ($num == 1){
      $msg = $msg.$viewtext['Setup'][12];
    }else{
      $msg = $msg.str_replace('%num%', $num, $viewtext['Setup'][13]);
    }
  }


// Table Server Plugins MSS >---------------------------------------------------

  $msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_server_plugins_mss", $viewtext['Setup'][3]);
  if (Table_Exists($table."_server_plugins_mss")=== True){
    if ($oldversion == "v1.9 Beta A"){
      sqlexec("ALTER TABLE ".$table."_server_plugins_mss DROP INDEX Plugin_Server_ID, ADD UNIQUE Interface_Server_ID (Interface_Server_ID,Plugin_Systemname)");
    }
    $msg = str_replace('%msg%', $viewtext['Setup'][8], $msg);
  }else{
    $msg = str_replace('%msg%', $viewtext['Setup'][9], $msg);

sqlexec("CREATE TABLE ".$table."_server_plugins_mss(
ID            	  int(11) PRIMARY KEY auto_increment,
Interface_Server_ID int(11),
Plugin_Server_ID    int(11),
Plugin_Systemname   varchar(255) NOT NULL default '',
UNIQUE KEY (Interface_Server_ID,Plugin_Systemname)
)");


   $msg = $msg.$viewtext['Setup'][5];
  }
  

// Table Mods >-----------------------------------------------------------------



  $msg = $msg.'<br>---<br>'.str_replace('%table%', $table."_mods", $viewtext['Setup'][3]);
  if (Table_Exists($table."_mods")=== True){
    $msg = str_replace('%msg%', $viewtext['Setup'][8], $msg);
  }else{
    $msg = str_replace('%msg%', $viewtext['Setup'][9], $msg);

 sqlexec("CREATE TABLE ".$table."_mods(
ID            int(11) PRIMARY KEY auto_increment,
name 		  varchar(255) NOT NULL default '',
folder        varchar(64) NOT NULL default '',
icon          varchar(128) NULL,
advert	      varchar(128) NULL,
UNIQUE KEY (icon)
)");

  sqlexec("INSERT INTO ".$table."_mods SET name='Counter-Strike: Source', folder='cstrike', icon='css.png', advert='cstrike'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Day of Defeat: Source', folder='dod', icon='dods.png',advert='dod'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Dystopia', folder='dystopia_v1', icon='dystopia.gif'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Fortress Forever', folder='FortressForever', icon='fortressforever.gif'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Garry''s Mod', folder='garrysmod', icon='garrysmod.png'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Half-Life 2 Capture the Flag', folder='hl2ctf', icon='hl2ctf.png'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Half-Life 2 Deathmatch', folder='hl2mp', icon='hl2dm.gif', advert='hl2mp'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Hidden: Source', folder='hidden', icon='hidden.png'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Insurgency: Source', folder='insurgency', icon='insurgency.png', advert='insurgency'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Left 4 Dead', folder='left4dead', icon='l4d.png', advert='left4dead'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Left 4 Dead 2', folder='left4dead2', icon='l4d2.png'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Perfect Dark: Source', folder='pdark', icon='pdark.png'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Pirates Vikings and Knights II', folder='pvkii', icon='pvkii.png', advert='pvkii'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Team Fortress 2', folder='tf', icon='tf2.png', advert='tf'");
  sqlexec("INSERT INTO ".$table."_mods SET name='The Ship', folder='ship', icon='the_ship.gif'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Zombie Panic', folder='zps', icon='zps.png', advert='zps'");
  sqlexec("INSERT INTO ".$table."_mods SET name='Age Of Chivalry', folder='ageofchivalry', icon='aoc.gif', advert='ageofchivalry'");

  $msg = $msg.$viewtext['Setup'][5];
  }

//====================[SQL-Admin-Plugin for multible server]====================


// Table Server >---------------------------------------------------------------

  $msg = $msg.'<br>---<br>'.str_replace('%table%', $smtable."_servers", $viewtext['Setup'][3]);
  if (Table_Exists($smtable."_servers")=== True){
    $msg = str_replace('%msg%', $viewtext['Setup'][8], $msg);
  }else{
    $msg = str_replace('%msg%', $viewtext['Setup'][9], $msg);
    if ((Table_Exists($smtable."_server")=== True) and ($oldbuild == "20011")){

      sqlexec("RENAME TABLE ".$smtable."_server TO ".$smtable."_servers");
      $msg = $msg.$viewtext['Setup'][14];

    }else{

      sqlexec("CREATE TABLE ".$smtable."_servers(
        id            int(10) PRIMARY KEY auto_increment,
        ip 		      varchar(15) NOT NULL default '',
        port          int(10),
        UNIQUE KEY (ip,port))");
      $msg = $msg.$viewtext['Setup'][5];
    }
  }


// Table Servers Groups >-------------------------------------------------------

  $msg = $msg.'<br>---<br>'.str_replace('%table%', $smtable."_servers_groups", $viewtext['Setup'][3]);
  if (Table_Exists($smtable."_servers_groups")=== True){
    $msg = str_replace('%msg%', $viewtext['Setup'][8], $msg);
  }else{
    $msg = str_replace('%msg%', $viewtext['Setup'][9], $msg);

 sqlexec("CREATE TABLE ".$smtable."_servers_groups(
server_id            int(10),
group_id 		         int(10),
UNIQUE KEY (server_id,group_id)
)");

   $msg = $msg.$viewtext['Setup'][5];
  }


// Table SQL bans >-------------------------------------------------------




  
  
  if (CheckColumnExist($table."_users", 'UserServersettings') == False){
   sqlexec("ALTER TABLE ".$table."_users ADD UserServersettings int(1) AFTER UserEditMods");
   sqlexec("UPDATE ".$table."_users SET UserServersettings='1'");
   $num++;
  }








$tpl = Infobox($tpl, $msghead, $msg, $viewtext['System'][26], 'index.php');

}


}

}else{

    $sqlresult = str_replace('%error%', $viewtext['System'][9].' '.$sqlresult, $viewtext['System'][56]);
    $tpl = Infobox($tpl, $msghead, $sqlresult, $viewtext['System'][64], 'setup.php');

}



$tpl->set_var(array(
"include"        => $tpl->parse("out", "inhalt")
));

$tpl->parse("out", "blank");

$tpl->p("out");




//----------------------------[Funktion]---------------------------------------


 function replace_date($changeentry){

        $changeentry = str_replace('%month%', 'm', $changeentry);
        $changeentry = str_replace('%day%', 'd', $changeentry);
        $changeentry = str_replace('%year%', 'y', $changeentry);
        $changeentry = str_replace('%hour%', 'H', $changeentry);
        $changeentry = str_replace('%min%', 'i', $changeentry);
        $changeentry = str_replace('%sec%', 's', $changeentry);

  return $changeentry;

 }
 
?>
