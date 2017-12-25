<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* serverconfigs.php                               *
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

/*
================================================================================
--------------------------=[Section Plugnin Management]=------------------------
================================================================================

### Most funtions can be found in ../inc/funktion.php
### General Webinterface Variables some can used here:

$usercheck['name'] = (User login name)

### General Plugin Variables some can used here:

$plugin['ID'] = (ID)
$plugin['Name'] = (Name)
$plugin['Systemname'] = (Systemname to select a plugin in the interface)
$plugin['MMS'] = (Multiserver Support Cvar)
$plugin['Version'] = (Version)
$plugin['Author'] = (Author)
$plugin['Authorlink'] = (Link to Author Profile)
$plugin['Tablename'] = (Plugin MySQL Tablname)
$plugin['MMSColum'] = (Server ID columname from Plugintable)
$plugin['Support'] = (Link to Support)
$plugin['Showplug'] = (show plugin in navi. 0 = Hide / 1 = Show)

*/

if ($area == ''){ // Begin if Area = ''

 $sid = (isset($_GET['sid'])) ? $_GET['sid']:'';
 $scfgfilename = (isset($_GET['file'])) ? $_GET['file']:'';


 sqlexec("UPDATE ".$plugin['Tablename']." SET Filename ='newfile.cfg' WHERE Filename = ''");


if ($sid <> ""){ //####################

  // Define stationery text and set templatevars.
  $tpl->set_var(array(
    "serverconfigs_pluginname"                  => $plugin['Name'],
    "section"                                   => $section,
    "plugin"                                    => $plugin['Systemname'],
    "sid"                                       => $sid,
    "file"                                      => $scfgfilename,
    "serverconfigs_serverid_cvar"               => $plugin['MMS'],
    "back_button_text"                          => $viewtext['System'][4],
    "servermms_applybutton_button_text"         => $viewtext['System'][7],
    "serverconfigs_files_discription"           => $viewtext['Serverconfigs'][0],
    "serverconfigs_info_general"                => $viewtext['System'][71],
    "serverconfigs_filename_discription"        => $viewtext['System'][117],
    ));





if (($action == "") OR (($action == "usecommand") AND (isset($_POST['backbutton'])))){ // +-+-

if (($scfgfilename == "") OR (($action == "usecommand") AND (isset($_POST['backbutton'])))){ // +-+-+-+-+-


  // Define templatefile
  $tpl->set_file("inhalt", "templates/plugins/serverconfigs_files.htm");

  // Define stationery text and set templatevars.
  $tpl->set_var(array(
    "serverconfigs_server_overview_discription" => $viewtext['System'][89],
    "serverconfigs_new_file_button_text"           => $viewtext['Serverconfigs'][3],
    "serverconfigs_files_overview_discription"  => $viewtext['Serverconfigs'][0],
    "serverconfigs_file_delete_pic_infotext"  => $viewtext['System'][12],
    "serverconfigs_file_edit_pic_infotext"    => $viewtext['Serverconfigs'][5],
	"serverconfigs_file_copy_pic_infotext"    => $viewtext['Serverconfigs'][31],
	"serverconfigs_file_export_pic_infotext"    => $viewtext['System'][119],
	"serverconfigs_file_import_pic_infotext"    => $viewtext['System'][116],
    "serverconfigs_file_del_button_text"      => $viewtext['System'][12],
    "serverconfigs_file_edit_button_text"     => $viewtext['Serverconfigs'][5],
	"serverconfigs_file_copy_button_text"     => $viewtext['Serverconfigs'][31],
	"serverconfigs_file_export_button_text"     => $viewtext['System'][119],
	"serverconfigs_file_import_button_text"     => $viewtext['System'][116]


    ));

     $tpl->set_block("inhalt", "filesanzeigescrollbox", "filesanzeigescrollbox_handle");
     $tpl->set_block("inhalt", "filesanzeige", "filesanzeige_handle");
     $tpl->set_block("inhalt", "serveranzeige", "serveranzeige_handle");

    $server = get_servernames_from_pluginsid($table, $plugin['Systemname'], $sid);

    
     foreach($server as $key => $value) {
     $tpl->set_var(array("serverconfigs_servername" => $value));
     $tpl->parse("serveranzeige_handle", "serveranzeige", true);
     
     }

    $filename_array = array();

    $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE Server_ID = '".$sid."' ORDER BY Filename";
    $result = mysql_query($sql) OR die(mysql_error());
       if(mysql_num_rows($result)){
         while($row = mysql_fetch_assoc($result)){
         	 if (!isSet($filename_array[$row['Filename']])) $filename_array[$row['Filename']] = '0';
            if ($row['Command_Name'] == "") $filename_array[$row['Filename']] = $filename_array[$row['Filename']] - 1;
           $filename_array[$row['Filename']] = $filename_array[$row['Filename']] + 1;
	       }
       }else{
       $tpl->set_var(array(
          "serverconfigs_file" => "--&nbsp;".$viewtext['Serverconfigs'][2]."&nbsp;--",
          "serverconfigs_file_value" => ""
          ));
       $tpl->parse("filesanzeigescrollbox_handle", "filesanzeigescrollbox", true);
       }

       
       $filecount = count($filename_array);
       
       $tpl->set_var(array("serverconfigs_file_count" => $filecount));
       
       
          $i = 0;
       foreach($filename_array as $key => $value) {
          $i++;
         if ($i == $filecount){
           $gitter = 'gitter.gif';
         }else{
           $gitter = 'gitter2.gif';
         }
         $tpl->set_var(array(
           "serverconfigs_file" => $key,
           "serverconfigs_file_value" => $key,
           "serverconfigs_commands" => $value,
           "serverconfigs_gitter" => $gitter
         ));

         $tpl->parse("filesanzeige_handle", "filesanzeige", true);
         $tpl->parse("filesanzeigescrollbox_handle", "filesanzeigescrollbox", true);
       }

}else{


  // Define templatefile
              $tpl->set_file("inhalt", "templates/plugins/serverconfigs_commands.htm");

  // Define stationery text and set templatevars.
  $tpl->set_var(array(
    "serverconfigs_filename" => $scfgfilename,
    "serverconfigs_command_name_discription"  => $viewtext['System'][70],
    "serverconfigs_command_value_discription" => $viewtext['Serverconfigs'][10],
    "serverconfigs_options_discription"       => $viewtext['System'][65],
    "serverconfigs_command_activ_discription" => $viewtext['Serverconfigs'][17],
    "serverconfigs_add_command_button_text"   => $viewtext['Serverconfigs'][18],
    "serverconfigs_save_command_button_text"  => $viewtext['System'][18]
    ));

    $tpl->set_block("inhalt", "commandsanzeige", "commandsanzeige_handle");
    $tpl->set_block("inhalt", "commandsanzeigeempty", "commandsanzeigeempty_handle");
    $tpl->set_block("inhalt", "extrabuttonsyes", "extrabuttonsyes_handle");

    $i = 0;

    $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."') AND (Command_Name <> '') ORDER BY ID";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){
        $i++;
		
    // $tpl->parse("inputscrollbox_handle", "inputscrollbox", true);

        $tpl->set_var(array(
          "id" => $row['ID'],
          "serverconfigs_command_name" => $row['Command_Name']
        ));
        
          $tpl->set_file("inhalt3".$i."","templates/plugins/serverconfigs_commands_input.htm");
          $tpl->set_block("inhalt3".$i."", "inputtext", "inputtext_handle".$i."");
          $tpl->set_block("inhalt3".$i."", "inputscrollbox", "inputscrollbox_handle".$i."");


          if ($row['Type'] == NULL){
           $tpl->set_var(array(
             "serverconfigs_command_value" => $row['Command_Value']
            ));
           $tpl->parse("inputtext_handle".$i."", "inputtext",true);
          }else{

            $tpl->set_file("inhalt2".$i."","templates/plugins/serverconfigs_commands_scrollbox.htm");
            $tpl->set_block("inhalt2".$i."", "inputscrollboxloop", "inputscrollboxloop_handle".$i."");
            $array_type = explode(":", $row['Type']);
            if (isSet($array_type[0])) $array_type[0] = explode(",", $array_type[0]);
            if ((isSet($array_type[1])) AND (isSet($array_type[1][0]))) $array_type[1] = explode(",", $array_type[1]);
          
            foreach($array_type[0] as $key => $value) { //-+-
              if (isSet($array_type[1][$key])){
                $tpl->set_var(array("serverconfigs_command_value_show" => $array_type[1][$key]));
              }else{
                $tpl->set_var(array("serverconfigs_command_value_show" => "&nbsp;".$value."&nbsp;"));
              }
              if ($row['Command_Value'] == $value){
                $tpl->set_var(array("scrollselected" => "SELECTED"));
              }else{
                $tpl->set_var(array("scrollselected" => ""));
              }


              $tpl->set_var(array("serverconfigs_command_value" => $value));
              $tpl->parse("inputscrollboxloop_handle".$i."", "inputscrollboxloop",true);
            }
          
            $tpl->set_var(array("scrollbox" => $tpl->parse("out", "inhalt2".$i."")));
            $tpl->parse("inputscrollbox_handle".$i."", "inputscrollbox",true);
          }
          $tpl->set_var(array("input" => $tpl->parse("out", "inhalt3".$i."")));

        if ($row['Activ'] == '1'){
          $tpl->set_var(array("activchecked" => "checked"));
        }else{
          $tpl->set_var(array("activchecked" => ""));
        }
        $tpl->parse("commandsanzeige_handle", "commandsanzeige", true);
      }

      if ($i >= 13){
        $tpl->parse("extrabuttonsyes_handle", "extrabuttonsyes", true);
      }
      

    }else{
      $tpl->set_var(array("serverconfigs_commands_empty" => $viewtext['Serverconfigs'][12]));
      $tpl->parse("commandsanzeigeempty_handle", "commandsanzeigeempty", true);
    }
       
 
} // +-+-+-+-+++
} // +-+-

