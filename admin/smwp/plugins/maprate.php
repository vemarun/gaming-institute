<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* maprate.php                                     *
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
$plugin['MMSColum'] = (Server ID columname from Plugintable)
$plugin['Support'] = (Link to Support)
$plugin['Showplug'] = (show plugin in navi. 0 = Hide / 1 = Show)


### Variables from Plugin Specific Settings some can used here:

$settings['maprate_show_maps'] = Maps per page in simple mode
$settings['maprate_show_maps_detailed'] = Maps per page in detailed mode
$settings['maprate_show_players'] = players per page in detailed mode
$settings['maprate_date_format'] = Votedate Format

*/
$view = (isset($_GET['view'])) ? $_GET['view']:'';
$mapname = (isset($_GET['map'])) ? $_GET['map']:'';

if ($area == ''){ // Begin if Area = ''




// -------------------------------[ If id empty  ]------------------------------


  // Define stationery text and set templatevars.
  $tpl->set_var(array(
    "maprate_pluginname"                      => $plugin['Name'],
    "section"                                 => $section,
    "plugin"                                  => $plugin['Systemname'],
    "maprate_novotes"                         => $viewtext['Maprate'][5],
    "maprate_terrible_discription"            => $viewtext['Maprate'][12],
    "maprate_poor_discription"                => $viewtext['Maprate'][13],
    "maprate_average_discription"             => $viewtext['Maprate'][14],
    "maprate_good_discription"                => $viewtext['Maprate'][15],
    "maprate_excellent_discription"           => $viewtext['Maprate'][16],
    "maprate_detailed_overview_text"          => $viewtext['System'][35],
    "maprate_simple_overview_text"            => $viewtext['System'][36]
  ));

if ($action == ""){


if ($mapname == ""){


if ($view == ""){
  if ($settings['maprate_startpage'] == "1"){
    $view = 'simple';
  }else{
    $view = 'detailed';
  }
}


if (($view == "simple") or ($view == "detailed")){ // detailed


    // Define stationery text and set templatevars.
  $tpl->set_var(array(
    "maprate_map_discription"                 => $viewtext['System'][124] ,
    "maprate_votes_discription"               => $viewtext['Maprate'][2],
    "maprate_average_discription"             => $viewtext['Maprate'][3],
    "maprate_votepercent_discription"         => $viewtext['Maprate'][4],
    "maprate_allpints_discription"            => $viewtext['Maprate'][6],
    "maprate_mapcount_discription"            => $viewtext['Maprate'][7],
    "maprate_ratingsinformation_discription"  => $viewtext['Maprate'][8],
    "maprate_votepoints_discription"          => $viewtext['Maprate'][9],
    "map_delete_pic_infotext"                 => $viewtext['Maprate'][22],
    "maprate_options_discription"             => $viewtext['System'][65],
    "map_playerrate_pic_infotext"             => $viewtext['Maprate'][24],
    "maprate_reset_text"                      => $viewtext['System'][92],
    "maprate_mappicture_discription"          => $viewtext['System'][37],
    "maprate_details_discription"             => $viewtext['System'][38]
 ));


 // Define templatefile
if ($view == "simple"){ // Simple View
$max_width = 200; // Px
$tpl->set_file("inhalt", "templates/plugins/maprate.tpl.htm");
$extracommand = '';
$show_maps = $settings['maprate_show_maps'];
} 

if ($view == "detailed"){// Detailed View
$tpl->set_file("inhalt", "templates/plugins/maprate_detailed.tpl.htm");
$max_width = 130; // Px
$extracommand = '&view=detailed';
$show_maps = $settings['maprate_show_maps_detailed'];
}


//Define blocks to schow maps in the loop
$tpl->set_block("inhalt", "mapview", "mapview_handle");
$tpl->set_block("inhalt", "mapviewempty", "mapviewempty_handle");


$map_array = array();

$mapcount = 0;
$allvotespoints = 0;
$allvotescount = 0;
  // Select Banrate Table
   $sql = "SELECT * FROM ".$plugin['Tablename']." ORDER BY map";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

$allvotescount++;

// Get all Votepoints
$allvotespoints = $allvotespoints + $row['rating'];

// Read all information from table and add them to a array
if (!isSet($map_array[$row['map']]['ratingsdetails'][$row['rating']])) $map_array[$row['map']]['ratingsdetails'][$row['rating']] = '0';
$map_array[$row['map']]['ratingsdetails'][$row['rating']] = $map_array[$row['map']]['ratingsdetails'][$row['rating']] + 1;
}

// Calculate size an percent for the votebar
$px_via_percent = ($max_width - 17) / 100;
$one_percent_allvotes = 100 / $allvotescount;


 // ---> START show page section <---
  // Check Entry Count for Nextpage-Funktion
  //$all_entrys = mysql_num_rows(mysql_query("SELECT id FROM ".$plugin['Tablename']));
  $all_entrys = count($map_array);
  // If current page > Count of all pages set current page to count of pages
  if ($current_site > ceil($all_entrys / $show_maps)) $current_site = 1;
  // Get Startentry
  $start_entry = $current_site * $show_maps - $show_maps;
  // Start funktion show entry ... to ... from ...!
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['Maprate'][7], $start_entry, $all_entrys, $show_maps);
  // Start funktion page-select-links
  $tpl = site_links($tpl, $all_entrys, $show_maps, $current_site, $section, '', $plugin['Systemname'], '', '', $extracommand, $viewtext);

  // ---> END Show Page Section <---

// $sql = "SELECT * FROM ".$plugin['Tablename']." ".$searcquery." ORDER BY timestamp DESC LIMIT ".$start_entry.", ".$settings['mysqlbans_show_bans']."";)

$loopentrys = 0;

//Read map array and do loop
foreach($map_array as $mapname => $value) { //-+-


if (($loopentrys >= $start_entry) and ( ($show_maps + $start_entry) > $loopentrys)){


  // set votescount to 0
  $map_array[$mapname]['votes'] = '0';
  // set ratepints to 0
  $map_array[$mapname]['points'] = '0';



  // do loop 1-5
  for ($x = 1; $x <= 5; $x++){  // -++-
    // if there no rates for a ratelevel set this ratelevel to 0
    if (!isSet($map_array[$mapname]['ratingsdetails'][$x])) $map_array[$mapname]['ratingsdetails'][$x] = '0';
    // read number of votes in a ratelevel and add it to votescount
    $map_array[$mapname]['votes'] = $map_array[$mapname]['votes'] + $map_array[$mapname]['ratingsdetails'][$x];
    // read ratelevel and add all ratepints to the array
    $map_array[$mapname]['points'] = $map_array[$mapname]['points'] + ($x * $map_array[$mapname]['ratingsdetails'][$x]);
  } //-++-


  // get one percent from ratelevel for a map
  $one_percent_rate = 100 / $map_array[$mapname]['votes'];
  // Get average for a map (rateponits / votes)  max.5
  $map_array[$mapname]['average'] = round ($map_array[$mapname]['points'] / $map_array[$mapname]['votes'],2);
  // Get percents of vote for a map
  $percent = $one_percent_allvotes * $map_array[$mapname]['votes'];

  // Define templatevars in the "read map array loop"
  
    $mapname = BadStringFilterShow($mapname);
  
  $tpl->set_var(array(
    "maprate_map"           => $mapname,
    "maprate_votes"         => $map_array[$mapname]['votes'],
    "maprate_average"       => $map_array[$mapname]['average']
  ));
  
$mapnametext = $mapname;

      

if ($view == "simple"){


if (strlen($mapnametext) > 26) $mapnametext = substr($mapnametext, 0, 24) . "...";


  // Set color if bars from percent of vote
  if (10 >= $percent) $color = 'red';
  if ((10 < $percent) and ( 20 >= $percent)) $color = 'orange';
  if ((20 < $percent) and ( 30 >= $percent)) $color = 'yellow';
  if ((30 < $percent) and ( 50 >= $percent)) $color = 'darkgreen';
  if (50 < $percent) $color = 'brightgreen';

  $tpl->set_var(array(
    "maprate_maptext"       => $mapnametext,
    "maprate_barwitch"      => $px_via_percent * $percent,
    "maprate_votepercent"   => round ($percent,1),
    "maprate_barcolor"      => $color
  ));
}

if ($view == "detailed"){ //+-+-

if (strlen($mapnametext) > 24) $mapnametext = substr($mapnametext, 0, 22) . "...";


for ($x = 1; $x <= 5; $x++){ 

$percentrate = $one_percent_rate * $map_array[$mapname]['ratingsdetails'][$x];

  $tpl->set_var(array(
    "maprate_ratevotes_".$x.""  => $map_array[$mapname]['ratingsdetails'][$x],
    "maprate_barwitch_".$x.""   => $px_via_percent * $percentrate,
    "maprate_ratepercent_".$x.""   => round ($percentrate,1)
  ));


}

if (file_exists("inc/pics/maps/".$mapname.".jpg")){
$mapic = "inc/pics/maps/".$mapname.".jpg";
}else{
if (file_exists("inc/pics/maps/".$mapname.".JPG")){
$mapic = "inc/pics/maps/".$mapname.".JPG";
}else{
$mapic = "inc/pics/no_picture.jpg";
}
}

 $tpl->set_var(array(
    "maprate_maptext"       => $mapnametext,
    "maprate_votepoints"    => $map_array[$mapname]['points'],
    "maprate_mappic"        => $mapic
  ));

}//+-+-

  $tpl->parse("mapview_handle", "mapview", true);

}

$loopentrys++;



} // -+-

}else{

$tpl->parse("mapviewempty_handle", "mapviewempty", true);

}


  // Define stationery text and set templatevars.
  $tpl->set_var(array(
  "maprate_allpoints"  => $allvotespoints,
  "maprate_maxwidth"   => $max_width,
  "maprate_allvotes"   => $allvotescount,
  "maprate_mapcount"   => count($map_array)
  ));




// echo'<pre>';
// print_r($map_array);
// echo'</pre>';


}

}else{ //Player view

$starfull = '<img src="inc/pics/rate_star_full.gif" width="14" height="15">';
$starempty = '<img src="inc/pics/rate_star_empty.gif" width="14" height="15">';

  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/maprate_votes.tpl.htm");

  //Define blocks to schow maps in the loop
  $tpl->set_block("inhalt", "playerview", "playerview_handle");
  $tpl->set_block("inhalt", "playerviewempty", "playerviewempty_handle");

    // Define stationery text and set templatevars.
  $tpl->set_var(array(
    "maprate_player_discription"    => $viewtext['System'][88],
    "maprate_date_discription"      => $viewtext['System'][114],
    "maprate_rateing_discription"   => $viewtext['Maprate'][19],
    "maprate_terrible_stars"        => $starfull,
    "maprate_poor_stars"            => $starfull.$starfull,
    "maprate_average_stars"         => $starfull.$starfull.$starfull,
    "maprate_good_stars"            => $starfull.$starfull.$starfull.$starfull,
    "maprate_excellent_stars"       => $starfull.$starfull.$starfull.$starfull.$starfull,
  ));




  // ---> START show search section <---
  // Create empty array
  $allsearchcategories = array();
  // Define values in the serachscrollbox
  $allsearchcategories['value'] = array('rating', 'steamid');
  // Define showtext in the serachscrollbox
  $allsearchcategories['view'] = array($viewtext['Maprate'][23], $viewtext['System'][88]);
  // Define serach table column's
  $allsearchcategories['table'] = array('rating', 'steamid');
  // Start funktion show search area
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, $plugin['Systemname'], '&map='.$mapname.'');
  // Search filter for sql injections
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, "map='".$mapname."'");

  // ---> START show page section <---
  // Check Entry Count for Nextpage-Funktion
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$plugin['Tablename']." ".$searcquery.""));
  //$all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$plugin['Tablename']." ".$searcquery." ".$hidefilter.""));
  // If current page > Count of all pages set current page to count of pages
  if ($current_site > ceil($all_entrys / $settings['maprate_show_votes'])) $current_site = 1;
  // Get Startentry
  $start_entry = $current_site * $settings['maprate_show_votes'] - $settings['maprate_show_votes'];
  // Start funktion show entry ... to ... from ...!
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['Maprate'][2], $start_entry, $all_entrys, $settings['maprate_show_votes']);
  // Start funktion page-select-links
  $tpl = site_links($tpl, $all_entrys, $settings['maprate_show_votes'], $current_site, $section, '', $plugin['Systemname'], $searchcat, $searchstring, '&map='.$mapname.'', $viewtext);
  // ---> END Show Page Section <---
  
    // Select Banrate Table
  $sql = "SELECT * FROM ".$plugin['Tablename']." ".$searcquery." ORDER BY rated DESC LIMIT ".$start_entry.", ".$settings['maprate_show_votes']."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

