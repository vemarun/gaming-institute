<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* chatlogextended.php                             *
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

### Some Funtions can be found in ../inc/funktion.php

/*
================================================================================
--------------------------=[Section Plugnin Management]=------------------------
================================================================================

### General Webinterface Variables some can used here:

$usercheck['name'] = (User login name)

### General Plugin Variables some can used here:

$plugin['ID'] = (ID)
$plugin['Name'] = (Name)
$plugin['Systemname'] = (Systemname to select a plugin in the interface)
$plugin['MMS'] = (Multiserver Support Cvar)
$plugin['MMSColum'] = (Colum for Server-ID from Plugintable)
$plugin['Version'] = (Version)
$plugin['Author'] = (Author)
$plugin['Authorlink'] = (Link to Author Profile)
$plugin['Tablename'] = (Plugin MySQL Tablname)
$plugin['Support'] = (Link to Support)
$plugin['Showplug'] = (show plugin in navi. 0 = Hide / 1 = Show)
$Serverids = (Array with all servers and Infos)

### Variables from Plugin Specific Settings some can used here:

// $settings['chatlogex_show_entrys'] = Lines per page
// $settings['chatlogex_date_format'] = Date Format

### ============================================================================
*/

//echo "<pre>";
//print_r($Serverids);
// echo "</pre>";