if ($action == "usecommand"){ //++--
  if(isset($_POST['addbutton'])){
    $tpl->set_file("inhalt", "templates/plugins/serverconfigs_command_settings.htm");
    $tpl->set_var(array(
         "serverconfigs_command_discription"       	       => $viewtext['Serverconfigs'][18],
         "serverconfigs_commandname_discription"   	       => $viewtext['System'][70],
         "serverconfigs_commandvalue_discription"   	     => $viewtext['Serverconfigs'][10],
         "serverconfigs_commandtype_discription"	         => $viewtext['System'][66],
         "serverconfigs_commandtype_text_discription"      => $viewtext['System'][118],
         "serverconfigs_commandtype_scrollbox_discription" => $viewtext['Serverconfigs'][28],
         "help"                                            => $viewtext['Serverconfigs'][30],
         "action"                                 	       => "execaddcommand",
         "checkedtext"                                     => 'checked',
         "checkedscrollbox"                                => '',
         "serverconfigs_commandtypescrollboxparm"          => '',
         "serverconfigs_commandname"               	       => "",
	     "serverconfigs_commandvalue"		                   => "",
         "id"                                              => ""
    ));
  }
  if(isset($_POST['savebutton'])){
  
    $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."') AND (Command_Name <> '') ORDER BY ID";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){
        
	  $commandvalue = (isset($_POST[$row['Command_Name'].'_command'])) ? $_POST[$row['Command_Name'].'_command']:'';
        if(isset($_POST[$row['Command_Name'].'_activ'])){
          $commandactiv = 1;
  	  }else{
          $commandactiv = 0;
        }
		echo "a";
        sqlexec("UPDATE ".$plugin['Tablename']." SET Command_Value ='".$commandvalue."', Activ ='".$commandactiv."' WHERE ID = '".$row['ID']."'", mysql_real_escape_string($commandvalue));
        $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][26], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'&file='.$scfgfilename.'');

      }
      }else{
        $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][12], $viewtext['System'][4], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'&file='.$scfgfilename.'');
      }
   }
}

