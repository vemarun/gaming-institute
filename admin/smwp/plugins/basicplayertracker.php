<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* basicplayertracker.php                          *
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

$settings['basicplayertracker_show_entrys'] = (Show player per page)

### ============================================================================
*/

//echo "<pre>";
//print_r($Serverids);
// echo "</pre>";


  // Get serverip server scrollbox
  $sip_serverselectbox = (isset($_POST['serverscrollbox'])) ? $_POST['serverscrollbox']:'';
   // Get serverip from adressbar
  $sip = (isset($_GET['sip'])) ? $_GET['sip']:$sip_serverselectbox;
  
  
    if ($sip <> "") list($ip, $port) = explode(":", $sip);
  
  
  $go = (isset($_POST['goserverbutton'])) ? $_POST['goserverbutton']:'';
  $reset = (isset($_POST['resetserverbutton'])) ? $_POST['resetserverbutton']:'';
  //$duplicate = (isset($_POST['duplicateserverbutton'])) ? $_POST['duplicateserverbutton']:'';
  
  
  
  
  if ($reset <> "") $action = 'reset';
  //if ($duplicate <> "") $action = 'duplicate';
  

 
   if ($area == ''){ // Begin if Area = ''
  
  // Define text for all mysqlbans templatefiles
  $tpl->set_var(array(
	"basicplayertracker_header_text"          => $plugin['Name'],
    "section"                  => $section,
    "plugin"                   => $plugin['Systemname'],
    "id"                       => $id,
  ));


// ---------------------[ If action "empty" show maintable]---------------------
if ($action == ""){

  // Define text for all advertisements templatefiles when action = empty
  $tpl->set_var(array(
    
	
    "basicplayertracker_ip_discription"     => $viewtext['System'][87],
	"basicplayertracker_name_discription" => $viewtext['System'][84],
	"basicplayertracker_steamid_discription" => $viewtext['System'][88],
	"basicplayertracker_server_discription" => $viewtext['System'][89],
	"basicplayertracker_status_discription" => $viewtext['System'][90],
	"basicplayertracker_options_discription" => $viewtext['System'][65],
	"basicplayertracker_game_discription" => $viewtext['System'][67],
	"basicplayertracker_no_entrys"       => $viewtext['System'][86],
	"basicplayertracker_server_go_button_text" => $viewtext['System'][54],
	"basicplayertracker_server_reset_button_text" => $viewtext['System'][92],

    ));
	
	//"basicplayertracker_server_douplikate_button_text" => $viewtext['Basicplayertracker'][5]

  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/basicplayertracker.tpl.htm");

  // Define blocks to schow entrys in the loop's
  $tpl->set_block("inhalt", "basicplayertrackerview", "basicplayertrackerview_handle");
  $tpl->set_block("inhalt", "basicplayertrackerviewempty", "basicplayertrackerviewempty_handle");
  
  
  // ---> START show search section <---
  // Create empty array
  $allsearchcategories = array();
  // Define values in the serachscrollbox
  $allsearchcategories['value'] = array('playername', 'steamid', 'playerip');
  // Define showtext in the serachscrollbox
  $allsearchcategories['view'] = array($viewtext['System'][84], $viewtext['System'][88], $viewtext['System'][87]);
  // Define serach table column's
  $allsearchcategories['table'] = array('playername', 'steamid', 'playerip');
  
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
  if ($current_site > ceil($all_entrys / $settings['basicplayertracker_show_entrys'])) $current_site = 1;
  // Get Startentry
  $start_entry = $current_site * $settings['basicplayertracker_show_entrys'] - $settings['basicplayertracker_show_entrys'];
  // Start funktion show entry ... to ... from ...!
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['System'][85], $start_entry, $all_entrys, $settings['basicplayertracker_show_entrys']);
  // Start funktion page-select-links
  $tpl = site_links($tpl, $all_entrys, $settings['basicplayertracker_show_entrys'], $current_site, $section, '', $plugin['Systemname'], $searchcat, $searchstring, '&sip='.$sip.'', $viewtext);
  // ---> END Show Page Section <---

  PluginServerScrollbox($tpl, $table, $plugin['Tablename'], 'serverip', 'serverport', $sip, $viewtext);
  
  
  //Set startcount of errow handlecount
  $handle_count = $start_entry;

  $sql = "SELECT * FROM ".$plugin['Tablename']." ".$searcquery." ORDER BY id ASC LIMIT ".$start_entry.", ".$settings['basicplayertracker_show_entrys']."";

  
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

        // ++ handlecount for errows
        $handle_count++;

// Start to get Mod Icon from Mod Database
$tpl = PluginGameIcon($tpl, $table, 'folder', $row['servertype'], $handle_count, '');

// If serach string nothing: Call function to show and manage arrows
if ($searcquery == "") $tpl = ParseArrows($tpl, $start_entry, $all_entrys, $settings['advertisements_show_advertisements'], $handle_count,$current_site, $row['id'], $viewtext);

  // Filter bad strings from playernames
  $row['playername'] = BadStringFilterShow($row['playername']);
  
  // convert geoipcountry to lowercase
  $row['geoipcountry'] = strtolower($row['geoipcountry']) ;
	  
	  
	if ($row['geoipcountry'] <> ""){  	  
	  if ((strlen($row['geoipcountry']) == 2) AND (file_exists("inc/pics/flags/".$row['geoipcountry'].".gif")) ) {
	  	$country = '<IMG src="inc/pics/flags/'.$row['geoipcountry'].'.gif" title="'.$row['geoipcountry'].'" border=0>';
      }else{
	    $country = '<IMG src="inc/pics/flags/0.gif" title="'.$row['geoipcountry'].'" border=0>';
      }
	}else{
	  $country = '<IMG src="inc/pics/flags/nocountry.gif" title="'.$viewtext['System'][45].'" border=0>';
    }
	
 // Templatevars advertisement text to the loop.
      $tpl->set_var(array(
	  "basicplayertracker_name" => $row['playername'],
	  "basicplayertracker_steamid" => $row['steamid'],
	  "basicplayertracker_ip"     =>  $row['playerip'],
	  "basicplayertracker_status" => $row['status'],
	  "basicplayertracker_country" => $country	  
      ));
	  
	  
      // Parse all templatevars to the advertisementsview block.
      $tpl->parse("basicplayertrackerview_handle", "basicplayertrackerview", true);


    }
    }else{
      // Parse all templatevars to the advertisementsviewempty block.
      $tpl->parse("basicplayertrackerviewempty_handle", "basicplayertrackerviewempty", true);
    }

}

