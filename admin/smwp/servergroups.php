<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* servergroups.php                                *
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

if ($usercheck['sqladmins'] == "1"){ // -+-+-


if ($settings['servergroups_enabled'] == "1"){ // -+++-

$tpl->set_var(array(
  "add_server_button_text"     => $viewtext['System'][109],
  "menue_server_ip"            => $viewtext['System'][107],
  "menue_server_port"          => $viewtext['System'][108]
));

if ($action == ""){ //--

  $tpl->set_var(array(
    "menue_servergroups"         => $viewtext['Menu'][18],
    "menue_server_id"            => $viewtext['System'][68],
    "menue_server_name"          => $viewtext['System'][126],
    "menue_server_groups"        => $viewtext['System'][98] ,
    "menue_server_options"       => $viewtext['System'][65],
    "server_delete_pic_infotext" => $viewtext['System'][111],
    "menue_server_edit"          => $viewtext['System'][11],
    "server_edit_pic_infotext"   => $viewtext['System'][110]
  ));

  $tpl->set_file("inhalt", "templates/servergroups.tpl.htm");
  $tpl->set_block("inhalt", "servergroupsanzeigeempty", "servergroupsanzeigeempty_handle");
  $tpl->set_block("inhalt", "servergroupsanzeige", "servergroupsanzeige_handle");

  // Next Page Funktion
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$smtable."_servers"));
  if ($current_site > ceil($all_entrys / $settings['show_server'])) $current_site = 1;
  $start_entry = $current_site * $settings['show_server'] - $settings['show_server'];
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['System'][89], $start_entry, $all_entrys, $settings['show_server']);
  $tpl = site_links($tpl, $all_entrys, $settings['show_server'], $current_site, $section, '', '', $searchcat, $searchstring, '', $viewtext);
  // ----

  $sql = "SELECT * FROM ".$smtable."_servers ORDER BY id ASC LIMIT ".$start_entry.", ".$settings['show_server']."";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

    $sql2 = "SELECT * FROM ".$table."_server WHERE Ip = '".$row['ip'].':'.$row['port']."'";
    $result2 = mysql_query($sql2) OR die(mysql_error());
    if(mysql_num_rows($result2)){
      while($row2 = mysql_fetch_assoc($result2)){

      $tpl->set_var(array("server_name" => $row2['Name_Short']));
      }
    }else{
      $tpl->set_var(array("server_name" => "?"));
    }
    
    
    $group_count = get_hits_management($smtable."_servers_groups", "server_id", $row['id']);
    
    $tpl->set_var(array(
      "server_groups"  => $group_count,
      "server_id"      => $row['id'],
      "server_ip"      => $row['ip'],
      "server_port"    => $row['port']
    ));
    $tpl->parse("servergroupsanzeige_handle", "servergroupsanzeige", true);
    }
  }else{
    $tpl->set_var(array("menue_server_empty" => $viewtext['System'][113]));
    $tpl->parse("servergroupsanzeigeempty_handle", "servergroupsanzeigeempty", true);
  }
}//--




 if (($action == "delserver") or ($action == "execdelserver") or ($action == "editserver") or ($action == "execeditserver")){ //--

 $sql = "SELECT * FROM ".$smtable."_servers WHERE id = '".$id."'";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){
        $servername = $row['ip'].":".$row['port'];
        $server_ip =  $row['ip'];
        $server_port =  $row['port'];
      }
    }

  $sql = "SELECT * FROM ".$table."_server WHERE Ip = '".$servername."'";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){
        $servername = $row['Name_Short'];
      }
    }

 }





