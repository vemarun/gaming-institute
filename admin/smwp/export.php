<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* export.php                                      *
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

$outputfile          = array();
$outputfile_value    = array();
$outputfile[0]       = 'admins_simple.ini';
$outputfile_value[0] = '0';
$outputfile[1]       = 'admins.cfg';
$outputfile_value[1] = '1';
$outputfile[2]       = 'admin_groups.cfg';
$outputfile_value[2] = '2';
$outputfile[3]       = 'admin_overrides.cfg';
$outputfile_value[3] = '3';

$outputfile_select_value = (isset($_GET['fileid'])) ? $_GET['fileid']:'';

if ($outputfile_select_value == ""){
  $outputfile_select_value = (isset($_POST['outputfile'])) ? $_POST['outputfile']:'0';
}

$outputfile_select = $outputfile[$outputfile_select_value];

$exportart = (isset($_GET['exportart'])) ? $_GET['exportart']:'';
if ($exportart == ""){
  $exportart = (isset($_POST['exportart'])) ? $_POST['exportart']:'0';
}

$sid = (isset($_GET['sid'])) ? $_GET['sid']:'';

if ($sid == ""){
$sid = (isset($_POST['server'])) ? $_POST['server']:'0';
}



if ($action == "upload"){ // -- -- --

$ftpsid = (isset($_GET['ftpsid'])) ? $_GET['ftpsid']:'';

$sql = "SELECT * FROM ".$table."_server WHERE ID = '".$ftpsid."'";
$result = mysql_query($sql) OR die(mysql_error());
if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
	    list($ftp_ip, $ftp_port) = explode(":", $row['ftp_ip']);
		$ftp_username = $row['ftp_username']; 
		$ftp_password = $row['ftp_pw'];
		$ftp_remotepath = $row['ftp_path'];
	}
}else{
   $ftp_ip = ''; 
   $ftp_port = ''; 
   $ftp_username = '';
   $ftp_password = '';
   $ftp_remotepath = '';
}

// $outputfilename = $outputfile[$fileid];

$outputfilecode = (isset($_POST['code'])) ? $_POST['code']:'';
$outputfilecode =  stripslashes($outputfilecode);

$file_content_output = file_get_contents("output/".$outputfile_select."");
$file_content_output = str_replace('%insert%', $outputfilecode, $file_content_output);
$file_content_output = str_replace('%date%', date("d/m/y"), $file_content_output);

$status = $viewtext['Upload'][1];
$con = array();
$tmpfname = Createtempfile($file_content_output, $viewtext, $viewtext);
$status = str_replace('%msg%', $tmpfname['msg'].'<br>', $status);

$status = $status.$viewtext['Upload'][0];
$con = array();
$con = Ftpconnect($ftp_ip, $ftp_port, $ftp_username, $ftp_password, $viewtext, $viewtext);
$status = str_replace('%msg%', $con['msg'].'<br>', $status);
@ftp_pasv($con['id'], true);

$ftp_outputfile = '/'.$ftp_remotepath.'/'.$outputfile_select;
$ftp_outputfile = str_replace('//', '/', $ftp_outputfile);

$status = $status.$viewtext['Upload'][2];
if (!$upload = @ftp_put($con['id'], $ftp_outputfile, $tmpfname['name'], FTP_BINARY)){
  $status = str_replace('%msg%', $viewtext['Upload'][10].'<br>', $status);
}else{
  $status = str_replace('%msg%', $viewtext['System'][32].'<br>', $status);
  ftp_quit($con['id']);
}

$status = $status.$viewtext['Upload'][3];
if (!$unlink = @unlink($tmpfname['name'])){
  $status = str_replace('%msg%', $viewtext['Upload'][9].'<br>', $status);
}else{
  $status = str_replace('%msg%', $viewtext['System'][32].'<br>', $status);
}

