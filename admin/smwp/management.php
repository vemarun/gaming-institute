<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* management.php                                  *
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

if ($id != ""){   ////----------

$tid = (isset($_GET['tid'])) ? $_GET['tid']:'';

if(isset($tid)) settype($tid, "integer");


$extravalue = array(); // Array Leeren

###############################################################################


if ($section == "groupmember"){  //---

$extravalue[0]['tablecoll'] = 'inherit_order';
$extravalue[0]['template']  = $viewtext['Groups'][5];
$extravalueeditmenumsg1 =  $viewtext['Groups'][11];
$extravalueeditmenumsg2 =  $viewtext['Groups'][16];

$tpl->set_var(array("menue_extravalue_info_general" => $viewtext['System'][71]));

$source_tabel = 'groups';
$source_tabel_coll[0] = 'name';
$source_tabel_coll_show_space = '';

$source_tabel_extrainfos = '';

$target_tabel = 'admins';


$managesource_coll = 'group_id';
$managetarget_coll = 'admin_id';
$manage_tabel = 'admins_groups';

$show = $settings['show_clients'];
$view = $viewtext['System'][82];

$msg = $viewtext['Groups'][12];
$msgempty = $viewtext['System'][50];
$menue_source_description = $viewtext['Groups'][9];

  $tpl->set_var(array(
     "menue_target_menue"     => $viewtext['System'][82],
     "addtarget_button_text"  => $viewtext['System'][99],
     "menue_target_id"        => $viewtext['System'][68],
     "menue_target_name"      => $viewtext['System'][82],
     "menue_target_action"    => $viewtext['System'][40],
     "menue_target_remove"    => $viewtext['Groups'][6],
     "target_add_link"        => "index.php?section=groupmember&id=".$id."&action=addtarget",
     "back_link"              => "index.php?section=groups"
  ));
} //---

####################################

if ($section == "groupimmunity"){  //---

$source_tabel = 'groups';
$source_tabel_coll[0] = 'name';
$source_tabel_coll_show_space = '';

$source_tabel_extrainfos = '';

$target_tabel = 'groups';

$managesource_coll = 'group_id';
$managetarget_coll = 'other_id';
$manage_tabel = 'group_immunity';

$show = $settings['show_groups'];
$view = $viewtext['System'][83];

$msg = $viewtext['Groups'][13];
$msgempty = $viewtext['System'][50];
$menue_source_description = $viewtext['Groups'][10];

  $tpl->set_var(array(
     "menue_target_menue"     => $viewtext['System'][83],
     "addtarget_button_text"  => $viewtext['Groups'][0],
     "menue_target_id"        => $viewtext['System'][68],
     "menue_target_name"      => $viewtext['System'][83],
     "menue_target_action"    => $viewtext['System'][40],
     "menue_target_remove"    => $viewtext['Groups'][3],
     "target_add_link"        => "index.php?section=groupimmunity&id=".$id."&action=addtarget",
     "back_link"              => "index.php?section=groups"
  ));
} //---

###############################################################################



if ($section == "servergroupsgroups"){  //---

$source_tabel = 'servers';

$source_tabel_coll[0]= 'ip';
$source_tabel_coll[1]= 'port';
// $source_tabel_coll[2] = 'blala';
// $source_tabel_coll[3] = 'e.t.c.';
$source_tabel_coll_show_space = ':';


// Take the source_tabel_coll result and search in an extratable for more infos if exits!
$source_tabel_extrainfos = 'server';
$source_tabel_extrainfos_prefix = $table;
$source_tabel_extrainfos_coll = 'Name_Short';
// ------

$target_tabel = 'groups';
$managesource_coll = 'server_id';
$managetarget_coll = 'group_id';
$manage_tabel = 'servers_groups';

$show = $settings['show_servergroups'];

$view = $viewtext['System'][98] ;

$msg = $viewtext['Servergroups'][8];
$msgempty = $viewtext['System'][50];
$menue_source_description = $viewtext['Servergroups'][10];

  $tpl->set_var(array(
     "menue_target_menue"     => $viewtext['System'][83],
     "addtarget_button_text"  => $viewtext['Groups'][0],
     "menue_target_id"        => $viewtext['System'][68],
     "menue_target_name"      => $viewtext['System'][83],
     "menue_target_action"    => $viewtext['System'][40],
     "menue_target_remove"    => $viewtext['Groups'][3],
     "target_add_link"        => "index.php?section=servergroupsgroups&id=".$id."&action=addtarget",
     "back_link"              => "index.php?section=servergroups"
  ));
} //---

###############################################################################

if ($section == "clientgroups"){  //---

$source_tabel = 'admins';
$source_tabel_coll[0] = 'name';
$source_tabel_coll_show_space = '';

$source_tabel_extrainfos = '';

$target_tabel = 'groups';


$managesource_coll = 'admin_id';
$managetarget_coll = 'group_id';
$manage_tabel = 'admins_groups';

$show = $settings['show_groups'];
$view = $viewtext['System'][83];

$msg = $viewtext['Clients'][9];
$msgempty = $viewtext['System'][50];
$menue_source_description = $viewtext['Clients'][8];

  $tpl->set_var(array(
     "menue_target_menue"     => $viewtext['System'][83],
     "addtarget_button_text"  => $viewtext['Groups'][0],
     "menue_target_id"        => $viewtext['System'][68],
     "menue_target_name"      => $viewtext['System'][83],
     "menue_target_action"    => $viewtext['System'][40],
     "menue_target_remove"    => $viewtext['Groups'][3],
     "target_add_link"        => "index.php?section=clientgroups&id=".$id."&action=addtarget",
     "back_link"              => "index.php?section=clients"
  ));
} //---

################################################################################




if (count($extravalue) > 0){
$tpl->set_var(array("extravalue_colspan"  => count($extravalue)+ 4,
                    "editextravaluebacklink" => 'index.php?section='.$section.'&id='.$id.'',
                    "execeditextravalue" => 'index.php?section='.$section.'&action=execeditextravalue&id='.$id.'&tid='.$tid.'',
                    "edit_extravalue_button_text" => $viewtext['System'][7],
                    "back_extravalue_button_text" => $viewtext['System'][4]
                    ));
}else{
$tpl->set_var(array("extravalue_colspan"  => count($extravalue)+ 3));
}

  $sql = "SELECT * FROM ".$smtable."_".$source_tabel." WHERE id = ".$id."";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
      $sourcename = '';
      for ($x = 0; $x <= count($source_tabel_coll)-1; $x++){
        if ($x == 0){
          $source_tabel_coll_show_space_temp = '';
        }else{
          $source_tabel_coll_show_space_temp = $source_tabel_coll_show_space;
        }
        $sourcename = $sourcename.$source_tabel_coll_show_space_temp.$row[$source_tabel_coll[$x]];
      }
    }
  }

  if(!$source_tabel_extrainfos == ""){
    $sql = "SELECT * FROM ".$source_tabel_extrainfos_prefix."_".$source_tabel_extrainfos." WHERE Ip = '".$sourcename."'";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){
        $sourcename = $row[$source_tabel_extrainfos_coll];
      }
    }
  }