if (($action == "addserver") or ($action == "editserver")){ //--

  $tpl->set_file("inhalt", "templates/servergroups_add_edit_server.tpl.htm");
  $tpl->set_block("inhalt", "scrollserverblock", "scrollserverblock_handle");

  $tpl->set_var(array(
    "menue_servergroups_info_serverlist"  => $viewtext['Servergroups'][3],
    "menue_servergroups_info_ownip"       => $viewtext['Servergroups'][6],
    "menue_servergroups_server"           => $viewtext['System'][89],
    "back_server_button_text"             => $viewtext['System'][4],
    "serverlistcheckedyes"                => "CHECKED",
    "serverowncheckedyes"                 => ""

  ));


if ($action == "addserver"){ //--

  $tpl->set_var(array(
    "execaddserveraction"                 => "execaddserver",
    "add_edit_server_button_text"         => $viewtext['System'][109],
    "menue_servergroups_server_add_edit"  => $viewtext['Servergroups'][7],
    "server_ip"                           => "",
    "server_port"                         => ""
));
}

if ($action == "editserver"){ //--

  $tpl->set_var(array(
    "execaddserveraction"                 => "execeditserver&id=".$id."",
    "add_edit_server_button_text"         => $viewtext['System'][7],
    "menue_servergroups_server_add_edit"  => $viewtext['Servergroups'][1]
));
}


$empty = 1;
$found = 0;

$sql = "SELECT * FROM ".$table."_server ORDER BY Name_Short ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

      list($ip, $port) = explode(":", $row['Ip']);



   $sql2 = "SELECT * FROM ".$smtable."_servers WHERE ip = '".$ip."' AND port = '".$port."'";




      $result2 = mysql_query($sql2) OR die(mysql_error());

      if(!mysql_num_rows($result2)){
      
        $empty = 0;

        $tpl->set_var(array(
          "scrollserverid"   => $row['ID'],
          "scrollserver"     => $row['Name_Short']."&nbsp;&nbsp;(".$row['Ip'].")",
          "scrollserveridselected" => ""
        ));

        $tpl->parse("scrollserverblock_handle", "scrollserverblock", true);
  
      }

     if ($action == "editserver"){

       $sql3 = "SELECT * FROM ".$smtable."_servers WHERE ip = '".$ip."' AND port = '".$port."' AND id = '".$id."'";
       $result3 = mysql_query($sql3) OR die(mysql_error());
       if(mysql_num_rows($result3)){

         $found = 1;

         $tpl->set_var(array(
           "scrollserverid"   => $row['ID'],
           "scrollserver"     => $row['Name_Short']."&nbsp;&nbsp;(".$row['Ip'].")",
           "scrollserveridselected" => "SELECTED",
           "server_ip"                           => "",
           "server_port"                         => ""
         ));
         $tpl->parse("scrollserverblock_handle", "scrollserverblock", true);
       }
     }

    }
  }


  if (($empty == 1) and ($found== 0)){
  
      $tpl->set_var(array(
      "scrollserverid"   => "0",
      "scrollserver"     => $viewtext['Servergroups'][5],
      "serverlistcheckedyes" => "",
      "serverowncheckedyes"  => "CHECKED"
    ));
      $tpl->parse("scrollserverblock_handle", "scrollserverblock", true);

    }

  if (($found == 0) and ($action == "editserver")){

  $tpl->set_var(array(
    "server_ip"                           => $server_ip,
    "server_port"                         => $server_port,
    "serverlistcheckedyes" => "",
    "serverowncheckedyes"  => "CHECKED"
  ));
  

  }


} // --