if ($area == ''){ // Begin if Area = ''

  // Get serverid server scrollbox
  $sid_serverselectbox = (isset($_POST['serverid'])) ? $_POST['serverid']:'';
  // Get serverid from adressbar
  $sid = (isset($_GET['sid'])) ? $_GET['sid']:$sid_serverselectbox;

  $go = (isset($_POST['goserverbutton'])) ? $_POST['goserverbutton']:'';
  $reset = (isset($_POST['resetserverbutton'])) ? $_POST['resetserverbutton']:'';

  // Read servid array and get servername
  $servername = '';
  if (isSet($Serverids[$sid])){
    if ($Serverids[$sid]['servercount'] > 1){
      $servername = $plugin['MMS'].' = '.$sid.'&nbsp;('.$viewtext['System'][122] .')';
    }else{
      $servername = $Serverids[$sid]['name'][1];
    }
  }

  if ($action <> "execreset"){

  if ($reset == ""){

   
  // Check if the hidefilter-apply-button was pressed.
  if ($action == "apply"){
    // Get filter checkbox settings
    $connections_hide = (isset($_POST['connections'])) ? $_POST['connections']:'0';
    $playersonline_hide = (isset($_POST['playersonline'])) ? $_POST['playersonline']:'0';
    $mapinfos_hide = (isset($_POST['mapinfos'])) ? $_POST['mapinfos']:'0';
    $console_hide = (isset($_POST['console'])) ? $_POST['console']:'0';
    $filter = (isset($_POST['filter'])) ? $_POST['filter']:'';

    // Save filter seetings to database
    sqlexec("UPDATE ".$table."_settings SET Value='".$connections_hide."' WHERE Name = 'chatlogex_hide_connections'");
    sqlexec("UPDATE ".$table."_settings SET Value='".$playersonline_hide."' WHERE Name = 'chatlogex_hide_playersonline'");
    sqlexec("UPDATE ".$table."_settings SET Value='".$mapinfos_hide."' WHERE Name = 'chatlogex_hide_mapinfos'");
    sqlexec("UPDATE ".$table."_settings SET Value='".$console_hide."' WHERE Name = 'chatlogex_hide_console'");
    sqlexec("UPDATE ".$table."_settings SET Value='".$filter."' WHERE Name = 'chatlogex_filter'");
  }// End $action = apply
     
 // Read all settings from setiings database
 $settings = Read_Settings($table);

 // add advancedfilter settings to inputfield.
 $tpl->set_var(array("chatlogex_filter" => $settings['chatlogex_filter']));

 // add hidefilter settings to checkbox
 if ($settings['chatlogex_hide_connections'] == 1){
   $tpl->set_var(array("connectionschecked" => "checked"));
 }else{
   $tpl->set_var(array("connectionschecked" => ""));
 }
 if ($settings['chatlogex_hide_playersonline'] == 1){
   $tpl->set_var(array("playersonlinechecked" => "checked"));
 }else{
   $tpl->set_var(array("playersonlinechecked" => ""));
 }
 if ($settings['chatlogex_hide_mapinfos'] == 1){
   $tpl->set_var(array("mapinfoschecked" => "checked"));
 }else{
   $tpl->set_var(array("mapinfoschecked" => ""));
 }
 if ($settings['chatlogex_hide_console'] == 1){
   $tpl->set_var(array("consolechecked" => "checked"));
 }else{
   $tpl->set_var(array("consolechecked" => ""));
 }
 // Define stationery text and set templatevars.
 $tpl->set_var(array(
    "pluginname"                                => $plugin['Name'],
    "plugin"                                    => $plugin['Systemname'],
    "section"                                   => $section,
    "chatlogex_serverid_cvar"                   => $plugin['MMS'],
    "chatlogex_server"                          => $servername,
    "sid"                                       => $sid,
    "back_button_text"                          => $viewtext['System'][4],
    "menue_chatlogex_date"                      => $viewtext['System'][114],
    "menue_chatlogex_nick"                      => $viewtext['System'][84],
    "menue_chatlogex_text"                      => $viewtext['System'][118],
    "menue_chatlogex_connections"               => $viewtext['Chatlogex'][10],
    "menue_chatlogex_playersonline"             => $viewtext['Chatlogex'][6],
    "menue_chatlogex_mapinfos"                  => $viewtext['Chatlogex'][4],
    "menue_chatlogex_console"                   => $viewtext['Chatlogex'][0],
    "menue_chatlogex_hide"                      => $viewtext['Chatlogex'][2],
    "chatlogex_hide_apply_button_text"          => $viewtext['System'][7],
    "chatlogex_server_go_button_text"           => $viewtext['System'][54],
    "menue_chatlogex_filter"                    => $viewtext['System'][55],
    "menue_chatlogex_server"                    => $viewtext['System'][89],
    "chatlogex_server_reset_button_text"        => $viewtext['System'][92]
    ));

  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/chatlogextended.tpl.htm");

  // Define blocks
  $tpl->set_block("inhalt", "chatlogexempty", "chatlogexempty_handle");
  $tpl->set_block("inhalt", "chatlogexanzeige", "chatlogexanzeige_handle");
  $tpl->set_block("inhalt", "scrollserverblock", "scrollserverblock_handle");
  
  //Read admins from database and add it to an array
  $chatadmins = AdminsToArray($smtable);
 
 
 


  // Get Table from MySQL Bans Plugin
  $sql = "SELECT * FROM ".$table."_plugins WHERE Systemname = 'mysqlbans'";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
      $mysqlbans = array();
      if (Table_Exists($row['Tablename'])=== True){
        // Read MySQL-Bans database and add all bans to array
		$mysqlbans = MysqlBansToArray($row['Tablename']);	
	  }
	}
  }
  
  
  
  
  
  
  
 
 // Read servid array an parse results to the serverscrollbox
  // If 'sid' have more than 1 server or no server-infos aviable then replace servername with mms-cvar
  
  
    //if (isSet($Serverids)){
  foreach($Serverids as $pluginsid => $handle){
    $tpl->set_var(array("scrollservervalue"  => $pluginsid));
    if ($pluginsid == $sid){
      $tpl->set_var(array("scrollserverselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("scrollserverselected" => ""));
    }
    if ($Serverids[$pluginsid]['servercount'] > 1){
      $tpl->set_var(array("scrollserver" => $plugin['MMS'].' = '.$pluginsid.'&nbsp;('.$viewtext['System'][122] .')'));
    }else{
      $name = $Serverids[$pluginsid]['name'][1];
      if (isSet($Serverids[$pluginsid]['ip'][1])) $name = $name.'&nbsp;&lt;'.$Serverids[$pluginsid]['ip'][1].'&gt;';
      $tpl->set_var(array("scrollserver" => $name));
    }
      $tpl->parse("scrollserverblock_handle", "scrollserverblock", true);
  }//-----------
  /*}else{
  
  $tpl->set_var(array(
  "scrollserver" => "all",
  "scrollservervalue" => $sid
  
  ));
  $tpl->parse("scrollserverblock_handle", "scrollserverblock", true);  
  }*/
  
  
  
  

  // ---> START show search section <---
  // Create empty array
  $allsearchcategories = array();
  // Define values in the serachscrollbox
  $allsearchcategories['value'] = array('text', 'name', 'steamid');
  // Define showtext in the serachscrollbox
  $allsearchcategories['view'] = array($viewtext['System'][118], $viewtext['System'][84], $viewtext['System'][88]);
  // Define serach table column's
  $allsearchcategories['table'] = array('text', 'name', 'steamid');
  // Start funktion show search area
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, $plugin['Systemname'], '&sid='.$sid.'');
  // Search filter for sql injections
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, "srvid='".$sid."'");
  // ---> END show search section <---

  // Check hidefilter and add sql-query to $hidefilter
  $hidefilterquery = '';
  if ($settings['chatlogex_hide_connections'] == 1) $hidefilterquery = "".$hidefilterquery." AND ((name <> '') OR (NOT text LIKE '%connected%'))";
  if ($settings['chatlogex_hide_playersonline'] == 1) $hidefilterquery = "".$hidefilterquery." AND (((name <> '') OR (steamid <> '')) OR (NOT text LIKE '%online%'))";
  if ($settings['chatlogex_hide_mapinfos'] == 1) $hidefilterquery = "".$hidefilterquery." AND (((name <> '') OR (steamid <> '')) OR (NOT text LIKE '%Map%'))";
  if ($settings['chatlogex_hide_console'] == 1) $hidefilterquery = "".$hidefilterquery." AND ((steamid <> '') OR (NOT name = 'console'))";

  // Check textfilter not empty
  $textfilterquery = '';
  if ($settings['chatlogex_filter'] <> ""){
    // Split words from filter to an Array
    $textfilter = explode(',',$settings['chatlogex_filter']);
    // List all filter-words from array
    foreach($textfilter as $key => $word) { //-+-
      // create an sqlquery
      $textfilterquery = "".$textfilterquery." AND (NOT text LIKE '%".$word."%')";
    }
  }
  
  // ---> START show page section <---
  // Check Entry Count for Nextpage-Funktion
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$plugin['Tablename']." ".$searcquery." ".$hidefilterquery." ".$textfilterquery.""));
  // If current page > Count of all pages set current page to count of pages
  if ($current_site > ceil($all_entrys / $settings['chatlogex_show_entrys'])) $current_site = 1;
  // Get Startentry
  $start_entry = $current_site * $settings['chatlogex_show_entrys'] - $settings['chatlogex_show_entrys'];
  // Start funktion show entry ... to ... from ...!
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['Chatlogex'][9], $start_entry, $all_entrys, $settings['chatlogex_show_entrys']);
  // Start funktion page-select-links
  $tpl = site_links($tpl, $all_entrys, $settings['chatlogex_show_entrys'], $current_site, $section, '', $plugin['Systemname'], $searchcat, $searchstring, '&sid='.$sid.'', $viewtext);
  // ---> END Show Page Section <---


  
  // Read chatlogs from table
  $sql = "SELECT * FROM ".$plugin['Tablename']." ".$searcquery." ".$hidefilterquery." ".$textfilterquery." ORDER BY date DESC LIMIT ".$start_entry.", ".$settings['chatlogex_show_entrys']."";
  
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

	
	// Filter bad strings from playernames
     $row['name'] = BadStringFilterShow($row['name']);
	
     // Parse US/English date format into a unix timestamp and Get Day, Mounth, Jear e.t.c.
     $date = date($settings['chatlogex_date_format'], strtotime($row['date']));
     // Check name is empty. if true replace it.
     if(($row['name'] == '') and ($row['steamid'] <> '') and (eregi('player', $row['steamid'])))$row['name'] = $viewtext['Chatlogex'][3];
     
	   // Add space to long words
	   $row['text'] = SpaceLongWords( $row['text'], '35', '18');
     
	 
	 	// Filter bad strings from playernames
     $row['text'] = BadStringFilterShow($row['text']);
	 

     // Det default color for texthighlighting
	 $tpl->set_var(array("chatlogex_text_color" => $settings['chatlogex_color_default']));
     // Change defualt bolding for texthighlighting
     $tpl->set_var(array("beginbold" => '',"endbold"  => ''));

     // List all admins from array
      foreach($chatadmins as $key => $value) {
        // check is authtype steamid
        if (($chatadmins[$key]['authtype'] == "steam") and ($chatadmins[$key]['identity'] == $row['steamid'])){
           // Change color for texthighlighting
	       $tpl->set_var(array("chatlogex_text_color" => $settings['chatlogex_highlightingcolor_admin']));
           // Change bolding for texthighlighting
           $tpl->set_var(array("beginbold" => '<b>',"endbold"  => '</b>'));
        }
      }
	  	   
	 // Check if sqlbans array exits 
	 if (isSet($mysqlbans['steam'])){
	   // Find steamid in sqlbans array
	   if(in_array($row['steamid'], $mysqlbans['steam'])){
	     // Change color for texthighlighting
	     $tpl->set_var(array("chatlogex_text_color" => $settings['chatlogex_highlightingcolor_ban']));
         // Change bolding for texthighlighting
         $tpl->set_var(array("beginbold" => '<b>',"endbold"  => '</b>'));
	  }
	}
	       
     // Parse databaseresults to templatevars.
     $tpl->set_var(array(
       "chatlogex_date"        => $date,
       "chatlogex_nick"        => $row['name'],
       "chatlogex_text"        => $row['text'],
     ));
     // Show chatlogexanzeige block
     $tpl->parse("chatlogexanzeige_handle", "chatlogexanzeige", true);
     }
   }else{
     if ($sid == ""){
       // Parse msg to templatevars hwhen no is selectet.
       $tpl->set_var(array("chatlogex_empty"        => $viewtext['System'][125]));
     }else{
       // Parse empty msg to templatevars.
       $tpl->set_var(array("chatlogex_empty"        => $viewtext['Chatlogex'][5]));
     }
     // Show chatlogexempty block
     $tpl->parse("chatlogexempty_handle", "chatlogexempty", true);
   }
   
  }else{
   // Infobox for reset chatlog entrys (Yes) or (No)
   $viewtext['Chatlogex'][7] = str_replace('%servername%', $servername, $viewtext['Chatlogex'][7]);
   $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Chatlogex'][7], $viewtext['System'][2], $viewtext['System'][1], "index.php?section=plugins&plugin=".$plugin['Systemname']."&sid=".$sid."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execreset&sid=".$sid."");
  }
  
  }else{
   // Infobox for reset chatlog entrys successfully
   sqlexec("DELETE FROM ".$plugin['Tablename']." WHERE srvid = ".$sid."");
   $viewtext['Chatlogex'][8] = str_replace('%servername%', $servername, $viewtext['Chatlogex'][8]);
   $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Chatlogex'][8], $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."&sid=".$sid."");
  }
  
} // End if Area = ''

