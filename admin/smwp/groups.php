<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* groups.php                                      *
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


if ($usercheck['sqladmins'] == "1"){ //-+-+-

$tpl->set_var(array(
"menue_group_name"          => $viewtext['System'][70],
"menue_group_immunitylevel" => $viewtext['System'][97],
));

// $group_immunity          = array();
// $group_immunity_value    = array();
// $group_immunity[0]       = $viewtext['Groups'][8];
// $group_immunity_value[0] = 'none';
// $group_immunity[1]       = $viewtext['Groups'][5];
// $group_immunity_value[1] = 'global';
// $group_immunity[2]       = $viewtext['Groups'][11];
// $group_immunity_value[2] = 'default';

if ($action == ""){ //--

$tpl->set_file("inhalt", "templates/groups.tpl.htm");

  $tpl->set_var(array(
     "menue_group_id"            => $viewtext['System'][68],
     "menue_group_action"        => $viewtext['System'][40],
     "menue_groups"              => $viewtext['System'][98],
     "menue_group_member"        => $viewtext['Groups'][4],
     "menue_group_edit"          => $viewtext['System'][11],
     "menue_group_del"           => $viewtext['System'][12],
     "add_group_button_text"     => $viewtext['Groups'][0],
     "menue_group_groupimmunity" => $viewtext['Groups'][7]
  ));

  $tpl->set_block("inhalt", "groupsanzeige", "groups_handle");
  $tpl->set_block("inhalt", "groupsanzeigeempty", "groupsempty_handle");







    // Search Funktion
  $allsearchcategories = array();
  $allsearchcategories['value'] = array('id', 'name', 'immunitylevel');
  $allsearchcategories['view'] = array($viewtext['System'][68], $viewtext['System'][70], $viewtext['System'][97]);
  $allsearchcategories['table'] = array('id', 'name', 'immunity_level');
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, '', '');
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, '');

  // Next Page Funktion
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$smtable."_groups ".$searcquery));




//  $all_entrys = mysql_num_rows(mysql_query("SELECT id FROM ".$smtable."_groups"));





  if ($current_site > ceil($all_entrys / $settings['show_groups'])) $current_site = 1;

  $start_entry = $current_site * $settings['show_groups'] - $settings['show_groups'];
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['System'][83], $start_entry, $all_entrys, $settings['show_groups']);
  $tpl = site_links($tpl, $all_entrys, $settings['show_groups'], $current_site, $section, '', '', $searchcat, $searchstring, '', $viewtext);


  //$sql = "SELECT * FROM ".$smtable."_groups ORDER BY id ASC LIMIT ".$start_entry.", ".$settings['show_groups']."";
  $sql = "SELECT * FROM ".$smtable."_groups ".$searcquery." ORDER BY id ASC LIMIT ".$start_entry.", ".$settings['show_groups']."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){



$member_anzahl_clients = get_hits_management($smtable."_admins_groups", "group_id", $row['id']);
$member_anzahl_groups = get_hits_management($smtable."_group_immunity", "group_id", $row['id']);



/*$sql_hits = "select count(*) as hits from ".$smtable."_admins_groups WHERE group_id = '".$row['id']."'";

$member_anzahl_clients = mysql_query($sql_hits) OR die("Query: <pre>".$sql_hits."</pre>\n".
"Antwort: ".mysql_error());

$member_anzahl_clients = mysql_fetch_row($member_anzahl_clients);


$sql_hits = "select count(*) as hits from ".$smtable."_group_immunity WHERE group_id = '".$row['id']."'";

$member_anzahl_groups = mysql_query($sql_hits) OR die("Query: <pre>".$sql_hits."</pre>\n".
"Antwort: ".mysql_error());

$member_anzahl_groups = mysql_fetch_row($member_anzahl_groups);   */



$x = 0;
/*for (;;){
  if ($group_immunity_value[$x]==$row['immunity']) break;
  $x++;
}     */

  $row['name'] = BadStringFilterShow($row['name']);

    $tpl->set_var(array(
            "group_id"            => $row['id'],
            "group_name"          => $row['name'],
            "group_immunity"      => $row['immunity_level'],
            "group_member"        => $member_anzahl_clients,
            "group_groupimmunity" => $member_anzahl_groups
    ));
     $tpl->parse("groups_handle", "groupsanzeige", true);
  }
  }else{
      $tpl->set_var(array( "groups_empty" => $viewtext['Groups'][8]));
    $tpl->parse("groupsempty_handle", "groupsanzeigeempty", true);
  }
}//--

if (($action == "addgroup") or ($action == "editgroup")){ //--
$tpl->set_file("inhalt", "templates/group_add_edit.tpl.htm");


  $tpl->set_var(array(
     "menue_group_info_general"    => $viewtext['System'][71],
     "back_group_button_text"      => $viewtext['System'][4],
     "add_edit_group_button_text"  => $viewtext['System'][7]
  ));

}//--