$tpl = Infobox($tpl, $viewtext['System'][119].'&nbsp;'.$outputfile_select, $status, $viewtext['System'][3], 'index.php?section='.$section.'&action=execexportcreate&sid='.$ftpsid.'&exportart=1&fileid='.$outputfile_select_value.'');










}else{ // -- -- --







$tpl->set_file("inhalt", "templates/export.tpl.htm");

if (($action == "") or ($action == "execexportcreate")){ //--


  $tpl->set_block("inhalt", "servergroupsblock", "servergroupsblock_handle");


  $tpl->set_var(array("menue_export_servergroups" => $viewtext['System'][89]));



  if ($settings['servergroups_enabled'] == 1){ // -+-+-
  


    $tpl->set_var(array("scrollservergroupsdisabled" => ""));


    $sql = "SELECT * FROM ".$smtable."_servers ORDER BY id ASC";
    $result = mysql_query($sql) OR die(mysql_error());

    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){

        $sql2 = "SELECT * FROM ".$table."_server WHERE Ip = '".$row['ip'].':'.$row['port']."'";
        $result2 = mysql_query($sql2) OR die(mysql_error());
        if(mysql_num_rows($result2)){
          while($row2 = mysql_fetch_assoc($result2)){
            $tpl->set_var(array("scrollservergroups" => $row2['Name_Short']));
            $scrollservername = $row2['Name_Short'];
          }
        }else{
          $tpl->set_var(array("scrollservergroups" => $row['ip'].':'.$row['port']));
          $scrollservername = $row['ip'].':'.$row['port'];
        }

         if ($row['id'] == $sid){
            $tpl->set_var(array("scrollservergroupsselected" => "SELECTED"));
            $servername = $scrollservername;
         }else{
           $tpl->set_var(array("scrollservergroupsselected" => ""));
         }
        $tpl->set_var(array("scrollservergroupsvalue" => $row['id']));
        $tpl->parse("servergroupsblock_handle", "servergroupsblock", true);
      }
    }

  }else{ // -+-+-



    $sql = "SELECT * FROM ".$table."_server";
    $result = mysql_query($sql) OR die(mysql_error());
      if(mysql_num_rows($result)){
        while($row = mysql_fetch_assoc($result)){
          $tpl->set_var(array(
          "scrollservergroups" => $row['Name_Short'],
          "scrollservergroupsvalue" => $row['ID']
          ));
          $scrollservername = $row['Name_Short'];

          if($row['ID'] == $sid){
            $tpl->set_var(array("scrollservergroupsselected" => "SELECTED"));
          }else{
            $tpl->set_var(array("scrollservergroupsselected" => ""));
          }
            $tpl->parse("servergroupsblock_handle", "servergroupsblock", true);
        }
      }

 /* $tpl->set_var(array(
   "scrollservergroups" => $viewtext['Servergroups'][0],
   "scrollservergroupsvalue" => "0",
   "scrollservergroupsselected" => "SELECTED",
   "scrollservergroupsdisabled" => "disabled"
   ));
   $tpl->parse("servergroupsblock_handle", "servergroupsblock", true);  */


  } // -+-+-
 

          $tpl->set_block("inhalt", "button", "button_handle");
          $tpl->set_block("inhalt", "nobutton", "nobutton_handle");





    if ($exportart == "0"){
      $tpl->set_var(array(
          "checked_save"        => "checked",
          "checked_upload"      => "",
          "export_button_text"  => $viewtext['System'][18],
          "export_exec"         => "output.php?file=".$outputfile_select."&exarea=sqladmins"
      ));
          $tpl->parse("button_handle", "button", true);
    }else{
    


       $ftpcheck = 0;


	   
	   
       if ($settings['servergroups_enabled'] == 1){

	   
	   

         $sql = "SELECT * FROM ".$smtable."_servers WHERE id='".$sid."'";
         $result = mysql_query($sql) OR die(mysql_error());
         if(mysql_num_rows($result)){
           while($row = mysql_fetch_assoc($result)){

             $sql2 = "SELECT * FROM ".$table."_server WHERE Ip = '".$row['ip'].':'.$row['port']."'";
             $result2 = mysql_query($sql2) OR die(mysql_error());
             if(mysql_num_rows($result2)){
               while($row2 = mysql_fetch_assoc($result2)){
			     $ftpsid = $row2['ID'];
                 if ($row2['ftp_ip'] <> "") $ftpcheck = 1;
               }
             }
           }
         }

       }else{
       
		 $ftpsid = $sid;
         $sql = "SELECT * FROM ".$table."_server WHERE ID = '".$sid."'";
         $result = mysql_query($sql) OR die(mysql_error());
         if(mysql_num_rows($result)){
           while($row = mysql_fetch_assoc($result)){
             if ($row['ftp_ip'] <> "") $ftpcheck = 1;
           }
         }


       }
	   
	          $tpl->set_var(array(
          "checked_save"        => "",
          "checked_upload"      => "checked",
          "export_button_text"  => $viewtext['System'][19],
          "export_ftp_error"    => $viewtext['Export'][4],
          "export_exec"         => "index.php?section=".$section."&action=upload&ftpsid=".$ftpsid."&fileid=".$outputfile_select_value."",
       ));
	   
       
       if ($ftpcheck == 1){
         $tpl->parse("button_handle", "button", true);
       }else{
         $tpl->parse("nobutton_handle", "nobutton", true);
       }
       

       
       

    }





