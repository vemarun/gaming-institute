<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* server.php                                      *
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

if ($usercheck['editserversettings'] == "1"){ //-+-+-

 $tpl->set_var(array(
     "server_ip_discription"        => $viewtext['System'][107],
     "server_port_discription"      => $viewtext['System'][108] ,
     "section"                      => $section,
     "execaction"                   => "exec".$action."",
     "actionid"                     => "&id=".$id.""
     ));


if ($action == ""){ //--

$question_pic = '<img src="inc/pics/question_gray.gif" width="15" height="15">';




$tpl->set_file("inhalt", "templates/server.tpl.htm");

 $tpl->set_var(array(
     "server_discription"           => $viewtext['System'][89],
     "server_name_discription"      => $viewtext['System'][126],
     "server_status_discription"    => $viewtext['System'][90],
     "server_mod_discription"       => $viewtext['Server'][7],
     "server_os_discription"        => $viewtext['Server'][8],
     "server_vac_discription"       => $viewtext['Server'][9],
     "server_options_discription"   => $viewtext['System'][65],
     "server_empty"                 => $viewtext['System'][113],
     "server_edit_pic_infotext"     => $viewtext['System'][110],
     "server_delete_pic_infotext"   => $viewtext['System'][111],
     "add_server_button_text"       => $viewtext['System'][109],
     "server_id_discription"        => $viewtext['System'][68]
     ));


     $tpl->set_block("inhalt", "serveranzeige", "serveranzeige_handle");
     $tpl->set_block("inhalt", "serveranzeigeempty", "serveranzeigeempty_handle");

	 
	 	 //Set startcount of errow handlecount
         $handle_count = 1;
	 
	 
     $sql = "SELECT * FROM ".$table."_server ORDER BY Name_Short ASC";




     $result = mysql_query($sql) OR die(mysql_error());
     if(mysql_num_rows($result)){
       while($row = mysql_fetch_assoc($result)){
	   
         // ++ handlecount for errows
        $handle_count++;

      list($ip, $port) = explode(":", $row['Ip']);


             if ( !($socket = @fsockopen($ip, $port, $errno, $errstr, $settings['server_timeout'])) ){
      

      
        $tpl->set_var(array("server_status"    => $viewtext['Server'][14]));
        $os_pic = $question_pic;
        $vac_pic = $question_pic;
        $infos["name"] = $row['Name_Short'];
		$infos["directory"] = '';

      }else{
      

        $verbindung = new HLServerAbfrage;
        $verbindung -> hlserver($row['Ip']);
        error_reporting(0);
        $infos = $verbindung->infos();
        // $ping = $verbindung->ping();
        //echo $ping;
        error_reporting(E_ALL);

$mod_pic = '';
if ($infos["os"] == "l") $os_pic = '<img src="inc/pics/os_linux.png" width="16" height="16">';
if ($infos["os"] == "w") $os_pic = '<img src="inc/pics/os_win.png" width="18" height="16">';
if ($infos["secure"] == "1") $vac_pic = '<img src="inc/pics/vac.gif" width="16" height="16">';
if ($infos["secure"] == "0") $vac_pic = '<img src="inc/pics/no_vac.gif" width="16" height="16">';


        $tpl->set_var(array("server_status"    => $viewtext['Server'][13]));
      }


	  // Start to get Mod Icon from Mod Database
$tpl = PluginGameIcon($tpl, $table, 'folder', $infos["directory"], $handle_count, '');
	  
	  

      $infos['name'] = BadStringFilterShow($infos['name']);


      
if (strlen($infos["name"]) > 52) $infos["name"] = substr($infos["name"], 0, 50) . "...";

         $tpl->set_var(array(
           "server_id"        => $row['ID'],
           "server_os"        => $os_pic,
           "server_vac"       => $vac_pic,
           "server_name"      => $infos["name"],
           "server_ip"        => $ip,
           "server_port"      => $port
           ));
           
         $tpl->parse("serveranzeige_handle", "serveranzeige", true);
       }
     }else{
       $tpl->parse("serveranzeigeempty_handle", "serveranzeigeempty", true);
     }

} //--

if (($action == "addserver") or ($action == "editserver")){ //---

$tpl->set_file("inhalt", "templates/server_add_edit.tpl.htm");

 $tpl->set_var(array(
     "server_edit_add_discription"      => $viewtext['System'][111],
     "server_info_general"              => $viewtext['System'][71],
     "server_short_name_discription"    => $viewtext['Server'][3],
     "back_server_button_text"          => $viewtext['System'][4],
     "apply_server_button_text"         => $viewtext['System'][7],
     "server_short_name_info" 	        => $viewtext['Server'][16],
     "server_rcon_discription" 	        => $viewtext['Server'][10],
     "server_info_ftp" 	                => $viewtext['Server'][0],
     "server_pw_discription"            => $viewtext['System'][61],
     "server_remotepath_discription"    => $viewtext['Server'][1],
     "server_remotepath_info_1" 	    => $viewtext['Server'][5],
     "server_remotepath_info_2" 	    => $viewtext['Server'][6],
	 "server_ftp_username_discription" 	=> $viewtext['System'][60]
     
     
     ));

if ($action == "addserver"){
 $tpl->set_var(array("server_edit_add_discription" => $viewtext['System'][109]));
 
 

 
 
           $tpl->set_var(array(
           "server_short_name"   => "",
           "server_ip"           => "",
		   "server_rcon"         => ""
           ));
 
}

if ($action == "editserver"){

$sql = "SELECT * FROM ".$table."_server WHERE ID = ".$id."";




     $result = mysql_query($sql) OR die(mysql_error());
     if(mysql_num_rows($result)){
       while($row = mysql_fetch_assoc($result)){

    $row['Name_Short']= BadStringFilterShow($row['Name_Short']);

	   $tpl->set_var(array(
           "server_short_name"   => $row['Name_Short'],
           "server_ip"           => $row['Ip'],
           "server_rcon"         => $row['rcon'],
           "server_ftp_ip"       => $row['ftp_ip'],
		   "server_ftp_username" => $row['ftp_username'],
           "server_pw"           => $row['ftp_pw'],
           "server_remotepath"   => $row['ftp_path']
           ));
           
        }
      }

 $tpl->set_var(array("server_edit_add_discription" => $viewtext['System'][110]));
}


} //--

if (($action == "execaddserver") or ($action == "execeditserver")){ //----

$server['shortname'] = (isset($_POST['shortname'])) ? $_POST['shortname']:'';
$server['ip'] = (isset($_POST['ip'])) ? $_POST['ip']:'';

$server['rcon'] = (isset($_POST['tja'])) ? $_POST['tja']:'';
$server['ftpip'] = (isset($_POST['ftpip'])) ? $_POST['ftpip']:'';
$server['ftpusername'] = (isset($_POST['ftpusername'])) ? $_POST['ftpusername']:'';
$server['ftpassword'] = (isset($_POST['ftptja'])) ? $_POST['ftptja']:'';
$server['ftppath'] = (isset($_POST['ftppath'])) ? $_POST['ftppath']:'';



    $server['shortname'] = BadStringFilterSave($server['shortname']);

$server['ip'] = str_replace(' ','',$server['ip']);
$server['ftpip'] = str_replace(' ','',$server['ftpip']);



if (($server['shortname'] == "") or ($server['ip'] == "")){
  $servercheck = 0;
  $tpl = Infobox($tpl, $viewtext['System'][89], $viewtext['System'][25], $viewtext['System'][4], 'javascript:history.back()');
}else{

if (substr_count($server['ip'], ':') <> 1) {

  $servercheck = 0;
  $tpl = Infobox($tpl, $viewtext['System'][89], $viewtext['Server'][29], $viewtext['System'][4], 'javascript:history.back()');

  }else{
  

  list($ip, $port) = explode(":", $server['ip']);
  //list($ftpip, $ftpport) = explode(":", $server['ftpip']);

  $servercheck = 1;
}

}

} //----


if (($action == "execaddserver") and ($servercheck == 1)){ //-+-+-

  $sql = "SELECT * FROM ".$table."_server WHERE Ip = '".$server['ip']."'";
  $result = mysql_query($sql) OR die(mysql_error());

  if(mysql_num_rows($result)){
  $tpl = Infobox($tpl, $viewtext['System'][89], $viewtext['Server'][30], $viewtext['System'][4], 'javascript:history.back()');
  }else{

    sqlexec("INSERT INTO ".$table."_server SET
              Name_Short='".$server['shortname']."',
              Ip='".$server['ip']."',
              rcon='".$server['rcon']."',
              ftp_ip='".$server['ftpip']."',
              ftp_username='".$server['ftpusername']."',
			  ftp_pw='".$server['ftpassword']."',
              ftp_path='".$server['ftppath']."'");

     $viewtext['Server'][26] = str_replace('%serverip%', $server['ip'], $viewtext['Server'][26]);
     $tpl = Infobox($tpl, $viewtext['System'][89], $viewtext['Server'][26], $viewtext['System'][3], 'index.php?section=server');
  }


} //-+-+-



if (($action == "execeditserver") and ($servercheck == 1)){ //-+++-


  $sql = "SELECT * FROM ".$table."_server WHERE
  ((Ip = '".$server['ip']."') AND (ID <>'".$id."'))";
  $result = mysql_query($sql) OR die(mysql_error());
   if(mysql_num_rows($result)){
  $tpl = Infobox($tpl, $viewtext['System'][89], $viewtext['Server'][30], $viewtext['System'][4], 'javascript:history.back()');
  }else{

      sqlexec("UPDATE
                  ".$table."_server
      	       SET
                  Name_Short ='".$server['shortname']."',
                  Ip ='".$server['ip']."',
                  rcon ='".$server['rcon']."',
                  ftp_ip ='".$server['ftpip']."',
				  ftp_username='".$server['ftpusername']."',
                  ftp_pw ='".$server['ftpassword']."',
                  ftp_path ='".$server['ftppath']."'
               WHERE
  	              ID = ".$id.""
             );

     $viewtext['Server'][27] = str_replace('%serverip%', $server['ip'], $viewtext['Server'][27]);
     $tpl = Infobox($tpl, $viewtext['System'][89], $viewtext['Server'][27], $viewtext['System'][3], 'index.php?section=server');
  
   }

} //-+++-


if (($action == "delserver") or ($action == "execdelserver")){ //+++

$sql = "SELECT * FROM ".$table."_server WHERE ID = ".$id."";

     $result = mysql_query($sql) OR die(mysql_error());
     if(mysql_num_rows($result)){
       while($row = mysql_fetch_assoc($result)){
       
         $viewtext['Server'][31] = str_replace('%serverip%', $row['Ip'], $viewtext['Server'][31]);
         $viewtext['Server'][32] = str_replace('%serverip%', $row['Ip'], $viewtext['Server'][32]);
       
       }
     }



}


if ($action == "delserver"){ //+

$tpl = Infoboxselect($tpl, $viewtext['System'][111], $viewtext['Server'][31], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section=server', 'index.php?section=server&action=execdelserver&id='.$id.'' );

}// ++

if ($action == "execdelserver"){ //+

sqlexec("DELETE FROM ".$table."_server WHERE ID = ".$id."");
sqlexec("DELETE FROM ".$table."_server_plugins_mss WHERE Interface_Server_ID = '".$id."'");
$tpl = Infobox($tpl, $viewtext['System'][111], $viewtext['Server'][32], $viewtext['System'][3], 'index.php?section=server');

}//+

}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-

?>
