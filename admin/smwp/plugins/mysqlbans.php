<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* mysqlbans.php                                   *
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

### All Funtions can be found in ../inc/funktion.php

/*
================================================================================
--------------------------=[Section Plugnin Management]=------------------------
================================================================================

### General Webinterface Variables some can used be here:

$usercheck['name'] = (User login name)

### General Plugin Variables some can be used here:

$plugin['ID'] = (ID)
$plugin['Name'] = (Name)
$plugin['Systemname'] = (Systemname to select a plugin in the interface)
$plugin['MMS'] = (Multiserver Support Cvar)
$plugin['MMSColum'] = (Colum for Server-ID from Plugintable)
$plugin['Version'] = (Version)
$plugin['Author'] = (Author)
$plugin['Authorlink'] = (Link to Author Profile)
$plugin['Tablename'] = (Plugin MySQL Tablname)
$plugin['MMSColum'] = (Server ID columname from Plugintable)
$plugin['Support'] = (Link to Support)
$plugin['Showplug'] = (show plugin in navi. 0 = Hide / 1 = Show)

### Variables from Plugin Specific Settings some can used be here:

$settings['mysqlbans_show_bans'] = Bans show per Page
$settings['mysqlbans_date_format'] = Bandate Format

*/


if ($area == ''){ // Begin if Area = ''

  // Define text for all mysqlbans templatefiles
  $tpl->set_var(array(
    "ban_header_text"          => $plugin['Name'],
    "ban_nick_discription"     => $viewtext['System'][84],
    "ban_reason_discription"   => $viewtext['Mysqlbans'][3],
    "ban_length_discription"   => $viewtext['Mysqlbans'][4],
    "ban_date_discription"     => $viewtext['System'][114],
    "ban_steamid_discription"  => $viewtext['System'][88],
    "ban_ip_discription"       => $viewtext['System'][87],
    "ban_back_button_text"     => $viewtext['System'][4],
    "section"                  => $section,
    "plugin"                   => $plugin['Systemname'],
    "id"                       => $id,
    "page"                     => $current_site
  ));


// =============================[ If action empty  ]============================

if ($action == ""){ 

// -------------------------------[ If id empty  ]------------------------------

if ($id == ""){ // If ID empty list all bans

  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/mysqlbans.tpl.htm");

  // Define stationery text and set templatevars.
  $tpl->set_var(array(
    "ban_by_discription"       => $viewtext['System'][115],
    "ban_options_discription"  => $viewtext['System'][65],
    "ban_details_pic_infotext" => $viewtext['System'][24],
    "ban_edit_pic_infotext"    => $viewtext['Mysqlbans'][9],
    "ban_delete_pic_infotext"  => $viewtext['Mysqlbans'][10],
    "ban_add_button_text"      => $viewtext['Mysqlbans'][11],
    "ban_import_discription"   => $viewtext['System'][116],
    "ban_export_pic_infotext"  => $viewtext['Mysqlbans'][6],
    "ban_import_pic_infotext"  => $viewtext['Mysqlbans'][37]
  ));

  // Define blocks to schow entrys in the loop's
  $tpl->set_block("inhalt", "banview", "banview_handle");
  $tpl->set_block("inhalt", "banviewempty", "banviewempty_handle");

  // ---> START show search section <---
  // Create empty array
  $allsearchcategories = array();
  // Define values in the serachscrollbox
  $allsearchcategories['value'] = array('nick', 'reason', 'admin', 'steamid', 'ip');
  // Define showtext in the serachscrollbox
  $allsearchcategories['view'] = array($viewtext['System'][84], $viewtext['Mysqlbans'][3], $viewtext['System'][115], $viewtext['System'][88], $viewtext['System'][87]);
  // Define serach table column's
  $allsearchcategories['table'] = array('player_name', 'ban_reason', 'banned_by', 'steam_id', 'ipaddr');
  // Start funktion show search area
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, $plugin['Systemname'], '');
  // Search filter for sql injections
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, '');

  // ---> START show page section <---
  // Check Entry Count for Nextpage-Funktion
  //$all_entrys = mysql_num_rows(mysql_query("SELECT id FROM ".$plugin['Tablename']));
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$plugin['Tablename']." ".$searcquery));
  // If current page > Count of all pages set current page to count of pages
  if ($current_site > ceil($all_entrys / $settings['mysqlbans_show_bans'])) $current_site = 1;
  // Get Startentry
  $start_entry = $current_site * $settings['mysqlbans_show_bans'] - $settings['mysqlbans_show_bans'];
  // Start funktion show entry ... to ... from ...!
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['Mysqlbans'][13], $start_entry, $all_entrys, $settings['mysqlbans_show_bans']);
  // Start funktion page-select-links
  $tpl = site_links($tpl, $all_entrys, $settings['mysqlbans_show_bans'], $current_site, $section, '', $plugin['Systemname'], $searchcat, $searchstring, '', $viewtext);
  // ---> END Show Page Section <---

  // Read bans from table
