<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* advertisements.php                              *
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
$plugin['Version'] = (Version)
$plugin['Author'] = (Author)
$plugin['Authorlink'] = (Link to Author Profile)
$plugin['Tablename'] = (Plugin MySQL Tablename)
$plugin['Support'] = (Link to Support)
$plugin['Showplug'] = (show plugin in navi. 0 = Hide / 1 = Show)


### Variables from Plugin Specific Settings some can used here:

// ----

### ============================================================================
*/

//echo "<pre>";
//print_r($Serverids);
// echo "</pre>";

if ($area == ''){ // Begin if Area = ''
  
  // Define text for all mysqlbans templatefiles
  $tpl->set_var(array(
	"advertisements_header_text"          => $plugin['Name'],
	"advertisements_text_discription" => $viewtext['System'][118],
	"advertisements_visible_discription" => $viewtext['Advertisements'][2],
    "advertisements_type_discription" => $viewtext['System'][66],
    "advertisements_game_discription" => $viewtext['System'][67],
    "section"                  => $section,
    "plugin"                   => $plugin['Systemname'],
    "id"                       => $id,
    "page"                     => $current_site
  ));


// ---------------------[ If action "empty" show maintable]---------------------
if ($action == ""){


    // ---> START move entry section <---
    // Read move variable from adresslist
    $move = (isset($_GET['move'])) ? $_GET['move']:'';
    // Check if move varable is set
    if (($move == "up") OR ($move == "down")){
      // Select move up or move down
      if ($move == "up") $new_id = $id +1;
      if ($move == "down") $new_id = $id -1;
      //Send SQL-Query
      sqlexec("UPDATE ".$plugin['Tablename']." SET id=-1 WHERE id='".$id."'");
      sqlexec("UPDATE ".$plugin['Tablename']." SET id='".$id."' WHERE id='".$new_id."'");
      sqlexec("UPDATE ".$plugin['Tablename']." SET id='".$new_id."' WHERE id=-1");
    }
    // ---> END move entry section <---

  // Define text for all advertisements templatefiles when action = empty
  $tpl->set_var(array(
    "advertisements_options_discription" => $viewtext['System'][65],
    "advertisements_id_discription" => $viewtext['System'][68],
    "advertisements_arrowup_pic_infotext" => $viewtext['System'][75],
    "advertisements_arrowdown_pic_infotext" => $viewtext['System'][76],
    "advertisements_edit_pic_infotext" =>$viewtext['Advertisements'][11],
    "advertisements_delete_pic_infotext" => $viewtext['Advertisements'][12],
	"add_advertisements_button_text" => $viewtext['Advertisements'][13],
	"advertisements_no_entrys"       => $viewtext['System'][50]
    ));

  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/advertisements.tpl.htm");

  // Define blocks to schow entrys in the loop's
  $tpl->set_block("inhalt", "advertisementsview", "advertisementsview_handle");
  $tpl->set_block("inhalt", "advertisementsviewempty", "advertisementsviewempty_handle");

  // ---> START show search section <---
  // Create empty array
  $allsearchcategories = array();
  // Define values in the serachscrollbox
  $allsearchcategories['value'] = array('advertisement', 'id');
  // Define showtext in the serachscrollbox
  $allsearchcategories['view'] = array($viewtext['System'][118], $viewtext['System'][68]);
  // Define serach table column's
  $allsearchcategories['table'] = array('text', 'id');
  // Start funktion show search area
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, $plugin['Systemname'], '');
  // Search filter for sql injections
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, '');

  // ---> START show page section <---
  // Check Entry Count for Nextpage-Funktion
  //$all_entrys = mysql_num_rows(mysql_query("SELECT id FROM ".$plugin['Tablename']));
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$plugin['Tablename']." ".$searcquery));
  // If current page > Count of all pages set current page to count of pages
  if ($current_site > ceil($all_entrys / $settings['advertisements_show_advertisements'])) $current_site = 1;
  // Get Startentry
  $start_entry = $current_site * $settings['advertisements_show_advertisements'] - $settings['advertisements_show_advertisements'];
  // Start funktion show entry ... to ... from ...!
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['Advertisements'][10], $start_entry, $all_entrys, $settings['advertisements_show_advertisements']);
  // Start funktion page-select-links
  $tpl = site_links($tpl, $all_entrys, $settings['advertisements_show_advertisements'], $current_site, $section, '', $plugin['Systemname'], $searchcat, $searchstring, '', $viewtext);
  // ---> END Show Page Section <---

  //Set startcount of errow handlecount
  $handle_count = $start_entry;

  $sql = "SELECT * FROM ".$plugin['Tablename']." ".$searcquery." ORDER BY id DESC LIMIT ".$start_entry.", ".$settings['advertisements_show_advertisements']."";
  //$sql = "SELECT * FROM ".$plugin['Tablename']." ORDER BY ID ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

        // ++ handlecount for errows
        $handle_count++;
		
    // Select "Type" Text
    switch($row['type']){
      case "S":
        $row['type'] = $viewtext['Advertisements'][3];
        break;
      case "T":
        $row['type'] = $viewtext['Advertisements'][4];
        break;
      case "H":
        $row['type'] = $viewtext['Advertisements'][5];
        break;
      case "C":
        $row['type'] = $viewtext['Advertisements'][6];
        break;
      default:
        $row['type'] = $viewtext['System'][69];
        break;
    }
    // Select "Visible by" Text
    switch($row['flags']){
      case "a":
        $row['flags'] = $viewtext['Advertisements'][7];
        break;
      case "none":
        $row['flags'] = $viewtext['System'][91];
        break;
      default:
        $row['flags'] = $viewtext['Advertisements'][8];
        break;
    }