if (($action == "delcommand") OR ($action == "execdelcommand") OR ($action == "editcommand")){ //++--

if ($id <> ""){

  $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE ID = '".$id."'";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
    $command = $row['Command_Name'];
    $value = $row['Command_Value'];
    $type= $row['Type'];
  }


    
  if ($action == "delcommand"){
  $viewtext['Serverconfigs'][24] = str_replace('%command%', $command, $viewtext['Serverconfigs'][24]);
  $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Serverconfigs'][24], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'&file='.$scfgfilename.'', 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&action=execdelcommand&sid='.$sid.'&file='.$scfgfilename.'&id='.$id.'');
}

if ($action == "execdelcommand"){


$sql_hits = "select count(*) as hits from ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."')";
$count = mysql_query($sql_hits) OR die("Query: <pre>".$sql_hits."</pre>\n"."Antwort: ".mysql_error());
$count = mysql_fetch_row($count);


if ($count[0] == 1){
   sqlexec("UPDATE ".$plugin['Tablename']." SET Command_Name ='' WHERE ID = '".$id."'");
}else{
   sqlexec("DELETE FROM ".$plugin['Tablename']." WHERE ID = '".$id."'");
}

    $viewtext['Serverconfigs'][25] = str_replace('%command%', $command, $viewtext['Serverconfigs'][25]);
    $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][25], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'&file='.$scfgfilename.'');
}

if ($action == "editcommand"){ //++--
  $tpl->set_file("inhalt", "templates/plugins/serverconfigs_command_settings.htm");
  $tpl->set_var(array(
    "serverconfigs_command_discription"               => $viewtext['Serverconfigs'][19],
    "serverconfigs_commandname_discription"           => $viewtext['System'][70],
    "serverconfigs_commandvalue_discription"          => $viewtext['Serverconfigs'][10],
    "serverconfigs_commandtype_discription"	          => $viewtext['System'][66],
    "serverconfigs_commandtype_text_discription"      => $viewtext['System'][118],
    "serverconfigs_commandtype_scrollbox_discription" => $viewtext['Serverconfigs'][28],
    "help"                                            => $viewtext['Serverconfigs'][30],
    "action"                                          => "execeditcommand",
    "serverconfigs_commandname"                       => $command,
    "serverconfigs_commandvalue"		                  => $value,
    "id"                                              => "&id=".$id.""
  ));
  
      if ($type == NULL){
      $tpl->set_var(array(
        "checkedtext"                            => 'checked',
        "checkedscrollbox"                       => '',
        "serverconfigs_commandtypescrollboxparm" => ''
        ));
    }else{
      $tpl->set_var(array(
        "checkedtext"                            => '',
        "checkedscrollbox"                       => 'checked',
        "serverconfigs_commandtypescrollboxparm" => $type
        ));
    }
  
  
}
    
   }else{
    $tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][42], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'&file='.$scfgfilename.'');
  }

}else{

$tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][43], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'&file='.$scfgfilename.'');

}


}