$star = '';
for ($x = 1; $x <= 5; $x++){



if ($row['rating'] >= $x ){
  $star = $star.$starfull;
}else{
  $star = $star.$starempty ;
}

}
          // Parse US/English date format into a unix timestamp and Get Day, Mounth, Jear e.t.c.
           $ratedate = date($settings['maprate_date_format'], strtotime($row['rated']));


  $tpl->set_var(array(
    "maprate_player"    => $row['steamid'],
    "maprate_date"      => $ratedate,
    "maprate_rateing"   => $star,
    "maprate_map"       => $mapname
  ));



$tpl->parse("playerview_handle", "playerview", true);

}
}else{

$tpl->parse("playerviewempty_handle", "playerviewempty", true);

}



}

} // End if action empty

if ($action == "delmap"){
  $viewtext['Maprate'][25] = str_replace('%map%', $mapname, $viewtext['Maprate'][25]);
  $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Maprate'][25], $viewtext['System'][2], $viewtext['System'][1], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execdelmap&page=".$current_site."&map=".$mapname."" );
}

if ($action == "execdelmap"){
  $viewtext['Maprate'][26] = str_replace('%map%', $mapname, $viewtext['Maprate'][26]);
  sqlexec("DELETE FROM ".$plugin['Tablename']." WHERE map = '".$mapname."'");
  $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Maprate'][26], $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."");
}

