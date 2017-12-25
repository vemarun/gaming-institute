<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* server_management.php                           *
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

$pluginsystemname = (isset($_GET['plugin'])) ? $_GET['plugin']:'';
$sid = (isset($_GET['sid'])) ? $_GET['sid']:'';

 $tpl->set_var(array(
     "servermms_plugin"   => $viewtext['System'][89],
     "plugin"		      => $pluginsystemname,
     "section"            => $section
     
));


$sql = "SELECT * FROM ".$table."_plugins WHERE Systemname = '".$pluginsystemname."'";

$result = mysql_query($sql) OR die(mysql_error());
if(mysql_num_rows($result)){
  while($row = mysql_fetch_assoc($result)){
    $plugin['name'] = $row['Name'];
    $plugin['tablename'] = $row['Tablename'];
    $plugin['mms'] = $row['MMS'];
    $plugin['mmscolum'] = $row['MMSColum'];
  }

  if ($plugin['mms'] <> NULL){
    if (Table_Exists($plugin['tablename'])=== True){



//	echo 'GoGoGo<br><br><br>';


     $viewtext['Server'][22] = str_replace('%pluginserveridname%', $plugin['mms'], $viewtext['Server'][22]);

     $tpl->set_var(array(
     "servermms_plugin"                    => $plugin['name'],
     "servermms_discription"               => $viewtext['System'][89],
     "servermms_server_discription"        => $viewtext['System'][89],
     "servermms_options_discription"       => $viewtext['System'][65],
     "servermss_edit_pic_infotext"         => $viewtext['System'][11],
     "servermss_delete_pic_infotext"       => $viewtext['System'][12],
     "servermms_server_id_discription"     => $plugin['mms'],
     "servermms_info_general"              => $viewtext['System'][71],
     "servermms_id_discription"            => $viewtext['Server'][11],
     "back_button_text"     		           => $viewtext['System'][4],
     "servermms_new_server_id_discription" => $viewtext['Server'][22]
      ));

      if ($action <> 'execeditpsidcvar') $id = (isset($_POST['psidcvar'])) ? $_POST['psidcvar']:$id;


	if ($id== ""){ //+++---
	
    $viewtext['Server'][20] = str_replace('%pluginserveridname%', $plugin['mms'], $viewtext['Server'][20]);
    $viewtext['Server'][21] = str_replace('%pluginserveridname%', $plugin['mms'], $viewtext['Server'][21]);
	
	if ($action == ""){ //---+++

     $tpl->set_file("inhalt", "templates/server_management_mss.tpl.htm");

     $tpl->set_var(array(
     "servermms_action_discription"       => $viewtext['System'][40],
     "servermms_empty"                    => $viewtext['System'][113],
     "servermms_server_add_button_text"   => $viewtext['Server'][20],
     "servermms_server_edit"			  => $viewtext['System'][11],
     "servermms_show"                     => $viewtext['System'][39]
     ));

     $tpl->set_block("inhalt", "serveridenzeige", "serveridenzeige_handle");
     $tpl->set_block("inhalt", "serveridenzeigeempty", "serveridenzeigeempty_handle");

		$serveridsready = Servermmslist($table, $plugin['mmscolum'], $plugin['tablename'], $pluginsystemname);

        //     echo'<pre>';
        //    print_r($serveridsready);
        //    echo'</pre>';
			
if (isSet($serveridsready)){
		   
foreach($serveridsready as $key => $value) {




$tpl->set_var(array(
     "servermms_server_id"              => $key,
     "servermms_server_count"           => $value,
));

  $tpl->parse("serveridenzeige_handle", "serveridenzeige", true);

}



}else{


       $tpl->parse("serveridenzeigeempty_handle", "serveridenzeigeempty", true);
       }




//          }else{



} //---+++

if ($action == 'addpsidcvar'){
$tpl->set_file("inhalt", "templates/server_management_mss_add_edit.tpl.htm");
$tpl->set_var(array(
     "servermms_psidcvar"			                 => "",
     "editactionsid"					                 => "",
     "servermms_psidcvar_add_edit_discription" => $viewtext['Server'][20],
     "servermms_psidcvar_add_edit_button_text" => $viewtext['System'][41],
     ));
}

if ($action == 'editpsidcvar'){
$tpl->set_file("inhalt", "templates/server_management_mss_add_edit.tpl.htm");
$tpl->set_var(array(
     "servermms_psidcvar"			                 => $sid,
     "editactionsid"					                 => "&action=execeditpsidcvar&sid=".$sid."",
     "servermms_psidcvar_add_edit_discription" => $viewtext['Server'][21],
     "servermms_psidcvar_add_edit_button_text" => $viewtext['System'][7],
     ));
}

if ($action == "execeditpsidcvar"){
  $newsid = (isset($_POST['psidcvar'])) ? $_POST['psidcvar']:'';
  $viewtext['Server'][28] = str_replace('%pluginserveridname%', $plugin['mms'], $viewtext['Server'][28]);
  sqlexec("UPDATE ".$table."_server_plugins_mss SET Plugin_Server_ID ='".$newsid ."' WHERE (Plugin_Systemname = '".$pluginsystemname."') AND (Plugin_Server_ID = '".$sid."')");
  sqlexec("UPDATE ".$plugin['tablename']." SET ".$plugin['mmscolum']." ='".$newsid ."' WHERE ".$plugin['mmscolum']." = '".$sid."'");
  $tpl = Infobox($tpl, $plugin['name'], $viewtext['Server'][28], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$pluginsystemname.'');
}

if ($action == 'delpsidcvar'){
  $viewtext['Server'][23] = str_replace('%pluginserveridname%', $plugin['mms'], $viewtext['Server'][23]);
  $viewtext['Server'][23] = str_replace('%id%', $sid, $viewtext['Server'][23]);
  $tpl = Infoboxselect($tpl, $plugin['name'], $viewtext['Server'][23], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section='.$section.'&plugin='.$pluginsystemname.'', 'index.php?section='.$section.'&plugin='.$pluginsystemname.'&action=execdelpsidcvar&sid='.$sid.'');
}

if ($action == 'execdelpsidcvar'){
  $viewtext['Server'][24] = str_replace('%pluginserveridname%', $plugin['mms'], $viewtext['Server'][24]);
  $viewtext['Server'][24] = str_replace('%id%', $sid, $viewtext['Server'][24]);
  sqlexec("DELETE FROM ".$table."_server_plugins_mss WHERE (Plugin_Systemname = '".$pluginsystemname."') AND (Plugin_Server_ID = '".$sid."')");
  sqlexec("DELETE FROM ".$plugin['tablename']." WHERE ".$plugin['mmscolum']." = '".$sid."'");
  $tpl = Infobox($tpl, $plugin['name'], $viewtext['Server'][24], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$pluginsystemname.'');
}


}else{//+++---

if ($action == 'addserver'){

  $postserver = (isset($_POST['server'])) ? $_POST['server']:'';
  if ($postserver <> '0'){

  $sql = "SELECT * FROM ".$table."_server WHERE ID = '".$postserver."'";

   $result = mysql_query($sql) OR die(mysql_error());
   if(mysql_num_rows($result)){

     $sql2 = "SELECT * FROM ".$table."_server_plugins_mss WHERE (Plugin_Systemname = '".$pluginsystemname."') AND (Interface_Server_ID = '".$postserver."')";
     $result2 = mysql_query($sql2) OR die(mysql_error());
     if(mysql_num_rows($result2)){
       $viewtext['Server'][34] = str_replace('%pluginserveridname%', $plugin['mms'], $viewtext['Server'][34]);
       $tpl->set_var(array("servermms_warning_text" => $viewtext['Server'][34]));
     }else{

       sqlexec("INSERT INTO ".$table."_server_plugins_mss SET
         Plugin_Systemname='".$pluginsystemname."',
         Interface_Server_ID='".$postserver."',
         Plugin_Server_ID='".$id."'");
     }
   }else{
//    echo 'notexist';
   $tpl->set_var(array("servermms_warning_text" => $viewtext['Server'][2]));
   }
  }
  }
  

if ($action == 'removeserver'){
$interfaceid = (isset($_GET['iid'])) ? $_GET['iid']:'';

sqlexec("DELETE FROM ".$table."_server_plugins_mss WHERE (Plugin_Systemname = '".$pluginsystemname."') AND (Interface_Server_ID = '".$interfaceid."')");

}

  $tpl->set_file("inhalt", "templates/server_management_mss_m.tpl.htm");
           
  $tpl->set_block("inhalt", "serveranzeige", "serveranzeige_handle");
  $tpl->set_block("inhalt", "serveranzeigeempty", "serveranzeigeempty_handle");
  $tpl->set_block("inhalt", "scrollserverblock", "scrollserverblock_handle");


     $tpl->set_var(array(
     "addserver_button_text"                     => $viewtext['System'][109],
     "servermms_empty"                           => $viewtext['System'][113],
     "back_button_text"                          => $viewtext['System'][4],
     "servermms_server_id"                       => $id,
     "servermss_remove_pic_infotext"             => $viewtext['Server'][15],     
     "servermms_warning_text"                    => ""
      ));

$scrollboxempty = 1;
$noservercheck = 1;

$sql = "SELECT * FROM ".$table."_server ORDER BY Name_Short ASC";
$result = mysql_query($sql) OR die(mysql_error());
if(mysql_num_rows($result)){
  while($row = mysql_fetch_assoc($result)){
    
    $sql2 = "SELECT * FROM ".$table."_server_plugins_mss WHERE (Plugin_Systemname = '".$pluginsystemname."') AND (Interface_Server_ID = '".$row['ID']."')";

    $result2 = mysql_query($sql2) OR die(mysql_error());
    if(mysql_num_rows($result2)){
      while($row2 = mysql_fetch_assoc($result2)){

        if ($row2['Plugin_Server_ID'] == $id){
          $noservercheck = 0;
          $tpl->set_var(array(
           "servermms_server" => $row['Name_Short'],
           "servermms_id"     => $row2['Interface_Server_ID']
        ));
        $tpl->parse("serveranzeige_handle", "serveranzeige", true);

        }
      }
    }else{
      $scrollboxempty = 0;
      $tpl->set_var(array(
         "scrollservervalue" => $row['ID'],
         "scrollserver"      => $row['Name_Short']
      ));
      $tpl->parse("scrollserverblock_handle", "scrollserverblock", true);
    }
  }
}

if ($scrollboxempty == 1){
  $tpl->set_var(array(
         "scrollservervalue" => "0",
         "scrollserver"      => "--&nbsp;".$viewtext['Server'][19]."&nbsp;--"
      ));
      $tpl->parse("scrollserverblock_handle", "scrollserverblock", true);
}
if ($noservercheck == 1){
$tpl->parse("serveranzeigeempty_handle", "serveranzeigeempty", true);
}


           
           } //+++---
           
    }else{
        //Plugintable not found
        $tpl = Infobox($tpl, $plugin['name'], $viewtext['Plugins'][18], $viewtext['System'][4], 'javascript:history.back()');
    }
  }else{
    // This Plugin has no Multiserver-Support
   $tpl = Infobox($tpl, $plugin['name'], $viewtext['Server'][33], $viewtext['System'][4], 'javascript:history.back()');
  }
}else{
  // Plugin not found
  $tpl = Infobox($tpl, $viewtext['Menu'][16], $viewtext['Plugins'][16], $viewtext['System'][4], 'javascript:history.back()');
}

?>