if ($action == "execeditcommand"){ //++--

  $commandname = (isset($_POST['commandname'])) ? $_POST['commandname']:'';
  $commandvalue = (isset($_POST['commandvalue'])) ? $_POST['commandvalue']:'';
  $commandtypescrollboxparm = (isset($_POST['commandtypescrollboxparm'])) ? $_POST['commandtypescrollboxparm']:'';
  $commandtype = (isset($_POST['commandtype'])) ? $_POST['commandtype']:'0';
  if (($commandname == "") or ($commandvalue == "") or (($commandtype == '1') AND ($commandtypescrollboxparm == ""))){
    $tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][25], $viewtext['System'][3], 'javascript:history.back()');
  }else{
    $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Command_Name = '".$commandname."') AND (ID <> '".$id."')";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      $viewtext['Serverconfigs'][21] = str_replace('%command%', $commandname, $viewtext['Serverconfigs'][21]);
      $viewtext['Serverconfigs'][21] = str_replace('%pluginserveridname%', $plugin['MMS'], $viewtext['Serverconfigs'][21]);
      $viewtext['Serverconfigs'][21] = str_replace('%id%', $sid, $viewtext['Serverconfigs'][21]);
      $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][21], $viewtext['System'][3], 'javascript:history.back()');
    }else{
      if ($commandtype == '1'){
        sqlexec("UPDATE ".$plugin['Tablename']." SET Command_Name ='".$commandname."', Command_Value ='".$commandvalue."', Type ='".$commandtypescrollboxparm."' WHERE ID = '".$id."'", 
		mysql_real_escape_string($commandname), mysql_real_escape_string($commandvalue), mysql_real_escape_string($commandtypescrollboxparm));
      }else{
        sqlexec("UPDATE ".$plugin['Tablename']." SET Command_Name ='".$commandname."', Command_Value ='".$commandvalue."', Type =NULL WHERE ID = '".$id."'",
		mysql_real_escape_string($commandname), mysql_real_escape_string($commandvalue));
      }
      $viewtext['Serverconfigs'][23] = str_replace('%command%', $commandname, $viewtext['Serverconfigs'][23]);
	  $viewtext['Serverconfigs'][23] = str_replace("\'", "'", $viewtext['Serverconfigs'][23]);
      $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][23], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'&file='.$scfgfilename.'');
    }
  }
}

if ($action == "execaddcommand"){ //++--
  $commandname = (isset($_POST['commandname'])) ? $_POST['commandname']:'';
  $commandvalue = (isset($_POST['commandvalue'])) ? $_POST['commandvalue']:'';
  $commandtypescrollboxparm = (isset($_POST['commandtypescrollboxparm'])) ? $_POST['commandtypescrollboxparm']:'';
  $commandtype = (isset($_POST['commandtype'])) ? $_POST['commandtype']:'0';
  if (($commandname == "") or ($commandvalue == "") or (($commandtype == '1') AND ($commandtypescrollboxparm == ""))){
    $tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][25], $viewtext['System'][4], 'javascript:history.back()');
  }else{
    $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Command_Name = '".$commandname."')";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      $viewtext['Serverconfigs'][21] = str_replace('%command%', $commandname, $viewtext['Serverconfigs'][21]);
      $viewtext['Serverconfigs'][21] = str_replace('%pluginserveridname%', $plugin['MMS'], $viewtext['Serverconfigs'][21]);
      $viewtext['Serverconfigs'][21] = str_replace('%id%', $sid, $viewtext['Serverconfigs'][21]);
      $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][21], $viewtext['System'][3], 'javascript:history.back()');
    }else{
    $sql2 = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."') AND (Command_Name = '')";
    $result2 = mysql_query($sql2) OR die(mysql_error());
    if(mysql_num_rows($result2)){
      if ($commandtype == '1'){
        sqlexec("UPDATE ".$plugin['Tablename']." SET Command_Name ='".$commandname."', Command_Value ='".$commandvalue."', Type ='".$commandtypescrollboxparm."' WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."') AND (Command_Name = '')",
		mysql_real_escape_string($commandname), mysql_real_escape_string($commandvalue), mysql_real_escape_string($commandtypescrollboxparm));
      }else{
        sqlexec("UPDATE ".$plugin['Tablename']." SET Command_Name ='".$commandname."', Command_Value ='".$commandvalue."', Type =NULL WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."') AND (Command_Name = '')",
		mysql_real_escape_string($commandname), mysql_real_escape_string($commandvalue));
      }
    }else{
      if ($commandtype == '1'){
        sqlexec("INSERT INTO ".$plugin['Tablename']." SET Filename ='".$scfgfilename."', Command_Value ='".$commandvalue."', Type ='".$commandtypescrollboxparm."', Server_ID ='".$sid."', Command_Name ='".$commandname."'",
		mysql_real_escape_string($commandname), mysql_real_escape_string($commandvalue), mysql_real_escape_string($commandtypescrollboxparm));
      }else{
        sqlexec("INSERT INTO ".$plugin['Tablename']." SET Filename ='".$scfgfilename."', Command_Value ='".$commandvalue."', Type =NULL, Server_ID ='".$sid."', Command_Name ='".$commandname."'",
		mysql_real_escape_string($commandname), mysql_real_escape_string($commandvalue));
      }
    }
    $viewtext['Serverconfigs'][22] = str_replace('%command%', $commandname, $viewtext['Serverconfigs'][22]);
    $viewtext['Serverconfigs'][22] = str_replace("\'", "'", $viewtext['Serverconfigs'][22]);
	$tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][22], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'&file='.$scfgfilename.'');
    }
  }
}