/*if ($action == "duplicate"){ 
  if ($sip == ""){  
    $tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][125] , $viewtext['System'][4], "index.php?section=plugins&plugin=".$plugin['Systemname']."");  
  }else{
    $viewtext['Basicplayertracker'][6]  = str_replace('%serverip%', $sip, $viewtext['Basicplayertracker'][6] );
	$tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Basicplayertracker'][6], $viewtext['System'][2], $viewtext['System'][1], "index.php?section=plugins&plugin=".$plugin['Systemname']."&sip=".$sip."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execduplicate&sip=".$sip."");    
  }
}

if ($action == "execduplicate"){ 

$sql = "SELECT * FROM ".$plugin['Tablename']."
WHERE (steamid,serverip) IN
(SELECT steamid,serverip
FROM ".$plugin['Tablename']."
GROUP BY steamid,serverip
HAVING COUNT(*)>1)";
  $result = mysql_query($sql) OR die(mysql_error());
if(mysql_num_rows($result)){
  while($row = mysql_fetch_assoc($result)){
    echo "".$row['steamid']."-".$row['serverip']."<br>";
  }
}


$tpl = Infobox($tpl, $plugin['Name'], 'Test' , $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."");
}*/






/*

DELETE FROM post USING post t1, post t2
WHERE t1.postid = t2.postid AND
t1.threadid = t2.threadid AND
t1.parentid = t2.parentid AND
t1.username = t2.username AND
t1.userid = t2.userid AND
t1.title = t2.title AND
t1.dateline = t2.dateline AND
t1.pagetext = t2.pagetext AND
t1.allowsmilie = t2.allowsmilie AND
t1.ipaddress = t2.ipaddress AND
t1.iconid = t2.iconid AND
t1.visible = t2.visible AND
t1.attach = t2.attach AND
t1.importthreadid = t2.importthreadid AND
t1.importpostid = t2.importpostid

*/







