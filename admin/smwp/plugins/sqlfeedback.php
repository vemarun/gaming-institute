<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* sqlfeedback.php                          		  *
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

================================================================================
--------------------------=[Section Plugnin Management]=------------------------
================================================================================

--> Global Funtions can be found in ../inc/funktion.php

### General Webinterface Variables some can used here:

$usercheck['name'] = (User login name)

### General Plugin Variables some can used here:

$plugin['ID'] = (ID)
$plugin['Name'] = (Name)
$plugin['Systemname'] = (Systemname to select a plugin in the interface)
$plugin['Version'] = (Version)
$plugin['Author'] = (Author)
$plugin['Authorlink'] = (Link to Author Profile)
$plugin['Tablename'] = (Plugin MySQL Tablname)
$plugin['Support'] = (Link to Support)
$plugin['Showplug'] = (show plugin in navi. 0 = Hide / 1 = Show)

### Variables from Plugin Specific Settings some can used here:

$settings['sqlfeedback_show_entrys'] = (Show player per page)

### ============================================================================
*/

//echo "<pre>";
//print_r($Serverids);
// echo "</pre>";


  // Get serverip server scrollbox
  $sip_serverselectbox = (isset($_POST['serverscrollbox'])) ? $_POST['serverscrollbox']:'';
  // Get serverip from adressbar
  $sip = (isset($_GET['sip'])) ? $_GET['sip']:$sip_serverselectbox;
  
  // Explode ip and por
   
  if ($sip <> "") list($ip, $port) = explode(":", $sip);
  
  
  
  
  $go = (isset($_POST['goserverbutton'])) ? $_POST['goserverbutton']:'';
  $reset = (isset($_POST['resetserverbutton'])) ? $_POST['resetserverbutton']:'';
  
  if ($reset <> "") $action = 'reset';

 
   if ($area == ''){ // Begin if Area = ''
  
  // Define text for all mysqlbans templatefiles
  $tpl->set_var(array(
	"sqlfeedback_header_text"          => $plugin['Name'],
    "section"                  => $section,
    "plugin"                   => $plugin['Systemname'],
    "id"                       => $id,
  ));


// ---------------------[ If action "empty" show maintable]---------------------
if ($action == ""){

  // Define text for all advertisements templatefiles when action = empty
  $tpl->set_var(array(
    
	"sqlfeedback_server_discription" => $viewtext['System'][89],	
	"sqlfeedback_date_discription" => $viewtext['System'][114],
	"sqlfeedback_name_discription" => $viewtext['System'][84],
	"sqlfeedback_feedback_discription" => $viewtext['Sqlfeedback'][0],
	"sqlfeedback_map_discription" => $viewtext['System'][124],
	"sqlfeedback_game_discription" => $viewtext['System'][67],
	"sqlfeedback_no_entrys"       => $viewtext['Sqlfeedback'][6],
	"sqlfeedback_server_go_button_text" => $viewtext['System'][54],
	"sqlfeedback_server_reset_button_text" => $viewtext['System'][92]

    ));

  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/sqlfeedback.tpl.htm");

  // Define blocks to schow entrys in the loop's
  $tpl->set_block("inhalt", "sqlfeedbackview", "sqlfeedbackview_handle");
  $tpl->set_block("inhalt", "sqlfeedbackviewempty", "sqlfeedbackviewempty_handle");
  
  
  // ---> START show search section <---
  // Create empty array
  $allsearchcategories = array();
  // Define values in the serachscrollbox
  $allsearchcategories['value'] = array('name', 'feedback', 'steamid', 'map');
  // Define showtext in the serachscrollbox
  $allsearchcategories['view'] = array($viewtext['System'][84], $viewtext['Sqlfeedback'][0], $viewtext['System'][88], $viewtext['System'][124]);
  // Define serach table column's
  $allsearchcategories['table'] = array('name', 'feedback', 'steamid', 'map');
  
  if ($sip == ""){
  // Start funktion show search area
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, $plugin['Systemname'], '');
  // Search filter for sql injections
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, '');
  }else{
  // Start funktion show search area
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, $plugin['Systemname'], '&sip='.$sip.'');
  // Search filter for sql injections
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, "serverip ='".$ip."' AND serverport='".$port."'");
  }
  

  // ---> START show page section <---
  // Check Entry Count for Nextpage-Funktion
  //$all_entrys = mysql_num_rows(mysql_query("SELECT id FROM ".$plugin['Tablename']));
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$plugin['Tablename']." ".$searcquery));
  // If current page > Count of all pages set current page to count of pages
  if ($current_site > ceil($all_entrys / $settings['sqlfeedback_show_entrys'])) $current_site = 1;
  // Get Startentry
  $start_entry = $current_site * $settings['sqlfeedback_show_entrys'] - $settings['sqlfeedback_show_entrys'];
  // Start funktion show entry ... to ... from ...!
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['System'][85], $start_entry, $all_entrys, $settings['sqlfeedback_show_entrys']);
  // Start funktion page-select-links
  $tpl = site_links($tpl, $all_entrys, $settings['sqlfeedback_show_entrys'], $current_site, $section, '', $plugin['Systemname'], $searchcat, $searchstring, '&sip='.$sip.'', $viewtext);
  // ---> END Show Page Section <---

  PluginServerScrollbox($tpl, $table, $plugin['Tablename'], 'serverip', 'serverport', $sip, $viewtext);
  
  
  //Set startcount of errow handlecount
  $handle_count = $start_entry;

  $sql = "SELECT * FROM ".$plugin['Tablename']." ".$searcquery." ORDER BY id ASC LIMIT ".$start_entry.", ".$settings['sqlfeedback_show_entrys']."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

        // ++ handlecount for errows
        $handle_count++;