//*************
if ($action == "copyeditdelexportfile"){ //++--
  $filescrollbox = (isset($_POST['filescrollbox'])) ? $_POST['filescrollbox']:'';
  if ($filescrollbox == ""){
    $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][14], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'');
  }else{

  if(isset($_POST['export'])){
  $tpl->set_file("inhalt", "templates/plugins/serverconfigs_export.tpl.htm");
 
  $tpl->set_var(array(
         "serverconfigs_export_discription"          => $viewtext['System'][119],
         "serverconfigs_export_file_discription"     => $viewtext['System'][117],
		 "serverconfigs_export_filecode_discription" => $viewtext['System'][120], 		 
		 "serverconfigs_export_file" 				 => $filescrollbox,
		 "serverconfigs_export_button_text" 		 => $viewtext['System'][18],
		 "serverconfigs_back_button_text"			=> $viewtext['System'][4]
      ));
  $exportfile = ReadFile2Array($plugin, $sid, $filescrollbox);

  $code = '';
  $linebreak ='
';

  $count = count($exportfile);
  foreach($exportfile as $key => $value) {
	if ($count == $key) $linebreak ='';
	
    if ( is_numeric( $exportfile[$key]['Command_Value'] ) ) {
       $code = $code.$exportfile[$key]['Command_Name'].' '.$exportfile[$key]['Command_Value'].$linebreak;
         }else{
       $code = $code.$exportfile[$key]['Command_Name'].' "'.$exportfile[$key]['Command_Value'].'"'.$linebreak;
}
	}
  $tpl->set_var(array("serverconfigs_export_filecode" => $code));
  }
  
     if(isset($_POST['copy'])){
      $tpl->set_file("inhalt", "templates/plugins/serverconfigs_files_copy.htm");
      $tpl->set_block("inhalt", "serveridscrollboxanzeige", "serveridscrollboxanzeige_handle");

      $viewtext['Serverconfigs'][32] = str_replace('%serverid%', $plugin['MMS'], $viewtext['Serverconfigs'][32]);

      $serveridsready = Servermmslist($table, $plugin['MMSColum'], $plugin['Tablename'], $plugin['Systemname']);
      foreach($serveridsready as $key => $value) {
         $tpl->set_var(array(
            "serverconfigs_serverid_value" => $key,
            "serverconfigs_serverid" => $key
         ));
          $tpl->parse("serveridscrollboxanzeige_handle", "serveridscrollboxanzeige", true);
      }
	     $tpl->set_var(array(
         "serverconfigs_file_copy_discription" => $filescrollbox,
         "serverconfigs_filename"              => $filescrollbox,
         "serverconfigs_serverid_discription"  => $viewtext['Serverconfigs'][32],
         "serverconfigs_saveas_text"           => $viewtext['Serverconfigs'][29],
         "action"                              => 'execcopyfile&file='.$filescrollbox.''

      ));
       }
     if(isset($_POST['del'])){
         $viewtext['Serverconfigs'][15] = str_replace('%file%', $filescrollbox, $viewtext['Serverconfigs'][15]);
         $tpl = Infoboxselect($tpl, $plugin['Name'], $viewtext['Serverconfigs'][15], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'', 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&action=execdelfile&file='.$filescrollbox.'&sid='.$sid.'');
     }
     if(isset($_POST['edit'])){
         $tpl->set_file("inhalt", "templates/plugins/serverconfigs_files_add_edit.htm");
         $tpl->set_var(array(
         "serverconfigs_file_edit_add_discription" => $viewtext['Serverconfigs'][5],
         "serverconfigs_filename"                  => $filescrollbox,
         "action"                                  => 'execeditfile&file='.$filescrollbox.''
      ));
    }
  }
} //++--