if ($action == "addgroup"){ //--

 $tpl->set_var(array(

     "menue_groups_add_edit"       => $viewtext['Groups'][0],
     "execaddeditgroup"            => "index.php?section=groups&action=execaddgroup",
     "group_name"                  => "",
     "group_immunitylevel"         => "0"
  ));

  /*$tpl->set_block("inhalt", "scrollimmunityblock", "immunity_handle");

  for ($x = 0; $x <= 2; $x++){

    $tpl->set_var(array(
       "scrollimmunity"         => $group_immunity[$x],
       "scrollimmunityvalue"    => $group_immunity_value[$x],
       "scrollimmunityselected" => ""
    ));
    $tpl->parse("immunity_handle", "scrollimmunityblock", true);
  } */

} //--

if ($action == "editgroup"){ //--

 $tpl->set_var(array(
     "menue_groups_add_edit"       => $viewtext['Groups'][1],
     "execaddeditgroup"            => "index.php?section=groups&action=execeditgroup&id=".$id."",
  ));

  $sql = "SELECT * FROM ".$smtable."_groups WHERE id = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

  /*$tpl->set_block("inhalt", "scrollimmunityblock", "immunity_handle");

  for ($x = 0; $x <= 2; $x++){

    $tpl->set_var(array(
       "scrollimmunity"         => $group_immunity[$x],
       "scrollimmunityvalue"    => $group_immunity_value[$x],
    ));

    if ($group_immunity_value[$x]==$row['immunity']){
      $tpl->set_var(array("scrollimmunityselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("scrollimmunityselected" => ""));
    }

    $tpl->parse("immunity_handle", "scrollimmunityblock", true);
  }*/

  
    $row['name'] = BadStringFilterShow($row['name']);
  
  $tpl->set_var(array(
     "group_name"              => $row['name'],
     "group_immunitylevel"     => $row['immunity_level']
  ));
  }
  }
}//--

if (($action == "execaddgroup") or ($action == "execeditgroup")){ //--

$group = array();

$group['name']                = (isset($_POST['groupname'])) ? $_POST['groupname']:'';
$group['immunitylevel']  = (isset($_POST['groupimmunitylevel'])) ? $_POST['groupimmunitylevel']:'';

  $group['name'] = BadStringFilterSave($group['name']);
}

if ($action == "execaddgroup"){ //--
 //Testen ob doppelt möglich
 if (($group['name'] == "") or ($group['immunitylevel'] == "")){
    $tpl = Infobox($tpl, $viewtext['Groups'][0], $viewtext['System'][25], $viewtext['System'][4], 'index.php?section=groups&action=addgroup');
 }else{

      sqlexec("INSERT INTO ".$smtable."_groups SET
                name='".$group['name']."',
                immunity_level='".$group['immunitylevel']."',
                flags=''
              ");
    $viewtext['Groups'][14] = str_replace('%group%', $group['name'], $viewtext['Groups'][14]);

    $tpl = Infobox($tpl, $viewtext['Groups'][0], $viewtext['Groups'][14], $viewtext['System'][3], 'index.php?section=groups');
  }
}//--

if ($action == "execeditgroup"){ //--


if (($group['name'] == "") or ($group['immunitylevel'] == "")){
    $tpl = Infobox($tpl, $viewtext['Groups'][0], $viewtext['System'][25], $viewtext['System'][4], 'index.php?section=groups&action=editgroup&id='.$id.'');

    }else{

      sqlexec("UPDATE ".$smtable."_groups SET
                name='".$group['name']."',
                immunity_level='".$group['immunitylevel']."'
                WHERE
                  id = ".$id."
              ");
    $viewtext['Groups'][15] = str_replace('%group%', $group['name'], $viewtext['Groups'][15]);

    $tpl = Infobox($tpl, $viewtext['Groups'][0], $viewtext['Groups'][15], $viewtext['System'][3], 'index.php?section=groups');
  }
}//--

if (($action == "delgroup") or ($action == "execdelgroup")){ //--

  $sql = "SELECT * FROM ".$smtable."_groups WHERE id = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

   $viewtext['Groups'][17] = str_replace('%group%', $row['name'], $viewtext['Groups'][17]);
   $viewtext['Groups'][18] = str_replace('%group%', $row['name'], $viewtext['Groups'][18]);
   }
   }
}

if ($action == "delgroup"){ //--
  $tpl = Infoboxselect($tpl, $viewtext['Groups'][2], $viewtext['Groups'][17], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section=groups', 'index.php?section=groups&action=execdelgroup&id='.$id.'' );
}//--

if ($action == "execdelgroup"){ //--
  sqlexec("DELETE FROM ".$smtable."_groups WHERE id = ".$id."");
  sqlexec("DELETE FROM ".$smtable."_admins_groups WHERE group_id = ".$id."");
  sqlexec("DELETE FROM ".$smtable."_group_immunity WHERE group_id = ".$id." OR other_id = ".$id."");
  sqlexec("DELETE FROM ".$smtable."_group_overrides WHERE group_id = ".$id."");
  sqlexec("DELETE FROM ".$smtable."_servers_groups WHERE group_id = ".$id."");
  $tpl = Infobox($tpl, $viewtext['Groups'][2], $viewtext['Groups'][18], $viewtext['System'][3], 'index.php?section=groups');
}//--



// if ($action == "editgroup") include ("management.php");

}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-

?>