if (($action == "execaddserver") or ($action == "execeditserver")){ //--++

  $ok = 1;
  $serveraddart = (isset($_POST['addserverart'])) ? $_POST['addserverart']:'';

    if ($serveraddart == "0"){ //-------------

    $serverid = (isset($_POST['server'])) ? $_POST['server']:'';

    $sql = "SELECT * FROM ".$table."_server WHERE ID = '".$serverid."'";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){
        list($ip, $port) = explode(":", $row['Ip']);
        $servername = $row['Name_Short'];
        $fullip = $row['Ip'];
      }
    }else{
      $ok = 0;
      $viewtext['Servergroups'][13] = str_replace('%id%', $serverid, $viewtext['Servergroups'][13]);
      $tpl = Infobox($tpl, $viewtext['Menu'][18], $viewtext['Servergroups'][13], $viewtext['System'][3], 'index.php?section=servergroups');
      
      
    }

  }else{ //--------------------------------
    $ip = (isset($_POST['serverip'])) ? $_POST['serverip']:'';
    $port = (isset($_POST['serverport'])) ? $_POST['serverport']:'';
    $servername = $viewtext['System'][45];
    $fullip = $ip.':'.$port;
	//$serverid = 0;

  } //----
  

    
  
    if($action == "execeditserver") $sql = "SELECT * FROM ".$smtable."_servers WHERE ip = '".$ip."' AND port = '".$port."' AND id <> '".$id."'";
	//if(($action == "execeditserver") and (!$serverid == 0)) $sql = "SELECT * FROM ".$smtable."_servers WHERE ip = '".$ip."' AND port = '".$port."' AND id <> '".$id."'";
   	if($action == "execaddserver") $sql = "SELECT * FROM ".$smtable."_servers WHERE ip = '".$ip."' AND port = '".$port."'";


	
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      $viewtext['Servergroups'][15] = str_replace('%ip%', $fullip, $viewtext['Servergroups'][15]);
      $tpl = Infobox($tpl, $viewtext['Servergroups'][7], $viewtext['Servergroups'][15], $viewtext['System'][4], 'javascript:history.back()');
      $ok = 0;
    }

   if ($ok == "1"){
    $checkip = checkipp($ip, $viewtext);
    if ($checkip == 1){
     $checkport = checkport ($port, $viewtext);
      if ($checkport == 1){
        if ($action == "execaddserver"){
          $viewtext['Servergroups'][14] = str_replace('%server%', $servername, $viewtext['Servergroups'][14]);
          $viewtext['Servergroups'][14] = str_replace('%ip%', $ip.":".$port, $viewtext['Servergroups'][14]);
          sqlexec("INSERT INTO ".$smtable."_servers SET ip='".$ip."', port='".$port."'" );
          $tpl = Infoboxselect($tpl, $viewtext['Menu'][18], $viewtext['Servergroups'][14], $viewtext['Menu'][18], $viewtext['Servergroups'][4], 'index.php?section=servergroups', 'index.php?section=servergroups&action=addserver' );
        }

        if  ($action == "execeditserver"){
          sqlexec("UPDATE ".$smtable."_servers SET ip='".$ip."', port='".$port."' WHERE id = ".$id."");
          $tpl = Infoboxselect($tpl, $viewtext['Menu'][18], $viewtext['Servergroups'][16], $viewtext['System'][4], $viewtext['System'][3], 'index.php?section=servergroups&action=editserver&id='.$id.'', 'index.php?section=servergroups' );
        }


      }else{
      $tpl = Infobox($tpl, $viewtext['Servergroups'][7], $checkport, $viewtext['System'][4], 'javascript:history.back()');
      }
    }else{
      $tpl = Infobox($tpl, $viewtext['Servergroups'][7], $checkip, $viewtext['System'][4], 'javascript:history.back()');
    }
  }
}// --++


if ($action == "delserver"){
  $viewtext['Servergroups'][11] = str_replace('%server%', $servername, $viewtext['Servergroups'][11]);
  $tpl = Infoboxselect($tpl, $viewtext['Servergroups'][2], $viewtext['Servergroups'][11], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section=servergroups', 'index.php?section=servergroups&action=execdelserver&id='.$id.'' );
}

if ($action == "execdelserver"){
  $viewtext['Servergroups'][12] = str_replace('%server%', $servername, $viewtext['Servergroups'][12]);
  sqlexec("DELETE FROM ".$smtable."_servers WHERE id = ".$id."");
  sqlexec("DELETE FROM ".$smtable."_servers_groups WHERE server_id = ".$id."");
  $tpl = Infobox($tpl, $viewtext['Servergroups'][2], $viewtext['Servergroups'][12], $viewtext['System'][3], 'index.php?section=servergroups');
}


}else{// -+++-
   $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['Servergroups'][0], $viewtext['System'][3], 'index.php');
} // -+++-


}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-


?>