if ($action == "reset"){
  if ($sip == ""){
    // Infobox for reset player entrys (Yes) or (No) all players
    $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Basicplayertracker'][2], $viewtext['System'][2], $viewtext['System'][1], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execreset");  
  }else{  
    // Infobox for reset player entrys (Yes) or (No) with serverip
    $viewtext['Basicplayertracker'][1]  = str_replace('%serverip%', $sip, $viewtext['Basicplayertracker'][1] );
    $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Basicplayertracker'][1], $viewtext['System'][2], $viewtext['System'][1], "index.php?section=plugins&plugin=".$plugin['Systemname']."&sip=".$sip."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execreset&sip=".$sip."");    
  }
}

if ($action == "execreset"){
  if ($sip == ""){
    sqlexec("TRUNCATE TABLE ".$plugin['Tablename']."");
  $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Basicplayertracker'][4] , $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."");  
  }else{
    sqlexec("DELETE FROM ".$plugin['Tablename']." WHERE serverip = '".$sip."'");
  $viewtext['Basicplayertracker'][3]  = str_replace('%serverip%', $sip, $viewtext['Basicplayertracker'][3] );
  $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Basicplayertracker'][3] , $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."");
  }
  
  
}

}
//==============================================================================
//--------------------------=[Section Plugnin Settings]=------------------------
//==============================================================================

// ---> BEGIN Create Table for ChatLog Extended Plugin
if ($area == 'createtable'){

  sqlexec("CREATE TABLE player_tracker (
  id int(11) NOT NULL auto_increment,
  steamid varchar(255) NOT NULL,
  playername varchar(255) NOT NULL,
  playerip varchar(255) NOT NULL,
  servertype varchar(255) NOT NULL,
  serverip varchar(255) NOT NULL,
  serverport varchar(255) NOT NULL,
  geoipcountry varchar(255) NOT NULL,
  status varchar(255) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY steamid (steamid),
  KEY playername (playername),
  KEY playerip (playerip),
  KEY servertype (servertype),
  KEY serverip (serverip),
  KEY status (status)
)");
	
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
  $tpl->set_file("basicplayertracker_specific_settings", "templates/plugins/basicplayertracker_specific_settings.tpl.htm");

  // Settingsaray from Settingstable and Description sending to Template var.
  $tpl->set_var(array(
     "basicplayertracker_specific_settings_show_entrys_discription"  => $viewtext['Basicplayertracker'][0],
     "basicplayertracker_specific_settings_show_entrys"  => $settings['basicplayertracker_show_entrys'],

	));
	
  // Parse advertisements_specific_settings.tpl.htm to plugins_settings.tpl.htm
  $tpl->set_var(array("specific_settings" => $tpl->parse("out", "basicplayertracker_specific_settings"),));
}

//------------------------------------------------------------------------------

// ---> BEGIN to save the Specific Settings in the Settings Table.

if ($area == 'savepecificsettings'){

  // Read inputfields from html template (Array must be $plugin['...']).
  $plugin['showentrys']   = (isset($_POST['showentrys'])) ? $_POST['showentrys']:'';
 

  // Checkt type is Integer, when not or empty set to "0".
  if(isset($plugin['showentrys'])) settype($plugin['showentrys'], "integer");

  // When var is "0" set to Default
  if ($plugin['showentrys'] == 0) $plugin['showentrys'] = 30;

  // When var is empty set to default.
  if ($plugin['showentrys'] == '') $plugin['showentrys'] = 30;


  // Save var in settings-table.
  sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['showentrys']."' WHERE Name = 'basicplayertracker_show_entrys'");

}

//==============================================================================
//==============================================================================
//==============================================================================
?>