if ($action == "reset"){
  $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Maprate'][27], $viewtext['System'][2], $viewtext['System'][1], "index.php?section=plugins&plugin=".$plugin['Systemname']."&page=".$current_site."", "index.php?section=plugins&plugin=".$plugin['Systemname']."&action=execreset" );
}

if ($action == "execreset"){
  sqlexec("TRUNCATE ".$plugin['Tablename']."");
  $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Maprate'][28], $viewtext['System'][3], "index.php?section=plugins&plugin=".$plugin['Systemname']."");
}



} // End if Area = ''


//==============================================================================
//--------------------------=[Section Plugnin Settings]=------------------------
//==============================================================================


// ---> BEGIN Create Table for MySQL Banning Plugin
if ($area == 'createtable'){

sqlexec("CREATE TABLE ".$plugintable."(
  steamid VARCHAR(24),
  map VARCHAR(48),
  rating INT(4),
  rated DATETIME,
  UNIQUE KEY (map, steamid))");

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
$tpl->set_file("maprate_specific_settings", "templates/plugins/maprate_specific_settings.tpl.htm");

// Settingsaray from Settingstable and Description sending to Template var.
$tpl->set_var(array(
"maprate_specific_settings_show_maps_discription"  => $viewtext['Maprate'][11],
"maprate_specific_settings_show_maps"  => $settings['maprate_show_maps'],
"maprate_specific_settings_show_maps_detailed_discription"  => $viewtext['Maprate'][10],
"maprate_specific_settings_show_maps_detailed"  => $settings['maprate_show_maps_detailed'],
"maprate_specific_settings_show_votes_discription"  => $viewtext['Maprate'][20],
"maprate_specific_settings_show_votes"  => $settings['maprate_show_votes'],
"maprate_specific_settings_date_format_discription"  => $viewtext['Maprate'][21],
"maprate_specific_settings_date_format"  => $settings['maprate_date_format'],
"maprate_specific_settings_startpage_discription"  => $viewtext['Maprate'][0],
"maprate_specific_settings_startpage_simple"  => $viewtext['Maprate'][17],
"maprate_specific_settings_startpage_detailed"  => $viewtext['Maprate'][18]
));

    if ($settings['maprate_startpage'] == "1"){
      $tpl->set_var(array(
              "startpagechecked1"        => "checked",
              "startpagechecked2"         => ""
      ));
    }else{
      $tpl->set_var(array(
              "startpagechecked1"        => "",
              "startpagechecked2"         => "checked"
      ));
    }

// Parse maprate_specific_settings.tpl.htm to plugins_settings.tpl.htm
$tpl->set_var(array("specific_settings" => $tpl->parse("out", "maprate_specific_settings"),));

}