//  $sql = "SELECT * FROM ".$plugin['Tablename']." ORDER BY timestamp DESC LIMIT ".$start_entry.", ".$settings['mysqlbans_show_bans']."";

  $sql = "SELECT * FROM ".$plugin['Tablename']." ".$searcquery." ORDER BY timestamp DESC LIMIT ".$start_entry.", ".$settings['mysqlbans_show_bans']."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
    
     // Get Day, Mounth and Jear from date and replace %...% with it.
     //$bandate = dateformat($row['timestamp'], $settings['mysqlbans_date_format']);

           // Parse US/English date format into a unix timestamp and Get Day, Mounth, Jear e.t.c.
           $bandate = date($settings['mysqlbans_date_format'], strtotime($row['timestamp']));

      $bantime = array();

      // When BAN 0 Minutes set to Permanent, when not get time infos
      if ($row['ban_length'] == 0){
        $bantime['output'] = $viewtext['Mysqlbans'][12];
      }else{
        // Show Ban Length from minutes
        $bantime = get_time_from_minutes($row['ban_length'], $viewtext, 'short');
      }
      // Cut Strings when to long
      if (strlen($row['ban_reason']) > 18) $row['ban_reason'] = substr($row['ban_reason'], 0, 16) . "...";
      if (strlen($row['player_name']) > 16) $row['player_name'] = substr($row['player_name'], 0, 14) . "...";
      if (strlen($row['banned_by']) > 14) $row['banned_by'] = substr($row['banned_by'], 0, 12) . "...";
      
	 	     $row['player_name'] = BadStringFilterShow($row['player_name']);
			 $row['ban_reason'] = BadStringFilterShow($row['ban_reason']);
      
      // Define templatevars bans text in the loop.
      $tpl->set_var(array(
        "ban_date"     => $bandate,
        "ban_nick"     => $row['player_name'],
        "ban_reason"   => $row['ban_reason'],
        "ban_length"   => $bantime['output'],
        "ban_by"       => $row['banned_by'],
        "ban_id"       => $row['id']
      ));
      // Parse all templatevars to the banview block.
      $tpl->parse("banview_handle", "banview", true);
    }
  }else{ // When no entrys found...

  // Define templatevars no bans found text.
  $tpl->set_var(array("ban_no_entrys"     =>  $viewtext['Mysqlbans'][8]));
  // Parse all templatevars to the banviewempty block.
  $tpl->parse("banviewempty_handle", "banviewempty", true);
  }

}else{ // --------------------------[ If id not empty  ]------------------------


  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/mysqlbans_details.tpl.htm");
  
  $tpl->set_var(array(
  "ban_by_discription"            => $viewtext['Mysqlbans'][24],
  "ban_date_discription"          => $viewtext['Mysqlbans'][23],
  "bandetails_edit_button_text"   => $viewtext['Mysqlbans'][9],
  "bandetails_delete_button_text" => $viewtext['Mysqlbans'][10]
  ));
  

  $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE id = ".$id."";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
    
           // Parse US/English date format into a unix timestamp and Get Day, Mounth, Jear e.t.c.
           $bandate = date($settings['mysqlbans_date_format_playerview'], strtotime($row['timestamp']));

     $bantime = array();

      // When BAN 0 Minutes set to Permanent, when not get time infos
      if ($row['ban_length'] == 0){
        $bantime['output'] = $viewtext['Mysqlbans'][12];
      }else{
        // Show Ban Length from minutes
        $bantime = get_time_from_minutes($row['ban_length'], $viewtext, 'long');
      }
    
      $viewtext['Mysqlbans'][22] = str_replace('%steamid%', $row['steam_id'], $viewtext['Mysqlbans'][22]);
    
	
	         $row['player_name'] = BadStringFilterShow($row['player_name']);
			 $row['ban_reason'] = BadStringFilterShow($row['ban_reason']);
	
	
      $tpl->set_var(array(
        "ban_info_details"                => $viewtext['Mysqlbans'][22],
        "ban_nick"                        => $row['player_name'],
        "ban_steamid"                     => $row['steam_id'],
        "ban_ip"                          => $row['ipaddr'],
        "ban_reason"                      => $row['ban_reason'],
        "ban_by"                          => $row['banned_by'],
        "ban_length"                      => $bantime['output'],
        "ban_date"                        => $bandate
      ));
    
    }
    }

}