if (($action != "editextravalue") and ($action != "execeditextravalue")){ //--/--//


$tpl->set_file("inhalt", "templates/management.tpl.htm");

$tpl->set_block("inhalt", "extravaluemenueblock", "extravaluemenueblock_handle");

for ($x = 0; $x <= count($extravalue) - 1; $x++){
  $tpl->set_var(array("menue_extravalue"  => $extravalue[$x]['template']));
  $tpl->parse("extravaluemenueblock_handle", "extravaluemenueblock", true);
}


     $tpl->set_var(array(
     "menue_source"           => str_replace('%source%', $sourcename , $menue_source_description)
     ));

 if ($action == "addtarget"){  //---

 $tid   = (isset($_POST['target'])) ? $_POST['target']:'';
 
 if(isset($tid)) settype($tid, "integer");
 
 if (($tid != "") and ($tid != "0")){
 
 $check = 1;

  $targetname = managementgettargetname($sql, $smtable, $target_tabel, $tid);

  
  $msg = str_replace('%target%', $targetname, $msg);
  $msg = str_replace('%source%', $sourcename , $msg);
 
  $sql = "SELECT * FROM ".$smtable."_".$manage_tabel." WHERE ".$managesource_coll." = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {    while($row = mysql_fetch_assoc($result)){

    if ($tid == $row[$managetarget_coll]) $check = 0;

  }
  }
    if ($check == "1"){

if ($section == "groupmember"){



    sqlexec("INSERT INTO ".$smtable."_".$manage_tabel." SET
                ".$managesource_coll." ='".$id."',
                ".$managetarget_coll." ='".$tid."',
		    inherit_order='0'");

}else{

       sqlexec("INSERT INTO ".$smtable."_".$manage_tabel." SET
                ".$managesource_coll." ='".$id."',
                ".$managetarget_coll." ='".$tid."'");
}

    }else{
      $tpl = Infobox($tpl, $viewtext['Groups'][9], $msg, $viewtext['System'][4], 'index.php?section='.$section.'&id='.$id);
    }
  }

 }//-

  if ($action == "removetarget"){ // --

  //###

   if ($tid != "") sqlexec("DELETE FROM ".$smtable."_".$manage_tabel." WHERE (".$managesource_coll." = ".$id.") AND (".$managetarget_coll." = ".$tid.")" );

   } // --

  $tpl->set_var(array("back_button_text"           => $viewtext['System'][4]));

  $tpl->set_block("inhalt", "memberanzeigeempty", "memberanzeigeempty_handle");
  $tpl->set_block("inhalt", "scrolltargetblock", "scrolltargetblock_handle");
  $tpl->set_block("inhalt", "memberanzeige", "memberanzeige_handle");

  $member = 0;
  $targetsempty = 1;
  $inhaltint = 0;
  $show_view = 0;
  
  $all_entrys = get_hits_management($smtable."_".$manage_tabel, $managesource_coll, $id);
  if ($current_site > ceil($all_entrys / $show)) $current_site = 1;

  $start_entry = ($current_site * $show - $show);
  $end_entry = ($start_entry) +  $show;
  
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$view, $start_entry, $all_entrys, $show);
  $tpl = site_links($tpl, $all_entrys, $show, $current_site, $section, $id, '', $searchcat, $searchstring, '', $viewtext);


  $sql = "SELECT * FROM ".$smtable."_".$target_tabel." ORDER BY id ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {    while($row = mysql_fetch_assoc($result)){
  
      $sql2 = "SELECT * FROM ".$smtable."_".$manage_tabel." WHERE ".$managesource_coll." = ".$id."";

    $result2 = mysql_query($sql2) OR die(mysql_error());
    if(mysql_num_rows($result2))
    {    while($row2 = mysql_fetch_assoc($result2)){

           if ($row['id'] == $row2[$managetarget_coll]){
           $show_view++;

          if (($show_view >= $start_entry +1) and ($end_entry >= $show_view)){
               $member = 1;
               for ($x = 0; $x <= count($extravalue) - 1; $x++){
                 $extravaluetemp[$x] = $row2[$extravalue[$x]['tablecoll']];
                 }

             }else{
                 $member = 2;
      }
           }
         }
    }
    if ($member == "1"){
      $tpl->set_var(array(
         "target_id"          => $row['id'],
         "target_name"        => $row['name'],
         "target_remove_link" => "index.php?section=".$section."&id=".$id."&action=removetarget&tid=".$row['id']
      ));
     
      $inhaltint = $inhaltint + 1;

      if (count($extravalue) > 0){

        $tpl->set_file("inhalt1".$inhaltint."","templates/inc/management_extravalue.tpl.htm");
        $tpl->set_block("inhalt1".$inhaltint."", "extravalueblock", "extravalueblock_handle1".$inhaltint."");
        $tpl->set_block("inhalt1".$inhaltint."", "extravalueeditblock", "extravalueeditblock_handle2".$inhaltint."");

        for ($x = 0; $x <= count($extravalue) - 1; $x++){
        
            $tpl->set_var(array("extravalue"  => $extravaluetemp[$x]));
            $tpl->parse("extravalueblock_handle1".$inhaltint."", "extravalueblock", true);
        }

        $tpl->set_file("inhalt2".$inhaltint."","templates/inc/management_extravalue.tpl.htm");
        $tpl->set_block("inhalt2".$inhaltint."", "extravalueblock", "extravalueblock_handle3".$inhaltint."");
        $tpl->set_block("inhalt2".$inhaltint."", "extravalueeditblock", "extravalueeditblock_handle4".$inhaltint."");
        $tpl->set_var(array("extravalue_edit_link"  => "index.php?section=".$section."&action=editextravalue&id=".$id."&tid=".$row['id']));
        $tpl->set_var(array("menue_extravalue_edit"  => $viewtext['System'][11]));
        $tpl->parse("extravalueeditblock_handle4".$inhaltint."", "extravalueeditblock", true);
        $tpl->set_var(array("eee" => $tpl->parse("out", "inhalt1".$inhaltint."")));
        $tpl->set_var(array("fff" => $tpl->parse("out", "inhalt2".$inhaltint."")));

      }

      $tpl->parse("memberanzeige_handle", "memberanzeige", true);


    }

    if ($member == '0'){
    


      $targetsempty = 0;
      $tpl->set_var(array(
         "scrolltargetvalue" => $row['id'],
         "scrolltarget"      => $row['name']
      ));
      $tpl->parse("scrolltargetblock_handle", "scrolltargetblock", true);
    }

   $member = 0;

  }
  }

  if ($targetsempty == "1"){

    $tpl->set_var(array(
         "scrolltargetvalue" => '0',
         "scrolltarget"      => '---------'
      ));

   $tpl->parse("scrolltargetblock_handle", "scrolltargetblock", true);

   }

  if ($inhaltint == "0"){
   
    $tpl->set_var(array( "targets_empty" => $msgempty));
    $tpl->parse("memberanzeigeempty_handle", "memberanzeigeempty", true);
   }

   }else{ //--+

     $targetname = managementgettargetname($sql, $smtable, $target_tabel, $tid);

     if ($action == "editextravalue"){

     $tpl->set_file("inhalt", "templates/management_add_edit.tpl.htm");


       $extravalueeditmenumsg1 = str_replace('%target%', $targetname, $extravalueeditmenumsg1);
       $extravalueeditmenumsg1 = str_replace('%source%', $sourcename , $extravalueeditmenumsg1);

     $tpl->set_var(array("menue_extravalue_add_edit" => $extravalueeditmenumsg1));

     $tpl->set_block("inhalt", "editextravalueblock", "editextravalueblock_handle");

         $sql = "SELECT * FROM ".$smtable."_".$manage_tabel." WHERE ".$managesource_coll." = ".$id." AND ".$managetarget_coll." = ".$tid."";

         $result = mysql_query($sql) OR die(mysql_error());
         if(mysql_num_rows($result))
         {    while($row = mysql_fetch_assoc($result)){
         

         for ($x = 0; $x <= count($extravalue) - 1; $x++){
       
          $tpl->set_var(array("menue_extravalue"  => $extravalue[$x]['template'],
                              "value_extravalue"  => $row[$extravalue[$x]['tablecoll']],
                              "name_extravalue"   => $extravalue[$x]['tablecoll']
                              ));
       
         $tpl->parse("editextravalueblock_handle", "editextravalueblock", true);
       }

         }
         }

     } //--+

     if ($action == "execeditextravalue"){ //--++
     


       for ($x = 0; $x <= count($extravalue) - 1; $x++){

       $extravaluepost = (isset($_POST[$extravalue[$x]['tablecoll']])) ? $_POST[$extravalue[$x]['tablecoll']]:'';

       sqlexec("UPDATE ".$smtable."_".$manage_tabel."
               SET ".$extravalue[$x]['tablecoll']."='".$extravaluepost."'
               WHERE ".$managesource_coll." = ".$id."
               AND ".$managetarget_coll." = ".$tid.""
              );


       $extravalueeditmenumsg2 = str_replace('%target%', $targetname, $extravalueeditmenumsg2);
       $extravalueeditmenumsg2 = str_replace('%source%', $sourcename , $extravalueeditmenumsg2);
       $tpl = Infobox($tpl, $viewtext['System'][98], $extravalueeditmenumsg2, $viewtext['System'][3], 'index.php?section='.$section.'&id='.$id.'');
       
       }
     
     
     } //--++
     
     

   }


   } ////----------
   
}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-

?>