//------------------------------------------------------------------------------

// ---> BEGIN to save the Specific Settings in the Settings Table.

if ($area == 'savepecificsettings'){

// Read inputfields from html template (Array must be $plugin['...']).
$plugin['startpage']   = (isset($_POST['startpage'])) ? $_POST['startpage']:'';
$plugin['show_maps']   = (isset($_POST['showmaps'])) ? $_POST['showmaps']:'';
$plugin['show_maps_detailed']   = (isset($_POST['showmaps_detailed'])) ? $_POST['showmaps_detailed']:'';
$plugin['show_votes']   = (isset($_POST['show_votes'])) ? $_POST['show_votes']:'';
$plugin['date_format']   = (isset($_POST['date_format'])) ? $_POST['date_format']:'';




// Checkt type is Integer, when not or empty set to "0".
if(isset($plugin['startpage'])) settype($plugin['startpage'], "integer");
if(isset($plugin['show_maps'])) settype($plugin['show_maps'], "integer");
if(isset($plugin['show_maps_detailed'])) settype($plugin['show_maps_detailed'], "integer");
if(isset($plugin['show_votes'])) settype($plugin['show_votes'], "integer");

echo $plugin['startpage'];


// When var is "0" set to Default
if ($plugin['startpage'] == 0) $plugin['startpage'] = 2;
if ($plugin['show_maps'] == 0) $plugin['show_maps'] = 10;
if ($plugin['show_maps_detailed'] == 0) $plugin['show_maps_detailed'] = 4;
if ($plugin['show_votes'] == 0) $plugin['show_votes'] = 10;
// When var is empty set to Default
if ($plugin['date_format'] == '') $plugin['date_format'] = 'm-d-y H:i:s';


// Save var in settings-table.
sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['startpage']."' WHERE Name = 'maprate_startpage'");
sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['show_maps']."' WHERE Name = 'maprate_show_maps'");
sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['show_maps_detailed']."' WHERE Name = 'maprate_show_maps_detailed'");
sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['show_votes']."' WHERE Name = 'maprate_show_votes'");
sqlexec("UPDATE ".$table."_settings SET Value='".$plugin['date_format']."' WHERE Name = 'maprate_date_format'");

}
//==============================================================================
//==============================================================================
//==============================================================================


?>