//==============================================================================
//--------------------------=[Section Plugnin Settings]=------------------------
//==============================================================================

// ---> BEGIN Create Table for ChatLog Extended Plugin
if ($area == 'createtable'){

  sqlexec("CREATE TABLE ".$plugintable."(
    seqid        int(10) unsigned NOT NULL auto_increment,
    srvid        varchar(255) NOT NULL,
    date         timestamp NOT NULL default CURRENT_TIMESTAMP,
    name         varchar(32) NOT NULL,
    steamid      varchar(32) NOT NULL,
    text         varchar(192) NOT NULL,
    team         int(1) NOT NULL,
    type         int(2) NOT NULL,
    PRIMARY KEY  (seqid),
    KEY          srvid (srvid),
    KEY          steamid (steamid)
    )
    ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
}
//------------------------------------------------------------------------------

// ---> BEGIN Delete Table for ChatLog Extended Plugin
if ($area == 'deltable'){
  sqlexec("DROP TABLE IF EXISTS ".$plugintable."");
}
//------------------------------------------------------------------------------

// ---> BEGIN to show the Specific Settings from the Settings Table.

if ($area == 'readspecificsettings'){

  // Set Specific Settings Template file
  $tpl->set_file("chatlogextended_specific_settings", "templates/plugins/chatlogextended_specific_settings.tpl.htm");

  // Settingsaray from Settingstable and Description sending to Template var.
  $tpl->set_var(array(
    "chatlogex_specific_settings_highlightingcolor_ban_info" => $viewtext['System'][96],
    "chatlogex_specific_settings_show_entrys_discription"  => $viewtext['Chatlogex'][1],
    "chatlogex_specific_settings_show_entrys"  => $settings['chatlogex_show_entrys'],
    "chatlogex_specific_settings_date_format_discription"  => $viewtext['System'][53],
    "chatlogex_specific_settings_date_format"  => $settings['chatlogex_date_format'],
    "chatlogex_specific_settings_color_default_discription"  => $viewtext['System'][95],
    "chatlogex_specific_settings_color_default"  => $settings['chatlogex_color_default'],
	"chatlogex_specific_settings_highlightingcolor_admin_discription"  => $viewtext['System'][93],
    "chatlogex_specific_settings_highlightingcolor_admin"  => $settings['chatlogex_highlightingcolor_admin'],
    "chatlogex_specific_settings_highlightingcolor_ban_discription"  => $viewtext['System'][94],
    "chatlogex_specific_settings_highlightingcolor_ban"  => $settings['chatlogex_highlightingcolor_ban']
	));
	
  // Parse chatlogextended_specific_settings.tpl.htm to plugins_settings.tpl.htm
  $tpl->set_var(array("specific_settings" => $tpl->parse("out", "chatlogextended_specific_settings"),));
}