// -----------------------------------------------------------------------------

} // End if action empty


// =============================================================================

// -----------------------[ If action addban or editban  ]----------------------

if (($action == "addban") or ($action == "editban")){

  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/mysqlbans_add_edit.tpl.htm");

  // Define stationery text and set templatevars for addban and editban template
  $tpl->set_var(array(

    "ban_info_general"         => $viewtext['System'][71],
    "ban_apply_button_text"    => $viewtext['System'][7],
    "ban_apply_button_text"    => $viewtext['System'][7],
  ));
  
  // Define Times from Minutes
  $btime = Define_time_from_minutes ($viewtext);

}
// -----------------------------------------------------------------------------

// -----------------------------[ If action addban ]----------------------------

if ($action == "addban"){

  $tpl->set_var(array(
    "action"                          => "execaddban",
    "applyid"                         => '',
    "ban_header_action_text"          => $viewtext['Mysqlbans'][11],
    "ban_nick"                        => '',
    "ban_steamid"                     => '',
    "ban_ip"                          => '',
    "ban_reason"                      => ''
  ));
  
  // Set Selected Bantimen (0 = Permanent)
  $row = array();
  $row['ban_length'] = 0;
  // Call Funktion fill Bantime Scrollbox
  $tpl = bantime_scrollbox($tpl, $btime, $viewtext, $viewtext, $row);
}
// -----------------------------------------------------------------------------

// -----------------------------[ If action editban ]---------------------------

if ($action == "editban"){

$sql = "SELECT * FROM ".$plugin['Tablename']." WHERE id = ".$id."";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

	
	 $row['player_name'] = BadStringFilterShow($row['player_name']);
     $row['ban_reason'] = BadStringFilterShow($row['ban_reason']);
	
      $tpl->set_var(array(
        "action"                          => "execeditban",
        "applyid"                         => '&id='.$id.'',
        "ban_header_action_text"          => $viewtext['Mysqlbans'][9],
        "ban_nick"                        => $row['player_name'],
        "ban_steamid"                     => $row['steam_id'],
        "ban_ip"                          => $row['ipaddr'],
        "ban_reason"                      => $row['ban_reason']
      ));
      // Call Funktion fill Bantime Scrollbox
      $tpl = bantime_scrollbox($tpl, $btime, $viewtext, $viewtext, $row);
    }
  }else{
    // Show ban not found window
    $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Mysqlbans'][21], $viewtext['System'][4], "index.php?section=plugins&plugin=".$plugin['Systemname']."");
  }
}
// -----------------------------------------------------------------------------