if ($action == "addfile"){ //++--
  $tpl->set_file("inhalt", "templates/plugins/serverconfigs_files_add_edit.htm");
  $tpl->set_var(array(
           "serverconfigs_file_edit_add_discription" => $viewtext['Serverconfigs'][3],
           "serverconfigs_filename"                  => "",
           "action"                                  => 'execaddfile'
         ));
} //++--


if ($action == "execdelfile"){ //++--
  sqlexec("DELETE FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."')");
  $viewtext['Serverconfigs'][16] = str_replace('%file%', $scfgfilename, $viewtext['Serverconfigs'][16]);
  $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][16], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'');
}

if ($action == "execeditfile"){ //++--
  $editscfgfilename = (isset($_POST['filename'])) ? $_POST['filename']:'';
    if ($editscfgfilename == ""){
    $tpl = Infobox($tpl, $plugin['Name'].' '.$viewtext['System'][9], $viewtext['System'][25], $viewtext['System'][4], 'javascript:history.back()');
  }else{
    $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."')";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      sqlexec("UPDATE ".$plugin['Tablename']." SET Filename ='".$editscfgfilename."' WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."')");
      $viewtext['Serverconfigs'][13] = str_replace('%sourcefile%', $scfgfilename, $viewtext['Serverconfigs'][13]);
      $viewtext['Serverconfigs'][13] = str_replace('%targetfile%', $editscfgfilename, $viewtext['Serverconfigs'][13]);
      $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][13], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'');
    }else{
      $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][14], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'');
    }
  }
}

if ($action == "execaddfile"){ //++--
  $scfgfilename = (isset($_POST['filename'])) ? $_POST['filename']:'';
    if ($scfgfilename == ""){
    $tpl = Infobox($tpl, $plugin['Name'], $viewtext['System'][25], $viewtext['System'][4], 'javascript:history.back()');
  }else{
    $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Filename = '".$scfgfilename."') ORDER BY Filename";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      $viewtext['Serverconfigs'][4] = str_replace('%file%', $scfgfilename, $viewtext['Serverconfigs'][4]);
      $viewtext['Serverconfigs'][4] = str_replace('%pluginserveridname%', $plugin['MMS'], $viewtext['Serverconfigs'][4]);
      $viewtext['Serverconfigs'][4] = str_replace('%id%', $sid, $viewtext['Serverconfigs'][4]);
      $tpl = Infobox($tpl, $plugin['Name'].' '.$viewtext['System'][9], $viewtext['Serverconfigs'][4], $viewtext['System'][3], 'javascript:history.back()');
    }else{
      sqlexec("INSERT INTO ".$plugin['Tablename']." SET Filename ='".$scfgfilename."', Server_ID ='".$sid."'");
      $viewtext['Serverconfigs'][8] = str_replace('%file%', $scfgfilename, $viewtext['Serverconfigs'][8]);
      $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][8], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'');
    }
  }
}


if ($action == "execcopyfile"){ //++--
  $scfgtargetfilename = (isset($_POST['filename'])) ? $_POST['filename']:'';
  $scfgtargetsid = (isset($_POST['serveridscrollbox'])) ? $_POST['serveridscrollbox']:'';
  
  if (($scfgtargetfilename == "") or ($scfgtargetsid == "")){
    $tpl = Infobox($tpl, $plugin['Name'].' '.$viewtext['System'][9], $viewtext['System'][25], $viewtext['System'][4], 'javascript:history.back()');
  }else{
    
	//echo $sid.'<br>';
	
	$sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$scfgtargetsid."') AND (Filename = '".$scfgtargetfilename."') ORDER BY Filename";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      $viewtext['Serverconfigs'][4] = str_replace('%file%', $scfgtargetfilename, $viewtext['Serverconfigs'][4]);
      $viewtext['Serverconfigs'][4] = str_replace('%pluginserveridname%', $plugin['MMS'], $viewtext['Serverconfigs'][4]);
      $viewtext['Serverconfigs'][4] = str_replace('%id%', $scfgtargetsid, $viewtext['Serverconfigs'][4]);
      $tpl = Infobox($tpl, $plugin['Name'].' '.$viewtext['System'][9], $viewtext['Serverconfigs'][4], $viewtext['System'][3], 'javascript:history.back()');
	 
    }else{
     
	 $newfile = ReadFile2Array($plugin, $sid, $scfgfilename);



	 
  	  foreach($newfile as $key => $value) {

	  $newfile[$key]['Command_Value'] = mysql_real_escape_string($newfile[$key]['Command_Value']); 
	  $newfile[$key]['Command_Name'] = mysql_real_escape_string($newfile[$key]['Command_Name']);
	  
	 sqlexec("INSERT INTO ".$plugin['Tablename']." SET Filename ='".$scfgtargetfilename."', Server_ID ='".$scfgtargetsid."', Command_Name ='".$newfile[$key]['Command_Value']."', Command_Value ='".$newfile[$key]['Command_Name']."', Type ='".$newfile[$key]['Type']."', Activ ='".$newfile[$key]['Activ']."'");
					
	  }
	  
	 $tpl = Infobox($tpl, $plugin['Name'], $viewtext['Serverconfigs'][27], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'');
					
				
    }	
  }
}