// Start to get Mod Icon from Mod Database
$tpl = PluginGameIcon($tpl, $table, 'advert', $row['game'], $handle_count, 'All');

// If serach string nothing: Call function to show and manage arrows
if ($searcquery == "") $tpl = ParseArrows($tpl, $start_entry, $all_entrys, $settings['advertisements_show_advertisements'], $handle_count,$current_site, $row['id'], $viewtext);

      // Replace { }  to asci code!
	  //$row['text'] = str_replace('{', '&#123;', $row['text']);
	  //$row['text'] = str_replace('}', '&#125;', $row['text']);
	  
	    $row['text'] = BadStringFilterShow($row['text']);

 // Templatevars advertisement text to the loop.
      $tpl->set_var(array(
        "advertisements_id" => $row['id'],
        "advertisements_text" => $row['text'],
        "advertisements_visible" => $row['flags'],
        "advertisements_type" => $row['type']
      ));
      // Parse all templatevars to the advertisementsview block.
      $tpl->parse("advertisementsview_handle", "advertisementsview", true);


    }
    }else{
      // Parse all templatevars to the advertisementsviewempty block.
      $tpl->parse("advertisementsviewempty_handle", "advertisementsviewempty", true);
    }

}

// -------------------[ If action addads or editads show add/edit window]----------------------

if (($action == "addads") or ($action == "editads")){


  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/advertisements_add_edit.tpl.htm");
  $tpl->set_file("colorinfo", "templates/plugins/advertisements_colorinfo.tpl.htm");
  
  // Set Templatevars for colorinfo
  $tpl->set_var(array("advertisements_not_aviable_text"   => $viewtext['System'][127]));
    
  // Define block to schow last edit entry
  $tpl->set_block("inhalt", "lastedit", "lastedit_handle");

  // Set Templatevars for addads and editads form
      $tpl->set_var(array(
        
		
		"advertisements_noadmins_info"      => $viewtext['Advertisements'][7],
        "advertisements_onlyadmins_info"    => $viewtext['Advertisements'][8],
        "advertisements_all_info"           => $viewtext['System'][91],
        "advertisements_say_info"           => $viewtext['Advertisements'][14],
    	"advertisements_hint_info"          => $viewtext['Advertisements'][15],
	    "advertisements_center_info"        => $viewtext['Advertisements'][16],
	    "advertisements_top_info"           => $viewtext['Advertisements'][17],
		"advertisements_not_aviable_text"   => $viewtext['System'][127],
        "advertisements_info_general"       => $viewtext['System'][71],
        "add_edit_das_button_text"          => $viewtext['System'][7],
        "back_ads_button_text"              => $viewtext['System'][4],
        "checkedsay"                        => '',
        "checkedhint"                       => '',
        "checkedcenter"                     => '',
        "checkedtop"                        => '',
        "checkedall"                        => '',
        "checkednoadmins"                   => '',
        "checkedonlyadmins"                 => '',
		"colorhelp" 						=> $tpl->parse("out", "colorinfo")
		
		));
}

//

// -----------------------------[ If action addads ]----------------------------

if ($action == "addads") {

  // Call Function to Show Mod-Gamelist
  $tpl = PluginGameScrollbox($tpl, $table."_mods", "advert", "");
        // Set Templatevars for addads form
        $tpl->set_var(array(
          "advertisements_header_action_text" => $viewtext['Advertisements'][13],
          "action"                            => 'execaddads',
          "checkedsay"                        => 'checked',
          "checkedall"                        => 'checked',
          "advertisements_text"               => ''
        ));
  
}

// ----------------------------[ If action editads ]----------------------------