// --------------------[ If action execaddban or execeditban]-------------------

if (($action == "execaddban") or ($action == "execeditban")){
// Get entry from html form
$name    = (isset($_POST['bannick'])) ? $_POST['bannick']:'';
$steamid = (isset($_POST['bansteamid'])) ? $_POST['bansteamid']:'';
$reason  = (isset($_POST['banreason'])) ? $_POST['banreason']:'';
$length  = (isset($_POST['banlength'])) ? $_POST['banlength']:'';
$ip      = (isset($_POST['banip'])) ? $_POST['banip']:'';

$name   = BadStringFilterShow($name );
$reason = BadStringFilterShow($reason);

// Filter Spaces
$steamid = str_replace(' ','',$steamid);
$ip = str_replace(' ','',$ip);

}

// -----------------------------------------------------------------------------

// ---------------------------[ If action execaddban ]--------------------------

if ($action == "execaddban"){

// Check Duplicate Steamid entry
$sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (steam_id = '".$steamid."') AND (steam_id <> '')";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    // Replace %steamid% with SteamID
    $viewtext['Mysqlbans'][18] = str_replace('%steamid%', $steamid, $viewtext['Mysqlbans'][18]);
    // Show duplicate steamid entry window
    $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Mysqlbans'][18], $viewtext['System'][4], "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=addban");

  }else{ // when not duplicate steamid entry....

    // Check fields empty
    if (($name == '') or (($steamid == '') AND ($ip == '')) or ($reason == '') or($length == '')){
    // Show fill out all fields window
      $tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][25], $viewtext['System'][4], "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=addban");
    }else{
    // Insert BAN entry to Database
      sqlexec("INSERT INTO ".$plugin['Tablename']." SET
        steam_id=".((strlen($steamid) > 0) ? "'".$steamid."'" : "NULL").",
        player_name='".$name."',
        ipaddr = ".((strlen($ip) > 0) ? "'".$ip."'" : "NULL").",
        ban_length = '".$length."',
        ban_reason = '".$reason."',
        banned_by = '".$usercheck['name']."',
        timestamp = '".date("Y-m-d H:m:s", $time)."'");

       // Replace %steamid% with SteamID
       $viewtext['Mysqlbans'][17] = str_replace('%steamid%', $steamid, $viewtext['Mysqlbans'][17]);
        // Show successfully added window
        $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Mysqlbans'][17], $viewtext['System'][4], "index.php?section=plugins&plugin=".$plugin['Systemname']."");
    }
  }
}

// -----------------------------------------------------------------------------


// ---------------------------[ If action execeditban ]-------------------------

if ($action == "execeditban"){

 if (($name == '') or (($steamid == '') AND ($ip == '')) or($reason == '') or($length == '')){
   // Show fill out all fields window
   $tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][25], $viewtext['System'][4], "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=editban&id=".$id."");
 }else{
    // Check is already banned with a another entry.
   $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (id <> '".$id."') and ((steam_id = '".$steamid."') AND (steam_id <> ''))";
   $result = mysql_query($sql) OR die(mysql_error());
   // Check is already banned with a another entry
   if(mysql_num_rows($result)){
     // Replace %steamid% with SteamID
     $viewtext['Mysqlbans'][20] = str_replace('%steamid%', $steamid, $viewtext['Mysqlbans'][20]);
     // Show "is already banned with a another entry" window
     $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Mysqlbans'][20], $viewtext['System'][4], "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=editban&id=".$id."");
   }else{
      // Update Databaseentry
      sqlexec("UPDATE ".$plugin['Tablename']." SET
        steam_id=".((strlen($steamid) > 0) ? "'".$steamid."'" : "NULL").",
        player_name='".$name."',
        ipaddr = ".((strlen($ip) > 0) ? "'".$ip."'" : "NULL").",
        ban_length = '".$length."',
        ban_reason = '".$reason."'
        WHERE id = '".$id."'");
      // Show successfully edit window
      $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Mysqlbans'][19], $viewtext['System'][4], "index.php?section=plugins&plugin=".$plugin['Systemname']."");
    }
  }
}
// -----------------------------------------------------------------------------