$outputfile_info = '';

  $tpl->set_var(array(
     "menue_export"                  => $viewtext['System'][119],
     "menue_export_outputfile"       => $viewtext['Export'][1],
     "menue_export_filecode"         => $viewtext['System'][120],
     "menue_export_outputfile_name"  => $viewtext['System'][117],
     "export_create_button_text"     => $viewtext['System'][17],
     "option_export_save"            => $viewtext['Export'][0],
     "option_export_upload"          => $viewtext['Export'][2],
     "export_create"                 => "index.php?section=".$section."&action=execexportcreate"
  ));
  
  $tpl->set_block("inhalt", "outputfileselectblock", "outputfileselectblock_handle");
  
  for ($x = 0; $x <= count($outputfile) - 1; $x++){
    $tpl->set_var(array(
       "outputfilevalue"               => $outputfile_value[$x],
       "export_outputfile_select_text" => $outputfile[$x]
     ));

     if ($outputfile_select_value == $x){
       $tpl->set_var(array("outputfileselect"         => "SELECTED"));
     }else{
       $tpl->set_var(array("outputfileselect"         => ""));
     }
     $tpl->parse("outputfileselectblock_handle", "outputfileselectblock", true);
  }

} //--

if ($action == ""){ //--
$tpl->set_var(array("export_outputfile_name" => $viewtext['Export'][3]));
}

//--