if ($action == "importfile"){ //++--

  $tpl->set_file("inhalt", "templates/plugins/serverconfigs_import.tpl.htm");
  $tpl->set_var(array(
           "serverconfigs_import_discription" 		  => $viewtext['System'][116],
           "serverconfigs_import_infotext"    		  => $viewtext['Serverconfigs'][1],
		   "serverconfigs_import_file_discription"    => $viewtext['System'][121],
		   "serverconfigs_import_button_text"         => $viewtext['System'][116],
		   "serverconfigs_import_ifexist_discription" => $viewtext['Serverconfigs'][6],
		   "serverconfigs_import_create_discription"  => $viewtext['Serverconfigs'][7],
		   "serverconfigs_import_replace_discription" => $viewtext['Serverconfigs'][9],
		   "serverconfigs_import_skip_discription"    => $viewtext['Serverconfigs'][11],
		   "serverconfigs_back_button_text"			  => $viewtext['System'][4],
           "action"                                   => 'execaddfile'
         ));  
}

if ($action == "execimportfile"){ //++--

$importart = (isset($_POST['importart'])) ? $_POST['importart']:'create';

$data = uploadfile('datei', $viewtext);

$data['file_content'] = Strip_Comments($data['file_content']);

preg_match_all("/^([^\/\s]+)\s+(?:\"(.*)\")?(\d+(?:\.\d+)?)?/m", $data['file_content'], $matches, PREG_SET_ORDER);

$found=0;
foreach ($matches as $match){
  if (((isset($match[2])) or (isset($match[3])))  AND (strtolower($match[1]) <> "echo")){
  $found++;
    $data['commands']['command'][$found] = $match[1];
    if (isset($match[2])) $data['commands']['value'][$found] = $match[2];
    if (isset($match[3])) $data['commands']['value'][$found] = $match[3];
  }
}
//-----------
$files = array();	

$sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') ORDER BY Filename";
$result = mysql_query($sql) OR die(mysql_error());
if(mysql_num_rows($result)){ //---+++---
    while($row = mysql_fetch_assoc($result)){
      if(!in_array($row['Filename'], $files )) $files[] = $row['Filename'];
	}
}
//-----------
$comandsindb = array();

$x = 0;
$sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Filename = '".$data['filename']."') ORDER BY Command_Name";
$result = mysql_query($sql) OR die(mysql_error());
if(mysql_num_rows($result)){ //---+++---  
  if ($importart == 'create'){    
  $viewtext['Serverconfigs'][34] = str_replace('%oldfilename%', $data['filename'], $viewtext['Serverconfigs'][34]);		
  for($a=1; $a < 1000000; $a++){
	  if(!in_array('copy_'.$a.'_of_'.$data['filename'], $files)){		    
		$data['filename'] = 'copy_'.$a.'_of_'.$data['filename'];
		break;
	  }
	}
    $viewtext['Serverconfigs'][34] = str_replace('%newfilename%', $data['filename'], $viewtext['Serverconfigs'][34]);		   
    $data['msg'] = $data['msg'].'<br>'.$viewtext['Serverconfigs'][34].'<br>---<br>';
  }else{
  	$data['msg'] = $data['msg'].'<br>';
    while($row = mysql_fetch_assoc($result)){
	  $x++;
	  $comandsindb[$x] = $row['Command_Name'];
	} 
  }
}  
//-----------
$skip = 0;
$replease = 0;
$new = 0; 

