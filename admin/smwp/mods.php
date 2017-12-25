<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* mods.php                                        *
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


if ( $usercheck['editmods'] == "1"){ //-+-+-



  $tpl->set_var(array(
    "section"            => $section,
    "action"             => $action
	));

// ----------------------------[ If action empty ]------------------------------
if ($action  == ""){

  $tpl->set_var(array(
    "mods_header_text"            => $viewtext['Mods'][1],
    "mods_no_entrys"              => $viewtext['Mods'][2],
    "mods_folder_discription"     => $viewtext['Mods'][3],
    "mods_icon_discription"       => $viewtext['Mods'][4],
    "add_mod_button_text"         => $viewtext['Mods'][5],
    "mods_edit_pic_infotext"      => $viewtext['Mods'][6],
    "mods_delete_pic_infotext"    => $viewtext['Mods'][7],
    "mods_advert_pic_discription" => $viewtext['Mods'][8],
    "mods_options_discription"    => $viewtext['System'][65],
    "mods_id_discription"         => $viewtext['System'][68],
    "mods_name_discription"       => $viewtext['System'][70]
  ));
  $tpl->set_file("inhalt", "templates/mods.tpl.htm");

  $tpl->set_block("inhalt", "modsview", "modsview_handle");
  $tpl->set_block("inhalt", "modsviewempty", "modsviewempty_handle");

  $count = 0;
  
  $sql = "SELECT * FROM ".$table."_mods ORDER BY ID ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

      $count++;
      if ($row['advert'] == ""){
        $tpl->set_var(array(
          "mods_advert_pic"  => "inc/pics/na.gif",
          "mods_advert_info" => $viewtext['Mods'][11]
        ));
      }else{
        $tpl->set_var(array(
          "mods_advert_pic"  => "inc/pics/ok.png",
          "mods_advert_info" => $viewtext['Mods'][10]
        ));
      }

      if ($row['icon'] == ""){
        $tpl->set_var(array("mods_game_pic" => "inc/pics/question_gray.gif"));
      }else{
        $tpl->set_var(array("mods_game_pic" => "inc/pics/games/".$row['icon']));
      }

      $tpl->set_var(array(
        "mods_id"       => $row['ID'],
        "mods_name"     => $row['name'],
        "mods_folder"   => $row['folder'],
      ));
      $tpl->parse("modsview_handle", "modsview", true);
    }
  }else{
    $tpl->parse("modsviewempty_handle", "modsviewempty", true);
  }
  $tpl->set_var(array("mods_count" => $count));
}

// -----------------------[ If action addmod or editmod  ]----------------------

if (($action == "addmod") OR ($action == "editmod")){

  $tpl->set_file("inhalt", "templates/mods_add_edit.tpl.htm");
  $tpl->set_var(array(
     "back_mods_button_text"	 => $viewtext['System'][4],
	 "add_edit_mod_button_text"	 => $viewtext['System'][7],
	 "mod_info_general"          => $viewtext['System'][71],
     "mod_name_discription"      => $viewtext['System'][70],
     "mod_folder_discription"    => $viewtext['Mods'][3],
     "mod_pic_discription"       => $viewtext['Mods'][4],
     "mod_advert_discription"    => $viewtext['Mods'][9],
     "mod_advert_info"           => $viewtext['Mods'][0]
  ));

    $tpl = Picmanagement($tpl, $table."_mods", "ID", $id, "icon", "inc/pics/games/", $viewtext, "index.php?section=".$section."&action=".$action."&id=".$id);
}

// -----------------------------[ If action addmod ]----------------------------

if ($action == "addmod"){

  $tpl->set_var(array(
     "mod_edit_add_discription"  => $viewtext['Mods'][5],
     "mod_name"       => "",
     "mod_folder"     => "",
     "mod_advert"     => "",
     "execaddeditmod" => "index.php?section=".$section."&action=execaddmod"
  ));
}
// ----------------------------[ If action editmod ]----------------------------

if ($action == "editmod"){

  $tpl->set_var(array(
     "mod_edit_add_discription"  => $viewtext['Mods'][6],
     "execaddeditmod" => "index.php?section=".$section."&action=execeditmod&id=".$id.""
  ));

  $row = ReadModEntry($tpl, $viewtext, $viewtext['Mods'][1], $table, $id);

  	  $tpl->set_var(array(
 	    "mod_name"      => $row['name'],
        "mod_folder"    => $row['folder'],
        "mod_advert"    => $row['advert']
      ));
   }

// -------------------[ If action execaddmod or execeditmod  ]------------------