// ----------------------[ If action delban or execdelban ]---------------------

if (($action == "delban") or ($action == "execdelban")){
  $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE id = '".$id."'";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
      $viewtext['Mysqlbans'][25] = str_replace('%steamid%', $row['steam_id'], $viewtext['Mysqlbans'][25]);
      $viewtext['Mysqlbans'][26] = str_replace('%steamid%', $row['steam_id'], $viewtext['Mysqlbans'][26]);
    }
  }
}
// -----------------------------------------------------------------------------

// -----------------------------[ If action delban ]----------------------------
if ($action == "delban"){
$tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Mysqlbans'][25], $viewtext['System'][2], $viewtext['System'][1], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execdelban&page=".$current_site."&id=".$id."" );
}
// -----------------------------------------------------------------------------

// ---------------------------[ If action execdelban ]--------------------------
if ($action == "execdelban"){
sqlexec("DELETE FROM ".$plugin['Tablename']." WHERE id = ".$id."");

$tpl = Infobox($tpl, $plugin['Name'], $viewtext['Mysqlbans'][26], $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."");
}
// -----------------------------------------------------------------------------


// ---------------------------[ If action importbans ]--------------------------
if ($action == "importban"){

 // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/mysqlbans_import.tpl.htm");

  // Define stationery text and set templatevars for import template
  $tpl->set_var(array(

    "ban_header_action_text"   => $viewtext['Mysqlbans'][37],
    "ban_import_info_file"     => $viewtext['Mysqlbans'][35],
    "ban_import_info_sourcebans"  => $viewtext['Mysqlbans'][36],
    "ban_import_file_discription" => $viewtext['System'][121],
    "ban_import_sourcebans_discription" => $viewtext['Mysqlbans'][38],
    "ban_import_button_text" =>  $viewtext['Mysqlbans'][37],
    "ban_import_bannedby_discription" => $viewtext['Mysqlbans'][39],
    "ban_import_bannedby" => $usercheck['name'],
	"ban_import_hostname_discription" => $viewtext['System'][57],
	"ban_import_hostname" => "localhost",
	"ban_import_port_default_discription" => $viewtext['System'][62],
	"ban_import_port_discription" => $viewtext['System'][58],
	"ban_import_port" => "3306",
	"ban_import_tableprefix_default_discription" => $viewtext['System'][62],
	"ban_import_tableprefix_discription" => $viewtext['System'][63],
	"ban_import_tableprefix" => "sb_",
    "ban_import_database_discription" => $viewtext['System'][59],
	"ban_import_username_discription" => $viewtext['System'][60],
	"ban_import_password_discription" => $viewtext['System'][61],
	"ban_import_config_data_discription" => $viewtext['Mysqlbans'][1]
  ));
}

// -----------------------------------------------------------------------------

// ---------------------------[ If action exportbans ]--------------------------
if ($action == "exportban"){



 // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/mysqlbans_export.tpl.htm");

$banexport = array();


$sql = "SELECT * FROM ".$plugin['Tablename']." WHERE ban_length = '0'";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
      if($row['steam_id'] <> "") $banexport['steamid'][] = $row['steam_id'];
      if($row['ipaddr'] <> "") $banexport['ip'][] = $row['ipaddr']; 
    }
  }
  
  $_SESSION['baninfo'] = array();
  
  $_SESSION['baninfo'] = $banexport;
  


  // Define stationery text and set templatevars for import template
  $tpl->set_var(array(

    "ban_header_action_text"   => $viewtext['Mysqlbans'][6],
    "ban_export_info_file"     => $viewtext['Mysqlbans'][16],
    "ban_export_file_discription" => $viewtext['System'][121],
    "ban_export_button_text" =>  $viewtext['Mysqlbans'][6],
    "ban_export_file_steamid"   => $viewtext['Mysqlbans'][14],
    "ban_export_file_ip"   => $viewtext['Mysqlbans'][15]
  ));
}
// -----------------------------------------------------------------------------