foreach($data['commands']['command'] as $key => $value) {
  if(in_array($value, $comandsindb)){
	//echo 'old ['.$data['commands']['command'][$key].'] <br>';
    if ($importart == 'replace'){
      $replease++;  

  	  $data['commands']['value'][$key] = mysql_real_escape_string($data['commands']['value'][$key]); 
	  $data['commands']['command'][$key] = mysql_real_escape_string($data['commands']['command'][$key]);
  
      sqlexec("UPDATE ".$plugin['Tablename']." 
	  SET Command_Value ='".$data['commands']['value'][$key]."' 
	  WHERE (Server_ID = '".$sid."') AND (Filename = '".$data['filename']."') AND (Command_Name = '".$data['commands']['command'][$key]."')");
    }
    if ($importart == 'skip') $skip++;
  }else{
  //echo 'new ['.$data['commands']['command'][$key].'] <br>';
  $new++; 
  
  $data['commands']['command'][$key] = mysql_real_escape_string($data['commands']['command'][$key]); 
  $data['commands']['value'][$key] = mysql_real_escape_string($data['commands']['value'][$key]);  
  
  sqlexec("INSERT INTO ".$plugin['Tablename']." SET 
	             Filename ='".$data['filename']."', 
		         Server_ID ='".$sid."',
				 Command_Name ='".$data['commands']['command'][$key]."',
				 Command_Value ='".$data['commands']['value'][$key]."',					
				 Activ ='1'
  		  ");
  }
}

$viewtext['Serverconfigs'][33] = str_replace('%num%', $found, $viewtext['Serverconfigs'][33]);		   
$data['msg'] = $data['msg'].$viewtext['Serverconfigs'][33].'<br>---<br>';
$viewtext['Serverconfigs'][35] = str_replace('%num%', $new, $viewtext['Serverconfigs'][35]);		   
$data['msg'] = $data['msg'].$viewtext['Serverconfigs'][35].'<br>';
$viewtext['Serverconfigs'][36] = str_replace('%num%', $skip, $viewtext['Serverconfigs'][36]);		   
$data['msg'] = $data['msg'].$viewtext['Serverconfigs'][36].'<br>';
$viewtext['Serverconfigs'][37] = str_replace('%num%', $replease, $viewtext['Serverconfigs'][37]);		   
$data['msg'] = $data['msg'].$viewtext['Serverconfigs'][37].'<br>---<br>';
$data['msg'] = $data['msg'].$viewtext['System'][33];
 //  echo'<pre>';
    //print_r($files);
  //echo'</pre>'; 

$tpl = Infobox($tpl, $plugin['Name'], $data['msg'], $viewtext['System'][3], 'index.php?section='.$section.'&plugin='.$plugin['Systemname'].'&sid='.$sid.'');
}

}else{ //####################

$viewtext['Server'][25] = str_replace('%pluginserveridname%', $plugin['MMS'], $viewtext['Server'][25]);
$tpl = Infobox($tpl, $plugin['Name'], $viewtext['Server'][25], $viewtext['System'][4], 'javascript:history.back()');

} //####################

} // End if Area = ''

//==============================================================================
//------------------------------=[Private Functions]=---------------------------
//==============================================================================

function ReadFile2Array($plugin ,$sid, $filename){
     $x = 0;
	 $filecontend = array();

	 $sql = "SELECT * FROM ".$plugin['Tablename']." WHERE (Server_ID = '".$sid."') AND (Filename = '".$filename."') ORDER BY ID";
     $result = mysql_query($sql) OR die(mysql_error());
     if(mysql_num_rows($result)){
	     while($row = mysql_fetch_assoc($result)){
			$x++;
     		$filecontend[$x]['Command_Name'] = $row['Command_Name'];
			$filecontend[$x]['Command_Value'] = $row['Command_Value'];
			$filecontend[$x]['Type'] = $row['Type'];
			$filecontend[$x]['Activ'] = $row['Activ'];
	     }
     }
	return $filecontend;
}

//==============================================================================
//--------------------------=[Section Plugnin Settings]=------------------------
//==============================================================================


// ---> BEGIN Create Table for MySQL Banning Plugin
if ($area == 'createtable'){

sqlexec("CREATE TABLE IF NOT EXISTS ".$plugintable."(
ID              int(11) PRIMARY KEY auto_increment,
Server_ID       int(11),
Command_Name    varchar(255) NOT NULL default '',
Command_Value   varchar(255) NOT NULL default '',
Filename        varchar(255) NOT NULL default '',
Type            varchar(255) NULL,
Activ           int(1),
time_modified   timestamp(14) NOT NULL default
CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
)TYPE=MyISAM;");

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
$tpl->set_file("maprate_specific_settings", "templates/plugins/serverconfigs_specific_settings.tpl.htm");

// Parse serverconfig_specific_settings.tpl.htm to plugins_settings.tpl.htm
$tpl->set_var(array("specific_settings" => $tpl->parse("out", "maprate_specific_settings"),));

}

//------------------------------------------------------------------------------

// ---> BEGIN to save the Specific Settings in the Settings Table.

if ($area == 'savepecificsettings'){


}


//==============================================================================
//==============================================================================
//==============================================================================


?>