//------------------------------------------------------------------------------

// ---> BEGIN to save the Specific Settings in the Settings Table.

if ($area == 'savepecificsettings'){

  // Read inputfields from html template (Array must be $plugin['...']).
  $plugin['show_entrys']               = (isset($_POST['showentrys'])) ? $_POST['showentrys']:'';
  $plugin['date_format']   		       = (isset($_POST['dateformat'])) ? $_POST['dateformat']:'';
  $plugin['color_default']     		   = (isset($_POST['color_default'])) ? $_POST['color_default']:'';
  $plugin['highlightingcolor_admin']   = (isset($_POST['highlightingcolor_admin'])) ? $_POST['highlightingcolor_admin']:'';
  $plugin['highlightingcolor_ban']     = (isset($_POST['highlightingcolor_ban'])) ? $_POST['highlightingcolor_ban']:'';

  // Checkt type is Integer, when not or empty set to "0".
  if(isset($plugin['show_entrys'])) settype($plugin['show_entrys'], "integer");

  // When var is "0" set to Default
  if ($plugin['show_entrys'] == 0) $plugin['show_entrys'] = 30;

  // When var is empty set to default.
  if ($plugin['date_format'] == '') $plugin['date_format'] = 'm-d-y H:i:s';
  if ($plugin['color_default'] == '') $plugin['color_default'] = '#000000';
  if ($plugin['highlightingcolor_admin'] == '') $plugin['highlightingcolor_admin'] = '#FF8000';
  if ($plugin['highlightingcolor_ban'] == '') $plugin['highlightingcolor_ban'] = '#FF0000';

  // Save var in settings-table.
  sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['show_entrys']."' WHERE Name = 'chatlogex_show_entrys'");
  sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['date_format']."' WHERE Name = 'chatlogex_date_format'");
  sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['color_default']."' WHERE Name = 'chatlogex_color_defualt'");
  sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['highlightingcolor_admin']."' WHERE Name = 'chatlogex_highlightingcolor_admin'");
  sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['highlightingcolor_ban']."' WHERE Name = 'chatlogex_highlightingcolor_ban'");
}

//==============================================================================
//==============================================================================
//==============================================================================
?>