// ----------------------[ If action execimportsourcebans ]---------------------
if ($action == "importsourcebans"){
  
  // Get optionbox selction
  $importart = (isset($_POST['importart'])) ? $_POST['importart']:'1';
  
  if ($importart == 1){
  // Get inputbox data
    $sbservername = (isset($_POST['hostname'])) ? $_POST['hostname']:'';
    $sbdbport = (isset($_POST['port'])) ? $_POST['port']:'3306';
    $sbdbname = (isset($_POST['database'])) ? $_POST['database']:'';
    $sbdbusername = (isset($_POST['username'])) ? $_POST['username']:'';
    $sbdbpassword = (isset($_POST['password'])) ? $_POST['password']:'';  
  }
  
  // Create Array
  $sbbans = array();
  // Close mysql connection
  mysql_close();
  // Connect to sourcebans database
  $result = MySQLConnect($sbservername.':'.$sbdbport, $sbdbusername, $sbdbpassword, $sbdbname, '0');
 // check connection
  if ($result === True){
  
    //$sql = "SELECT * FROM ".$table."_bans";
    //$result = mysql_query($sql) OR die(mysql_error());
  
  
    // write "connection ok msg" to textbox msg
    $msg = str_replace('%msg%', $viewtext['System'][32], $viewtext['Mysqlbans'][5]);
    // Read sourcebans database and add all bans to array
    $sbbans = SourcebansToArray($sbtable);
  }else{
    // write "connection error msg" to textbox msg
    $viewtext['System'][56] = str_replace('%error%', $result, $viewtext['System'][56]);
    $msg = str_replace('%msg%', $viewtext['System'][56], $viewtext['Mysqlbans'][5]);
  }
  // Close sourcebans mysql connection
  @mysql_close();
  // Connect to webadmin database
  MySQLConnect($servername.':'.$dbport, $dbusername, $dbpassword, $dbname, '1');
  // set count's startvalue
  $insert = 0;
  $double= 0;
  // count found SB-Bans
  $found = count($sbbans);
  // write found sb-bans count to textbox msg
  if ($found == 1){
    $msg = $msg.'<br>'.$viewtext['Mysqlbans'][27];
  }else{
    $msg = $msg.'<br>'.str_replace('%num%', $found, $viewtext['Mysqlbans'][28]);
  }
  $msg = $msg.'<br>---';
  // write "write entrys to MySql-Bans" to textbox msg
  $msg = $msg.'<br>'.$viewtext['Mysqlbans'][2];
  // List sourcebans bans-array
  foreach($sbbans as $key => $value) {
    //Select steamid in SQL-Bans Database
    $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE steam_id = '".$sbbans[$key]['steam']."'";
    $result = mysql_query($sql) OR die(mysql_error());
    // if found steam:
    if(mysql_num_rows($result)){
      // count duplicated entry's
      $double++;
    // if doun't found steamid:
    }else{
       // convert seconds to minutes and round result
       $length = round($sbbans[$key]['length'] / 60);
       // covert unix timestamp to US/English date format
       $date = date("Y-m-d H:m:s", $sbbans[$key]['date']);
       // Insert BAN to MySQL-Bans database

       $steam_id = $sbbans[$key]['steam'];
       $ipaddr = $sbbans[$key]['ip'];
       sqlexec("INSERT INTO mysql_bans SET
            steam_id =".((strlen($steamid) > 0) ? "'".$steamid."'" : "NULL").",
            player_name ='".$sbbans[$key]['name']."',
            ipaddr =".((strlen($ipaddr) > 0) ? "'".$ipaddr."'" : "NULL").",
            ban_length ='".$length."',
            ban_reason ='".$sbbans[$key]['reason']."',
            banned_by ='".$sbbans[$key]['admin']."',
            timestamp ='".$date."'");
            
       // count add entry's
       $insert++;
    }
  }
  // write insert count to textbox msg
  if ($insert == 1){
    $msg = $msg.'<br>'.$viewtext['Mysqlbans'][33].'<br>';
  }else{
    $msg = $msg.'<br>'.str_replace('%num%', $insert, $viewtext['Mysqlbans'][34]);
  }
  // write duplicated count to textbox msg
  if ($double == 1){
    $msg = $msg.'<br>'.$viewtext['Mysqlbans'][31];
  }else{
    $msg = $msg.'<br>'.str_replace('%num%', $double, $viewtext['Mysqlbans'][32]);
  }
  $msg = $msg.'<br>---<br>'.$viewtext['System'][33].'<br>';
  // Parse infox with msg out
 
$tpl = Infoboxselect($tpl, $plugin['Name'].' --> '.$viewtext['Mysqlbans'][36], $msg, $viewtext['System'][4], $viewtext['System'][3], 'javascript:history.back()', "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."" );

 
      //$tpl = Infobox($tpl, $plugin['Name'].' --> '.$viewtext['Mysqlbans'][36], $msg, $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."");
}
   // -----------------------------------------------------------------------------

// -------------------------[ If action execimportfile ]------------------------
if ($action == "execimportfile"){

$bannedby    = (isset($_POST['bannedby'])) ? $_POST['bannedby']:$usercheck['name'];

$data = uploadfile('datei', $viewtext);
$bans = read_banfile($data['file_content'], $bannedby, 'Unknown', 'Imported ban from banfile', $time, $viewtext);

$insert = 0;
$double = 0;

for ($x = 0; $x <= count($bans['entry']['steam'])-1; $x++){

if (array_search($x, $bans['dubblentrys']) == False){

 $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE steam_id = ('".$bans['entry']['steam'][$x]."') OR (ipaddr = '".$bans['entry']['ip'][$x]."')";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){

  $double++;
  }else{
  
   if ($bans['entry']['steam'][$x] == '0') $bans['entry']['steam'][$x] = '';
 if ($bans['entry']['ip'][$x] == '0') $bans['entry']['ip'][$x] = '';

	  $steam_id = $bans['entry']['steam'][$x];
	  $ipaddr = $bans['entry']['ip'][$x];
          sqlexec("INSERT INTO ".$plugin['Tablename']." SET
            steam_id =".((strlen($steamid) > 0) ? "'".$steamid."'" : "NULL").",
            player_name ='".$bans['entry']['name'][$x]."',
            ipaddr =".((strlen($ipaddr) > 0) ? "'".$ipaddr."'" : "NULL").",
            ban_length ='".$bans['entry']['banlenght'][$x]."',
            ban_reason ='".$bans['entry']['reason'][$x]."',
            banned_by ='".$bans['entry']['admin'][$x]."',
            timestamp ='".$bans['entry']['date'][$x]."'");
            
    $insert++;
  }
}
}

$bans['msg'] = $bans['msg'].'<br>---';

if ($insert == 1){
  $bans['msg'] = $bans['msg'].'<br>'.$viewtext['Mysqlbans'][33].'<br>';
}else{
  $bans['msg'] = $bans['msg'].'<br>'.str_replace('%num%', $insert, $viewtext['Mysqlbans'][34]);
}

if ($double == 1){
  $bans['msg'] = $bans['msg'].'<br>'.$viewtext['Mysqlbans'][31];
}else{
  $bans['msg'] = $bans['msg'].'<br>'.str_replace('%num%', $double, $viewtext['Mysqlbans'][32]);
}

$data['msg'] = $data['msg'].'<br>---<br>'.$bans['msg'];

$data['msg'] = $data['msg'].'<br>---<br>'.$viewtext['System'][33];

$tpl = Infobox($tpl, 'Import Bans', $data['msg'], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'');

// '<pre>'.print_r($bans, true).'</pre>'
}

// -----------------------------------------------------------------------------

} // End if Area = ''

//==============================================================================
//--------------------------=[Section Plugnin Settings]=------------------------
//==============================================================================


// ---> BEGIN Create Table for MySQL Banning Plugin
if ($area == 'createtable'){

// New Tablestructure by "naris"
  sqlexec("CREATE TABLE ".$plugintable."(
  id int(11) auto_increment,
  steam_id varchar(32),
  player_name varchar(65),
  ipaddr varchar(24),
  ban_length int(1) default '0',
  ban_reason varchar(100),
  banned_by varchar(65),
  banned_by_id varchar(32),
  timestamp timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY steam_id (steam_id),
  UNIQUE KEY ipaddr (ipaddr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3");
  
}
//------------------------------------------------------------------------------

// ---> BEGIN Delete Table for MySQL Banning Plugin
if ($area == 'deltable'){

sqlexec("DROP TABLE IF EXISTS ".$plugintable."");


}
//------------------------------------------------------------------------------

// ---> BEGIN to show the Specific Settings from the Settings Table.

if ($area == 'readspecificsettings'){

// Set Specific Settings Template file
$tpl->set_file("mysqlbans_specific_settings", "templates/plugins/mysqlbans_specific_settings.tpl.htm");

// Settingsaray from Settingstable and Description sending to Template var.
$tpl->set_var(array(
"ban_specific_settings_show_bans_discription"  => $viewtext['Mysqlbans'][0],
"ban_specific_settings_show_bans"  => $settings['mysqlbans_show_bans'],
"ban_specific_settings_dateformat_discription"  => $viewtext['Mysqlbans'][7],
"ban_specific_settings_dateformat"  => $settings['mysqlbans_date_format'],
"ban_specific_settings_dateformat_playerview_discription"  => $viewtext['Mysqlbans'][40],
"maprate_specific_settings_date_format_playerview"  => $settings['mysqlbans_date_format_playerview']
));

// Parse mysqlbans_specific_settings.tpl.htm to plugins_settings.tpl.htm
$tpl->set_var(array("specific_settings" => $tpl->parse("out", "mysqlbans_specific_settings"),));

}

//------------------------------------------------------------------------------

// ---> BEGIN to save the Specific Settings in the Settings Table.

if ($area == 'savepecificsettings'){

// Read inputfields from html template (Array must be $plugin['...']).
$plugin['show_bans']   = (isset($_POST['showbans'])) ? $_POST['showbans']:'';
$plugin['date_format']   = (isset($_POST['dateformat'])) ? $_POST['dateformat']:'';
$plugin['date_dateformat_playerview'] = (isset($_POST['dateformat_playerview'])) ? $_POST['dateformat_playerview']:'';

// Checkt type is Integer, when not or empty set to "0".
if(isset($plugin['show_bans'])) settype($plugin['show_bans'], "integer");

// When var is "0" set to Default
if ($plugin['show_bans'] == 0) $plugin['show_bans'] = 30;
// When var is empty set to default.
if ($plugin['date_format'] == '') $plugin['date_format'] = 'm-d-y';
if ($plugin['date_dateformat_playerview'] == '') $plugin['date_dateformat_playerview'] = 'm-d-y H:i:s';

// Save var in settings-table.
sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['show_bans']."' WHERE Name = 'mysqlbans_show_bans'");
sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['date_format']."' WHERE Name = 'mysqlbans_date_format'");
sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['date_dateformat_playerview']."' WHERE Name = 'mysqlbans_date_format_playerview'");

}
//==============================================================================
//==============================================================================
//==============================================================================

/*           echo'<pre>';
           print_r($banexport);
           echo'</pre>';*/


?>