if ($action == "editads") {

 // Read Advertment Entry
 $row = ReadAdvertEntry($tpl, $viewtext, $plugin['Name'], $plugin, $id);
  if ($row <> False){

  // Call Function to Show Mod-Gamelist
  $tpl = PluginGameScrollbox($tpl, $table."_mods", "advert", $row['game']);
  
   // Parse US/English date format into a unix timestamp and Get Day, Mounth, Jear e.t.c.
   $row['time'] = date($settings['advertisements_date_format'], strtotime($row['time']));
   
   $row['text'] = BadStringFilterShow($row['text']);
   
  // Add text Templatevars
  $tpl->set_var(array(
    "advertisements_date"                 => $row['time'],
    "advertisements_user"                 => $row['name'],
    "advertisements_lastedit_discription" => $viewtext['System'][77],
    "advertisements_header_action_text"   => $viewtext['Advertisements'][11],
    "action"                              => 'execeditads',
    "advertisements_text"                 => $row['text']
  ));

  // Parse lastedit block
  $tpl->parse("lastedit_handle", "lastedit", true);
  
    // Select Type and add to Templatevars
    switch($row['type']){
      case "S":
        $tpl->set_var(array("checkedsay" => 'checked'));
        break;
      case "T":
         $tpl->set_var(array("checkedtop" => 'checked'));
        break;
      case "H":
        $tpl->set_var(array("checkedhint" => 'checked'));
        break;
      case "C":
        $tpl->set_var(array("checkedcenter" => 'checked'));
        break;
      default:
        $tpl->set_var(array("checkedsay" => 'checked'));
        break;
    }
          // Select Visible and add to Templatevars

    switch($row['flags']){
      case "a":
        $tpl->set_var(array("checkednoadmins" => 'checked'));
        break;
      case "none":
        $tpl->set_var(array("checkedall" => 'checked'));
        break;
      case "":
        $tpl->set_var(array("checkedonlyadmins" => 'checked'));
        break;
      default:
        $tpl->set_var(array("checkedall" => 'checked'));
        break;
    }
  }
}

// ------------------[ If action execeditads or execeditads ]-------------------


if (($action == "execaddads") OR ($action == "execeditads")){
   
  // Read html inputs
  $adstext  = (isset($_POST['text'])) ? $_POST['text']:'';
  $adstype  = (isset($_POST['adstype'])) ? $_POST['adstype']:'';
  $adsflag = (isset($_POST['adsflag'])) ? $_POST['adsflag']:'';
  $adsgame  = (isset($_POST['adsgame'])) ? $_POST['adsgame']:'';
  
  $adstext = BadStringFilterSave($adstext);
  
  // Replace forbiten letters
  //$adstext = str_replace("'", "", $adstext);
  
  // Check textbox input are empty
  if ($adstext == ""){
    // Show Infobox
	$tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][25], $viewtext['System'][4], 'javascript:history.back()'); 
	$go = 0;
  }else{ // If not empty
    
	// Analyse checks from optionsbox and add replace variable
    switch($adstype){
      case "say":
        $adstype = "S";
        break;
      case "top":
        $adstype = "T";
        break;
      case "hint":
        $adstype = "H";
        break;
      case "center":
        $adstype = "C";
        break;
      default:
        $adstype = "S";
        break;
    }	
	switch($adsflag){
      case "all":
        $adsflag = "none";
        break;
      case "noadmins":
        $adsflag = "a";
        break;
      case "onlyadmins":
        $adsflag = "";
        break;
      default:
        $adsflag = "none";
        break;
    }	
    $go = 1;  
  }
}
  
  
// ----------------------------[ If action exeaddads ]--------------------------
if (($action == "execaddads") and ($go == 1)){

  // Get next free id
  $checkid = 0;
  $sql = "SELECT id FROM ".$plugin['Tablename']." ORDER BY id ASC";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
	 $checkid++;
	 $adsid = $checkid + 1;
      if ($row['id'] != $checkid){
  	    $adsid = $checkid;
		break;
      }
    }
  }else{
    $adsid = 1;
  }
  
   sqlexec("INSERT INTO ".$plugin['Tablename']." SET id='".$adsid."', text='".$adstext."', type='".$adstype."', flags='".$adsflag."', game='".$adsgame."', name='".$usercheck['name']."'", mysql_real_escape_string($adstext));
  // Replace %adstext% from langeuagefile to dvertisement-text
  $viewtext['Advertisements'][18] = str_replace('%adstext%', $adstext, $viewtext['Advertisements'][18]);
  // Replace \' to '
  $viewtext['Advertisements'][18] = str_replace("\'", "'", $viewtext['Advertisements'][18]);
  // Show Infobox
  $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Advertisements'][18], $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."");

}