if (($action == "execaddmod") OR ($action == "execeditmod")){

  $name    = (isset($_POST['name'])) ? $_POST['name']:'';
  $folder  = (isset($_POST['folder'])) ? $_POST['folder']:'';
  $adverts = (isset($_POST['adverts'])) ? $_POST['adverts']:'';
  
  if (($name == "") OR ($folder =="")){
    $go = 0;
    $tpl = Infobox($tpl, $viewtext['Mods'][1], $viewtext['System'][25], $viewtext['System'][4], 'javascript:history.back()');
  }else{
    $go = 1;
  
    $data = array();  
    $data['msg'] = '';
    $data['check'] = 1;	 
    if (isset($_FILES['picture']['name'])){
      if ($_FILES['picture']['name'] <> "") $data = picupload('picture', 'inc/pics/games', $viewtext,  '16', '16');    
    }
  
    if ($adverts == ""){
      $adverts = 'NULL';
    }else{
      $adverts = "'".$adverts."'";
    }
  
    if ($data['check'] == 0) $tpl = Infobox($tpl, $viewtext['Mods'][1], $data['msg'], $viewtext['System'][4], 'javascript:history.back()');

 }

}

// -----------------------[ If action execaddmod ]------------------------------

if (($action == "execaddmod") and ($go == 1) and ($data['check'] == 1)){

  $sql = "SELECT * FROM ".$table."_mods WHERE folder = '".$folder."'";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    $tpl = Infobox($tpl, $viewtext['Mods'][1], $viewtext['Mods'][12], $viewtext['System'][4], 'javascript:history.back()');
  }else{
      if (isset($data['picname'])){
	    sqlexec("INSERT INTO ".$table."_mods SET name='".$name."', folder='".$folder."', advert=".$adverts.", icon='".$data['picname']."'");
      }else{
	    sqlexec("INSERT INTO ".$table."_mods SET name='".$name."', folder='".$folder."', advert=".$adverts."");
	  }
	
	$viewtext['Mods'][13] = str_replace('%mod%', $name, $viewtext['Mods'][13]);
    $tpl = Infobox($tpl, $viewtext['Mods'][1], $data['msg'].$viewtext['Mods'][13], $viewtext['System'][4], 'index.php?section='.$section.'');
  }
}

// -----------------------[ If action execeditmod ]-----------------------------

if (($action == "execeditmod") and ($go == 1) and ($data['check'] == 1)){
  $sql = "SELECT * FROM ".$table."_mods WHERE folder = '".$folder."' AND ID <> '".$id."'";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    $tpl = Infobox($tpl, $viewtext['Mods'][1], $viewtext['System'][73], $viewtext['System'][4], 'javascript:history.back()');
  }else{
    if (isset($data['picname'])){
      sqlexec("UPDATE ".$table."_mods SET name='".$name."', folder='".$folder."', advert=".$adverts.", icon='".$data['picname']."' WHERE ID = '".$id."'");
	}else{
	  sqlexec("UPDATE ".$table."_mods SET name='".$name."', folder='".$folder."', advert=".$adverts." WHERE ID = '".$id."'");
	}
    $tpl = Infobox($tpl, $viewtext['Mods'][1], $data['msg'].$viewtext['System'][74], $viewtext['System'][3], 'index.php?section='.$section.'');
  }
}

// ----------------------------[ If action delmod  ]----------------------------

if ($action == "delmod"){
   $row = ReadModEntry($tpl, $viewtext, $viewtext['Mods'][1], $table, $id);
   if ($row <> False){
     $viewtext['Mods'][14] = str_replace('%folder%', $row['folder'], $viewtext['Mods'][14]);
     $tpl = Infoboxselect($tpl, $viewtext['Mods'][1], $viewtext['Mods'][14], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section='.$section.'', 'index.php?section='.$section.'&action=execdelmod&id='.$id.'' );
   }
}

// ----------------------------[ If action execdelmod  ]----------------------------

if ($action == "execdelmod"){
   $row = ReadModEntry($tpl, $viewtext, $viewtext['Mods'][1], $table, $id);
   if ($row <> False){
     $viewtext['Mods'][15] = str_replace('%folder%', $row['folder'], $viewtext['Mods'][15]);
     sqlexec("DELETE FROM ".$table."_mods WHERE ID = ".$id."");
     if(is_file('inc/pics/games/'.$row['icon'])) unlink('inc/pics/games/'.$row['icon']);
     $tpl = Infobox($tpl, $viewtext['Mods'][1], $viewtext['Mods'][15], $viewtext['System'][3], 'index.php?section='.$section.'');
   }
}

 }else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-


//==============================================================================
// ------------------------------[ Functions  ]---------------------------------
//==============================================================================

function ReadModEntry($tpl, $viewtext, $boxheader, $table, $id){

$sql = "SELECT * FROM ".$table."_mods WHERE ID = ".$id."";

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



?>