if ($action == "execexportcreate"){ //-----

$anzeige = '';
$br = "
";

 $x = 0;

$output_client = array();

 $sql = "SELECT * FROM ".$smtable."_admins ORDER BY id ASC";

   $result = mysql_query($sql) OR die(mysql_error());
   if(mysql_num_rows($result)){
     while($row = mysql_fetch_assoc($result)){
      $x++;

      $output_client['authtype'][$x] = $row['authtype'];
      $output_client['identity'][$x] = $row['identity'];
      $output_client['password'][$x] = $row['password'];
      $output_client['flags'][$x] = $row['flags'];
      $output_client['name'][$x] = $row['name'];
      $output_client['immunity'][$x] = $row['immunity'];

      if ($row['immunity'] != 0 ){
      $output_client['adminsimple_im_fl'][$x] = $row['immunity'].':'.$row['flags'];
      }else{
      $output_client['adminsimple_im_fl'][$x] = $row['flags'];
      }

      $y = 0;
      
      $sql2 = "SELECT * FROM ".$smtable."_admins_groups WHERE admin_id = ".$row['id']." ORDER BY inherit_order ASC";

      $result2 = mysql_query($sql2) OR die(mysql_error());
      if(mysql_num_rows($result2)){
        while($row2 = mysql_fetch_assoc($result2)){
          $y++;

          $groupid = $row2['group_id'];

          $sql3 = "SELECT * FROM ".$smtable."_groups WHERE id = ".$groupid." ORDER BY immunity_level ASC";

          $result3 = mysql_query($sql3) OR die(mysql_error());
          if(mysql_num_rows($result3)){
            while($row3 = mysql_fetch_assoc($result3)){

            $output_client['group'][$x]['name'][$y] = $row3['name'];
            $output_client['group'][$x]['id'][$y] = $row3['id'];
            
            }
          }
        }
      }
    }
  }

if (isSet($output_client['identity'])){ //-#





//echo "<pre>";
//print_r($output_client);
//echo "</pre>";





  if ($outputfile_select_value == "0"){ //---
  
  
  
    $outputfile_info = $viewtext['Export'][5];

  $maxlengt_identity = maxlength ($output_client['identity']);
  $maxlengt_adminsimple_im_fl = maxlength ($output_client['adminsimple_im_fl']);

  //for ($x = 1; $x <= count($output_client['identity']); $x++){
  foreach($output_client['identity'] as $x => $value) {
  $existgroupinservergroups = 0;
  
  
     if (isSet($output_client['group'][$x])){


      for ($y = 1; $y <= count($output_client['group'][$x]['id']); $y++){
     $sql3 = "SELECT * FROM ".$smtable."_servers_groups WHERE group_id = '".$output_client['group'][$x]['id'][$y]."' AND server_id = '".$sid."'";

         $result3 = mysql_query($sql3) OR die(mysql_error());
         if((mysql_num_rows($result3)) or ($settings['servergroups_enabled'] == 0)){

          $existgroupinservergroups = 1;
     }


     }

         }

  
  
  
   if (($existgroupinservergroups == 1) or ($settings['servergroups_enabled'] == 0)){
  
  
  

    $space_identity = Getspace($output_client['identity'][$x] ,$maxlengt_identity);
    $space_adminsimple_im_fl = Getspace($output_client['adminsimple_im_fl'][$x] ,$maxlengt_adminsimple_im_fl);

    $anzeige = $anzeige.'"'.$output_client['identity'][$x].'"'.$space_identity.'"'.$output_client['adminsimple_im_fl'][$x].'"'.$space_adminsimple_im_fl.'// '.$output_client['name'][$x].$br;

  }

  }

}//---

if ($outputfile_select_value == "1"){ //---




  $anzeige = $anzeige.'Admins'.$br.'['.$br;

  //for ($x = 1; $x <= count($output_client['identity']); $x++){
    foreach($output_client['identity'] as $x => $value) {
    $existgroupinservergroups = 0;
    $anzeigegroups = '';

     if (isSet($output_client['group'][$x])){


      for ($y = 1; $y <= count($output_client['group'][$x]['id']); $y++){
     $sql3 = "SELECT * FROM ".$smtable."_servers_groups WHERE group_id = '".$output_client['group'][$x]['id'][$y]."' AND server_id = '".$sid."'";

         $result3 = mysql_query($sql3) OR die(mysql_error());
         if((mysql_num_rows($result3)) or ($settings['servergroups_enabled'] == 0)){

          $anzeigegroups = $anzeigegroups.'    "group"    "'.$output_client['group'][$x]['name'][$y].'"'.$br;
          $existgroupinservergroups = 1;
     }


     }

         }

     if (($existgroupinservergroups == 1) or ($settings['servergroups_enabled'] == 0)){


     $anzeige = $anzeige.'  "'.$output_client['name'][$x].'"'.$br.'  ['.$br;
     $anzeige = $anzeige.'    "auth"     "'.$output_client['authtype'][$x].'"'.$br;
     $anzeige = $anzeige.'    "identity" "'.$output_client['identity'][$x].'"'.$br;
     if ($output_client['password'][$x] != "") $anzeige = $anzeige.'    "password" "'.$output_client['password'][$x].'"'.$br;
     if ($output_client['flags'][$x] != "") $anzeige = $anzeige.'    "flags"    "'.$output_client['flags'][$x].'"'.$br;
     if ($output_client['immunity'][$x] != "0") $anzeige = $anzeige.'    "immunity" "'.$output_client['immunity'][$x].'"'.$br;


//     if (isSet($output_client['group'][$x])){
//            for ($y = 1; $y <= count($output_client['group'][$x]['name']); $y++){
//         $anzeige = $anzeige.'    "group"    "'.$output_client['group'][$x]['name'][$y].'"'.$br;
//       }
//     }
     $anzeige = $anzeige.$anzeigegroups.'  ]'.$br.$br;


    }



  }
  $anzeige = $anzeige.']';


}//---
}//-#

if ($outputfile_select_value == "2"){ //---



  $outputfile_info = $viewtext['Export'][6];

  $anzeige = $anzeige.'Groups'.$br.'['.$br;

  $sql = "SELECT * FROM ".$smtable."_groups ORDER BY immunity_level DESC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
    




      $sql3 = "SELECT * FROM ".$smtable."_servers_groups WHERE group_id = '".$row['id']."' AND server_id = '".$sid."'";

         $result3 = mysql_query($sql3) OR die(mysql_error());
         if((mysql_num_rows($result3)) or ($settings['servergroups_enabled'] == 0)){


       
      $anzeige = $anzeige.'  "'.$row['name'].'"'.$br.'  ['.$br;
      if ($row['flags'] != "") $anzeige = $anzeige.'    "flags"    "'.$row['flags'].'"'.$br;
      $anzeige = $anzeige.'    "immunity" "'.$row['immunity_level'].'"'.$br;

      $x = 0;
      $output_group_overide = array();

      $sql2 = "SELECT * FROM ".$smtable."_group_overrides WHERE group_id = ".$row['id']." ORDER BY type AND name ASC";

      $result2 = mysql_query($sql2) OR die(mysql_error());
      if(mysql_num_rows($result2)){
        while($row2 = mysql_fetch_assoc($result2)){
        $x++;
        if ($row2['type'] == 'group')$output_group_overide['name'][$x] = ':'.$row2['name'];
        if ($row2['type'] == 'command')$output_group_overide['name'][$x] = $row2['name'];
        $output_group_overide['access'][$x] = $row2['access'];
        }
        $anzeige = $anzeige.$br.'    Overrides'.$br.'    ['.$br;
        $maxlengt_group_overide = maxlength ($output_group_overide['name']);
        for ($x = 1; $x <= count($output_group_overide['name']); $x++){
          $space_identity = Getspace($output_group_overide['name'][$x] ,$maxlengt_group_overide);
          $anzeige = $anzeige.'      "'.$output_group_overide['name'][$x].'"'.$space_identity.'"'.$output_group_overide['access'][$x].'"'.$br;
        }
        $anzeige = $anzeige.'    ]'.$br;
      }
      $anzeige = $anzeige.'  ]'.$br.$br;
      }
    }
  }
  $anzeige = $anzeige.']';
 } //---
 
 
 if ($outputfile_select_value == "3"){ //---

  $anzeige = $anzeige.'Overrides'.$br.'['.$br;
  $x = 0;
  $output_overide = array();
  $sql = "SELECT * FROM ".$smtable."_overrides ORDER BY type ASC";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
      $x++;
      if ($row['type'] == 'group'){
        if ($settings['servergroups_enabled'] == 0){
          $output_overide['name'][$x] = '@'.$row['name'];
          $output_overide['flags'][$x] = $row['flags'];
        }else{
          $sql2 = "SELECT * FROM ".$smtable."_groups WHERE name = '".$row['name']."'";
          $result2 = mysql_query($sql2) OR die(mysql_error());
          if(mysql_num_rows($result2)){
            while($row2 = mysql_fetch_assoc($result2)){

              $sql3 = "SELECT * FROM ".$smtable."_servers_groups WHERE group_id = '".$row2['id']."' AND server_id = '".$sid."'";
              $result3 = mysql_query($sql3) OR die(mysql_error());
                if(mysql_num_rows($result3)){
                  $output_overide['name'][$x] = '@'.$row['name'];
                  $output_overide['flags'][$x] = $row['flags'];
                }
              }
            }
          }
        }
        if ($row['type'] == 'command'){
          $output_overide['name'][$x] = $row['name'];
          $output_overide['flags'][$x] = $row['flags'];
        }
      }
    }
    if (isSet($output_overide['name'])){ //-*
      $maxlengt_overide = maxlength ($output_overide['name']);
      for ($x = 1; $x <= count($output_overide['name']); $x++){
        $space_overide = Getspace($output_overide['name'][$x] ,$maxlengt_overide);
        $anzeige = $anzeige.'  "'.$output_overide['name'][$x].'"'.$space_overide.'"'.$output_overide['flags'][$x].'"'.$br;
      }
      $anzeige = $anzeige.']';
    }else{
  $anzeige = '';
  } //-*
} //---
 



    $anzeige = str_replace('[', '&#123;', $anzeige);
    $anzeige = str_replace(']', '&#125;', $anzeige);




    $tpl->set_var(array(
        "export_filecode"               => $anzeige,
        "export_outputfile_name"        => $outputfile_select,
        "export_outputfile_info"        => $outputfile_info
     ));

} //-----





} // -- -- --



}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-


?>