// ---------------------------[ If action execeditads ]--------------------------
if (($action == "execeditads")  and ($go == 1)){
 // Update Entry
 
 sqlexec("UPDATE ".$plugin['Tablename']." SET text='".$adstext."', type='".$adstype."', flags='".$adsflag."', game='".$adsgame."' ,name='".$usercheck['name']."' WHERE id = '".$id."'", mysql_real_escape_string($adstext));
 // Show Infobox
 $tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][74], $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."");
}

// ---------------------------[ If action delads ]--------------------------
if ($action == "delads"){
  // Read Advertment Entry
  $row = ReadAdvertEntry($tpl, $viewtext, $plugin['Name'], $plugin, $id);
  if ($row <> False){
    // Replace %adstext% from langeuagefile to dvertisement-text
    $viewtext['Advertisements'][19] = str_replace('%adstext%', $row['text'], $viewtext['Advertisements'][19]);
    // Show Select box Yes/No
    $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Advertisements'][19], $viewtext['System'][2], $viewtext['System'][1],  "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execdelads&page=".$current_site."&id=".$id."" );
  }
}

// ---------------------------[ If action execdelads ]--------------------------
if ($action == "execdelads"){
  // Read Advertment Entry
  $row = ReadAdvertEntry($tpl, $viewtext, $plugin['Name'], $plugin, $id);
  if ($row <> False){
  // Delete Entry
  sqlexec("DELETE FROM ".$plugin['Tablename']." WHERE id = ".$id."");
  // Show Infobox
  $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Advertisements'][20], $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."");
  }
}

//------------------------------------------------------------------------------

} // End if Area = '

//==============================================================================
// ----------------------[ Plugin specific functions  ]-------------------------
//==============================================================================

// Function to read Advertisment Entry.
function ReadAdvertEntry($tpl, $viewtext, $boxheader, $plugin, $id){

$sql = "SELECT * FROM ".$plugin['Tablename']." WHERE id = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
       $return = $row;
    }
  }else{
    $viewtext['System'][73] = str_replace('%id%', $id, $viewtext['System'][73]);
    $tpl = Infobox($tpl, $boxheader, $viewtext['System'][73], $viewtext['System'][4], 'javascript:history.back()');
    return false;
  }

  return $return;
}


//==============================================================================
//--------------------------=[Section Plugnin Settings]=------------------------
//==============================================================================

// ---> BEGIN Create Table for ChatLog Extended Plugin
if ($area == 'createtable'){

  sqlexec("CREATE TABLE ".$plugintable."(
     id int(11) NOT NULL,
     type text NOT NULL,
     text text character set utf8 collate utf8_swedish_ci NOT NULL,
     flags text NOT NULL,
     game text NOT NULL,
     name text NOT NULL,
     time timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
     PRIMARY KEY  (id))
     ENGINE=MyISAM DEFAULT CHARSET=latin1;");
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
  $tpl->set_file("advertisements_specific_settings", "templates/plugins/advertisements_specific_settings.tpl.htm");

  // Settingsaray from Settingstable and Description sending to Template var.
  $tpl->set_var(array(
     "advertisements_specific_settings_show_advertisements_discription"  => $viewtext['Advertisements'][0],
     "advertisements_specific_settings_show_advertisements"  => $settings['advertisements_show_advertisements'],
	 "advertisements_specific_settings_dateformat_discription"  => $viewtext['Advertisements'][1],
     "advertisements_specific_settings_dateformat"  => $settings['advertisements_date_format']
	));
	
  // Parse advertisements_specific_settings.tpl.htm to plugins_settings.tpl.htm
  $tpl->set_var(array("specific_settings" => $tpl->parse("out", "advertisements_specific_settings"),));
}

//------------------------------------------------------------------------------

// ---> BEGIN to save the Specific Settings in the Settings Table.

if ($area == 'savepecificsettings'){

  // Read inputfields from html template (Array must be $plugin['...']).
  $plugin['showadvertisements']   = (isset($_POST['showadvertisements'])) ? $_POST['showadvertisements']:'';
  $plugin['dateformat']   = (isset($_POST['dateformat'])) ? $_POST['dateformat']:''; 

  // Checkt type is Integer, when not or empty set to "0".
  if(isset($plugin['showadvertisements'])) settype($plugin['showadvertisements'], "integer");

  // When var is "0" set to Default
  if ($plugin['showadvertisements'] == 0) $plugin['showadvertisements'] = 30;

  // When var is empty set to default.
  if ($plugin['showadvertisements'] == '') $plugin['showadvertisements'] = 30;
  if ($plugin['dateformat'] == '') $plugin['dateformat'] = 'm-d-Y H:i:s';

  // Save var in settings-table.
  sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['showadvertisements']."' WHERE Name = 'advertisements_show_advertisements'");
  sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['dateformat']."' WHERE Name = 'advertisements_date_format'");
}

//==============================================================================
//==============================================================================
//==============================================================================
?>
