<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* flags.php                                       *
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

if ($section == "clientflags"){
$tableart = 'admins';

$show = $settings['show_clients'];
$view = $viewtext['System'][82];

$tpl->set_file("inhalt", "templates/flags.tpl.htm");
$tpl->set_var(array( "menue_target_name"            => $viewtext['System'][82],
                     "menue_flags"                  => $viewtext['Flagsmenu'][1],
                     "menue_editflags_target"       => $viewtext['Flagsmenu'][3]
                    ));
//$menuenum[2] = 7;
//$menuenum[5] = 19;
$flags_viewb = $viewtext['Flagsmenu'][7];
$flags_viewa = $viewtext['Flagsmenu'][9];
$viewtext_serachscrollbox = $viewtext['System'][82];
}

if ($section == "groupflags"){
$tableart = 'groups';

$show = $settings['show_groups'];
$view = $viewtext['System'][83];

$tpl->set_file("inhalt", "templates/flags.tpl.htm");
$tpl->set_var(array( "menue_target_name"            => $viewtext['System'][83],
                     "menue_flags"                  => $viewtext['Flagsmenu'][2],
                     "menue_editflags_target"       => $viewtext['Flagsmenu'][4]
                    ));
//$menuenum[2] = 8;
//$menuenum[5] = 20;
$flags_viewb = $viewtext['Flagsmenu'][8];
$flags_viewa = $viewtext['Flagsmenu'][10];



$viewtext_serachscrollbox = $viewtext['System'][83];
}





if ($action == ""){ //--

  $tpl->set_var(array(
     "menue_target_flags"           => $viewtext['System'][106],
     "menue_target_id"              => $viewtext['System'][68],
     "menue_target_action"          => $viewtext['System'][40],
     "menue_target_flags_edit"      => $viewtext['System'][11]
     ));

  $flag = array();
  $flag = Setflags($viewtext);

  $tpl = ViewFlaginfo($tpl, $flag, $viewtext, $viewtext);
  
  $tpl->set_block("inhalt", "targetsanzeige", "targets_handle");



    // Search Funktion
  $allsearchcategories = array();
  $allsearchcategories['value'] = array('id', 'name');
  $allsearchcategories['view'] = array($viewtext['System'][68], $viewtext_serachscrollbox);
  $allsearchcategories['table'] = array('id', 'name');
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, '', '');
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, '');

  // Next Page Funktion
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$smtable."_".$tableart." ".$searcquery));


  //Wieviele Einträge gibt es überhaupt
  //$all_entrys = mysql_num_rows(mysql_query("SELECT id FROM ".$smtable."_".$tableart.""));

  if ($current_site > ceil($all_entrys / $show)) $current_site = 1;
  $start_entry = $current_site * $show - $show;
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$view, $start_entry, $all_entrys, $show);
  $tpl = site_links($tpl, $all_entrys, $show, $current_site, $section, '', '', $searchcat, $searchstring, '', $viewtext);


//  $sql = "SELECT * FROM ".$smtable."_".$tableart." ORDER BY id ASC LIMIT ".$start_entry.", ".$show."";

  $sql = "SELECT * FROM ".$smtable."_".$tableart." ".$searcquery." ORDER BY id ASC LIMIT ".$start_entry.", ".$show."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

    $flags = chunk_split( $row['flags'], 1, '&nbsp;' );

    if ($row['flags'] == "") $flags = $flags_viewa;

    $tpl->set_var(array(
            "target_id"              => $row['id'],
            "target_name"            => $row['name'],
            "target_flags"           => $flags,
            "target_flags_edit_link" =>"index.php?section=".$section."&action=editflags&id=".$row['id'].""
    ));

    $tpl->parse("targets_handle", "targetsanzeige", true);
    }
  }
}//--

if ($action == "editflags"){ //--

$viewid = (isset($_POST['target'])) ? $_POST['target']:'';
if ($viewid != "") $id = $viewid;


if ($id != ""){

    $tpl->set_file("inhalt", "templates/flags_edit.tpl.htm");

    $tpl->set_var(array(

       "menue_target_action"          => $viewtext['System'][40],
       "menue_target_flags_edit"      => $viewtext['System'][11],
       "menue_editflags_flags"        => $viewtext['System'][106],
       "exec_editflags_button_text"   => $viewtext['System'][7],
       "back_editflags_button_text"   => $viewtext['System'][4],
       "viewtargetbutton_text"        => $viewtext['System'][8],
       "editflags_backaction"         => "index.php?section=".$section."",
       "editflagsapplyaction"         => "index.php?section=".$section."&action=execeditflags&id=".$id."",
       "editflags_viewstarget"        => "index.php?section=".$section."&action=editflags"
    ));

    $tpl->set_block("inhalt", "scrolltargetblock", "scrolltargetblock_handle");

    $sql = "SELECT * FROM ".$smtable."_".$tableart." ORDER BY id ASC";

    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result))
    {        while($row = mysql_fetch_assoc($result)){

      $tpl->set_var(array(
         "scrolltargetvalue"     => $row['id'],
         "scrolltarget"          => $row['name'],
         "scrolltargetselected"  => ""
      ));
  
      if ($row['id'] == $id){
	  $tpl->set_var(array("scrolltargetselected" => "SELECTED"));
	  $flags_viewb = str_replace('%target%', $row['name'] , $flags_viewb);
	  
	  }

      $tpl->parse("scrolltargetblock_handle", "scrolltargetblock", true);
      }
      }

	  
	
    $tpl->set_var(array("menue_editflags" =>  $flags_viewb));
  
    $flag = array();
    $flag = Setflags($viewtext);
  
    $tpl = ViewFlagCheckbox($tpl, $flag, $smtable, $tableart, $id, 'id', $viewtext, $viewtext);

    
    }
}//--

if ($action == "execeditflags"){ //--

if ($id != ""){

$sql = "SELECT * FROM ".$smtable."_".$tableart." WHERE id = ".$id."";

    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result))
    {        while($row = mysql_fetch_assoc($result)){

  $setflags = '';
  $flag = array();
  $flag = Setflags($viewtext);

  for ($x = 0; $x <= (count($flag['flag'])-1); $x++){

    if ((isset($_POST['flag_'.$flag['flag'][$x].''])) ? $_POST['flag_'.$flag['flag'][$x].'']:'' == "checked"){

        $setflags = $setflags.$flag['flag'][$x];
    }
  }
  sqlexec("UPDATE ".$smtable."_".$tableart." SET flags='".$setflags."' WHERE id = ".$id."");

  $viewtext['Flagsmenu'][11] = str_replace('%target%', $row['name'],$viewtext['Flagsmenu'][11]);
  $tpl = Infobox($tpl, str_replace('%target%', $row['name'] , $flags_viewb), $viewtext['Flagsmenu'][11], $viewtext['System'][3], 'index.php?section='.$section.'');

    }
    }
}
}//--

}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-

?>