// Start to get Mod Icon from Mod Database
$tpl = PluginGameIcon($tpl, $table, 'folder', $row['game'], $handle_count, '');

// If serach string nothing: Call function to show and manage arrows
if ($searcquery == "") $tpl = ParseArrows($tpl, $start_entry, $all_entrys, $settings['advertisements_show_advertisements'], $handle_count,$current_site, $row['id'], $viewtext);


// Filter bad strings from playernames
  $row['name'] = BadStringFilterShow($row['name']);
  
  // Add space to long words
  $row['feedback'] = SpaceLongWords($row['feedback'], '30', '18');
  
  // Filter bad strings from playernames
  $row['feedback'] = BadStringFilterShow($row['feedback']);

   // Parse US/English date format into a unix timestamp and Get Day, Mounth, Jear e.t.c.
   $row['date'] = date($settings['sqlfeedback_date_format'], strtotime($row['date']));
   
   // Get Servername from serverlist
   $servername = GetServernme($tpl, $table, $row['serverip'], $row['serverport']);
  
 // Templatevars advertisement text to the loop.
      $tpl->set_var(array(
	  "sqlfeedback_date" => $row['date'],
	  "sqlfeedback_name" => $row['name'],
	  "sqlfeedback_feedback" => $row['feedback'],
	  "sqlfeedback_map" => $row['map'],
	  "sqlfeedback_server" => $servername
      ));
	  
      // Parse all templatevars to the advertisementsview block.
      $tpl->parse("sqlfeedbackview_handle", "sqlfeedbackview", true);

    }
    }else{
      // Parse all templatevars to the advertisementsviewempty block.
      $tpl->parse("sqlfeedbackviewempty_handle", "sqlfeedbackviewempty", true);
    }

}


if ($action == "reset"){
  if ($sip == ""){
    // Infobox for reset player entrys (Yes) or (No) all players
    $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Sqlfeedback'][3], $viewtext['System'][2], $viewtext['System'][1], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execreset");  
  }else{  
    // Infobox for reset player entrys (Yes) or (No) with serverip
    $viewtext['Sqlfeedback'][1]  = str_replace('%serverip%', $sip, $viewtext['Sqlfeedback'][1] );
    $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Sqlfeedback'][2], $viewtext['System'][2], $viewtext['System'][1], "index.php?section=plugins&plugin=".$plugin['Systemname']."&sip=".$sip."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execreset&sip=".$sip."");    
  }
}

if ($action == "execreset"){
  if ($sip == ""){
    sqlexec("TRUNCATE TABLE ".$plugin['Tablename']."");
  $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Sqlfeedback'][5] , $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."");  
  }else{
    sqlexec("DELETE * FROM ".$plugin['Tablename']." WHERE serverip = '".$ip."' AND serverport = '".$port."'");
  $viewtext['Sqlfeedback'][3]  = str_replace('%serverip%', $sip, $viewtext['Sqlfeedback'][4] );
  $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Sqlfeedback'][3] , $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."");
  }
  
  
}

}
//==============================================================================
//--------------------------=[Section Plugnin Settings]=------------------------
//==============================================================================

// ---> BEGIN Create Table for ChatLog Extended Plugin
if ($area == 'createtable'){

		sqlexec("CREATE TABLE IF NOT EXISTS feedback (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(65) NOT NULL,
		  steamid varchar(32) NOT NULL,
		  map varchar(32) NOT NULL,
		  serverip varchar(16) NOT NULL,
		  serverport varchar(6) NOT NULL,
		  game varchar(32) NOT NULL,
		  feedback varchar(255) NOT NULL,
		  date timestamp NOT NULL default CURRENT_TIMESTAMP,
		  PRIMARY KEY (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
    
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
  $tpl->set_file("sqlfeedback_specific_settings", "templates/plugins/sqlfeedback_specific_settings.tpl.htm");

  // Settingsaray from Settingstable and Description sending to Template var.
  $tpl->set_var(array(
     "sqlfeedback_specific_settings_show_entrys_discription"  => $viewtext['Sqlfeedback'][1],
     "sqlfeedback_specific_settings_show_entrys"  => $settings['sqlfeedback_show_entrys'],
	 "sqlfeedback_specific_settings_dateformat_discription"  => $viewtext['System'][53],
     "sqlfeedback_specific_settings_dateformat"  => $settings['sqlfeedback_date_format']

	));
	
  // Parse advertisements_specific_settings.tpl.htm to plugins_settings.tpl.htm
  $tpl->set_var(array("specific_settings" => $tpl->parse("out", "sqlfeedback_specific_settings"),));
}

//------------------------------------------------------------------------------

// ---> BEGIN to save the Specific Settings in the Settings Table.

if ($area == 'savepecificsettings'){

  // Read inputfields from html template (Array must be $plugin['...']).
  $plugin['showentrys']   = (isset($_POST['showentrys'])) ? $_POST['showentrys']:'';
  $plugin['dateformat']   = (isset($_POST['dateformat'])) ? $_POST['dateformat']:''; 
 

  // Checkt type is Integer, when not or empty set to "0".
  if(isset($plugin['showentrys'])) settype($plugin['showentrys'], "integer");

  // When var is "0" set to Default
  if ($plugin['showentrys'] == 0) $plugin['showentrys'] = 30;

  // When var is empty set to default.
  if ($plugin['showentrys'] == '') $plugin['showentrys'] = 30;
  if ($plugin['dateformat'] == '') $plugin['dateformat'] = 'm-d-Y H:i:s';


  // Save var in settings-table.
  sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['showentrys']."' WHERE Name = 'sqlfeedback_show_entrys'");
    sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['dateformat']."' WHERE Name = 'sqlfeedback_date_format'");

}

//==============================================================================
//==============================================================================
//==============================================================================
?>
