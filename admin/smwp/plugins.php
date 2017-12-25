<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* plugins.php                                     *
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

$pluginfile = (isset($_GET['plugin'])) ? $_GET['plugin']:'';

if ($pluginfile != ''){ // -----

$sqlinclude = "SELECT * FROM ".$table."_plugins WHERE Systemname = '".$pluginfile."'";

$resultinclude = mysql_query($sqlinclude) OR die(mysql_error());
if(mysql_num_rows($resultinclude)){
  while($plugin = mysql_fetch_assoc($resultinclude)){
    $area = '';


    if  ($plugin['MMS'] <> ""){
    

    
     //----------------------- Server Infos to Array -----------  "add in the next version to plugins.php" !!!

       //Select all entry from server_mms table
       $sql = "SELECT * FROM ".$table."_server_plugins_mss WHERE Plugin_Systemname = '".$plugin['Systemname']."'";
       $result = mysql_query($sql) OR die(mysql_error());
       if(mysql_num_rows($result)){
         while($row = mysql_fetch_assoc($result)){
           // Save serverids from server_mms and add it to an array ("$array[serverid] = serveridcounts")
           if (!isSet($Serveridsmangement[$row['Plugin_Server_ID']])) $Serveridsmangement[$row['Plugin_Server_ID']] = 0;
           $Serveridsmangement[$row['Plugin_Server_ID']] = $Serveridsmangement[$row['Plugin_Server_ID']] + 1;
         }
       }

       //Select all entry from plugintable
       $sql = "SELECT * FROM ".$plugin['Tablename']."";
       $result = mysql_query($sql) OR die(mysql_error());
       if(mysql_num_rows($result)){
         while($row = mysql_fetch_assoc($result)){
         // Save srvids from chatlogtable and add it to an array  "$array[servid] = servidlogcounts"
         if (!isSet($Serverids[$row[$plugin['MMSColum']]]['entrys'])) $Serverids[$row[$plugin['MMSColum']]]['entrys'] = 0;
         $Serverids[$row[$plugin['MMSColum']]]['entrys'] = $Serverids[$row[$plugin['MMSColum']]]['entrys'] + 1;
		 }
       }else{
	     $Serverids = array();
	   }

        // Merge serverids and srvids and filter duplicate entrys
        //if ((isSet($Serverids)) AND (isSet($Serveridsmangement))){
		
		if (isSet($Serveridsmangement)){
        $Serveridsmerge = $Serverids;
        foreach($Serveridsmangement as $sidm => $sidmcount){
          $found = 0;
          foreach($Serveridsmerge as $servid => $handle) {
            if ($servid == $sidm) $found = 1;
          }
          if ($found == 0) $Serverids[$sidm]['entrys'] = 0;
        }
        }


      // if (isSet($Serverids)){
 
       // Take merget serverids and add infos to it. (servername, serverip, interfaceid e.t.c.)
      foreach($Serverids as $servid => $handle) {
         $x=0;
         $sql = "SELECT Interface_Server_ID FROM ".$table."_server_plugins_mss WHERE Plugin_Server_ID = '".$servid."' AND Plugin_Systemname = '".$plugin['Systemname']."'";
         $result = mysql_query($sql) OR die(mysql_error());
         if(mysql_num_rows($result)){
           while($row = mysql_fetch_assoc($result)){
             $x++;
            $Serverids[$servid]['servercount'] = mysql_num_rows($result);
            $sql2 = "SELECT * FROM ".$table."_server WHERE ID = ".$row['Interface_Server_ID']."";
            $result2 = mysql_query($sql2) OR die(mysql_error());
            if(mysql_num_rows($result2)){
              while($row2 = mysql_fetch_assoc($result2)){
                $Serverids[$servid]['name'][$x] = $row2['Name_Short'];
                $Serverids[$servid]['ip'][$x] = $row2['Ip'];
                $Serverids[$servid]['serverinterfaceid'][$x] = $row['Interface_Server_ID'];
              }
            }
          }
        }else{
          $Serverids[$servid]['servercount'] = 1;
          $Serverids[$servid]['name'][1] = $plugin['MMS'].' = '.$servid;
        }
      }


          //}
		  
		  }
        include ("plugins/".$pluginfile.".php");
 }
 }
}else{ // -----


if ($usercheck['editpluginsettings'] == "1"){ //-+-+- 

$plugins_shownavi[0] = $viewtext['Plugins'][11];
$plugins_shownavi[1] = $viewtext['Plugins'][10];


$tpl->set_var(array(
  "section"                     => $section,
  "menue_plugins"               => $viewtext['Plugins'][0],
  "menue_plugin_name"           => $viewtext['System'][70],
  "menue_plugin_version"        => $viewtext['Plugins'][3],
  "menue_plugin_author"         => $viewtext['Plugins'][7],
  "menue_plugin_shownavi"       => $viewtext['Plugins'][6],
  "menue_plugin_shownavi_long"  => $viewtext['Plugins'][13],
  "menue_plugin_database_table" => $viewtext['Plugins'][4],
  ));
  

if ($action == ""){ //--

$tpl->set_file("inhalt", "templates/plugins.tpl.htm");

$tpl->set_var(array(
  "menue_plugin_id"             => $viewtext['System'][68],
  "menue_plugin_options"        => $viewtext['System'][65]

));

$tpl->set_block("inhalt", "pluginsanzeige", "plugins_handle");

$sql = "SELECT * FROM ".$table."_plugins ORDER BY id ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

      if (Table_Exists($row['Tablename'])=== True){
        $show_exist_table = $viewtext['Plugins'][8];
      }else{
        $show_exist_table = $viewtext['Plugins'][9];
      }

      $tpl->set_var(array(
        "plugin_id"             => $row['ID'],
        "plugin_name"           => $row['Name'],
        "plugin_support_link"   => $row['Support'],
        "plugin_version"        => $row['Version'],
        "plugin_author"         => $row['Author'],
        "plugin_author_link"    => $row['Authorlink'],
        "plugin_database_table" => $show_exist_table,
        "plugin_shownavi"       => $plugins_shownavi[$row['Showplug']],
        "plugin_options"        => $viewtext['Menu'][15]
      ));
      $tpl->parse("plugins_handle", "pluginsanzeige", true);
    }
  }



}//--


if ($action == "settings"){ //--

  $tpl->set_file("inhalt", "templates/plugins_settings.tpl.htm");

  $tpl->set_var(array(
    "menue_plugin_settings_general"  => $viewtext['System'][71],
    "menue_plugin_settings_specific" => $viewtext['Plugins'][12],
    "menue_plugin_support_link"       => $viewtext['Plugins'][5],
    "apply_button_text"              => $viewtext['System'][7],
    "back_button_text"               => $viewtext['System'][4]

  ));

  $sql = "SELECT * FROM ".$table."_plugins WHERE id = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

      if (Table_Exists($row['Tablename'])=== True){
         $tpl->set_var(array(
         "tableaction_text"      => $viewtext['Plugins'][2],
         "tableaction"           => 'checkdeltable',
         "plugin_database_table" => $viewtext['Plugins'][8]
         ));
      }else{
      $tpl->set_var(array(
         "tableaction_text"      => $viewtext['Plugins'][1],
         "tableaction"           => 'createtable',
         "plugin_database_table" => $viewtext['Plugins'][9]
         ));
      }

  $tpl->set_var(array(
        "plugin_id"             => $row['ID'],
        "plugin_name"           => $row['Name'],
        "plugin_version"        => $row['Version'],
        "plugin_author"         => $row['Author'],
        "plugin_table"          => $row['Tablename'],
        "plugin_support_link"   => $row['Support']
        ));
        
$tpl->set_var(array("specific_settings" => 'sssss'));

  $area = 'readspecificsettings';
  include ("plugins/".$row['Systemname'].".php");
  
  $tpl->set_block("inhalt", "scrollshownaviblock", "scrollshownaviblock_handle");
  
   for ($x = 0; $x <= 1; $x++){

    $tpl->set_var(array(
       "scrollshownavi"         => $plugins_shownavi[$x],
       "scrollshownavivalue"    => $x
    ));

    if ($x == $row['Showplug']){
      $tpl->set_var(array("scrollshownaviselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("scrollshownaviselected" => ""));
    }

    $tpl->parse("scrollshownaviblock_handle", "scrollshownaviblock", true);
  }

  
  }
  }else{
  $tpl = Infobox($tpl, $viewtext['Plugins'][0], $viewtext['Plugins'][16], $viewtext['System'][3], 'index.php?section='.$section.'');
  }

} //--


if ($action == "applysettings"){ //--

$plugin = array();

$plugin['tablename']   = (isset($_POST['tablename'])) ? $_POST['tablename']:'';
$plugin['supportlink'] = (isset($_POST['supportlink'])) ? $_POST['supportlink']:'';
$plugin['shownavi']   = (isset($_POST['shownavi'])) ? $_POST['shownavi']:'';

if (($plugin['tablename'] == '') or ($plugin['supportlink'] == '') or ($plugin['shownavi'] == '')){

$tpl = Infobox($tpl, $viewtext['Plugins'][0], $viewtext['System'][25], $viewtext['System'][4], 'index.php?section='.$section.'&action=settings&id='.$id.'');

}else{

  $sql = "SELECT * FROM ".$table."_plugins WHERE id = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
      $area = 'savepecificsettings';
      include ("plugins/".$row['Systemname'].".php");
    }
  }

sqlexec("UPDATE ".$table."_plugins SET
           Tablename='".$plugin['tablename']."',
           Showplug='".$plugin['shownavi']."',
           Support='".$plugin['supportlink']."'
         WHERE
           ID = ".$id."");

$tpl = Infobox($tpl, $viewtext['Plugins'][0], $viewtext['System'][112], $viewtext['System'][3], 'index.php?section='.$section.'&action=settings&id='.$id.'');

}

}  //--

if (($action == "createtable") or ($action == "deltable") or ($action == "checkdeltable")){ //--

  $area = $action;

  $sql = "SELECT * FROM ".$table."_plugins WHERE id = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
          if ($action == "checkdeltable"){
        $tpl = Infoboxselect($tpl, $viewtext['Plugins'][0], str_replace('%table%', $row['Tablename'], $viewtext['Plugins'][17]), $viewtext['System'][2], $viewtext['System'][1], 'index.php?section='.$section.'', 'index.php?section='.$section.'&action=deltable&id='.$id.'' );
      }else{
        $plugintable = $row['Tablename'];
        include ("plugins/".$row['Systemname'].".php");
        if ($action == "createtable"){
          if (Table_Exists($row['Tablename'])=== True){
            $msg = str_replace('%table%', $row['Tablename'], $viewtext['Plugins'][14]);
          }else{
            $msg = $viewtext['System'][23];
          }
        }
        if ($action == "deltable"){
          if (Table_Exists($row['Tablename'])=== True){
            $msg = $viewtext['System'][23];

          }else{
            $msg = str_replace('%table%', $row['Tablename'], $viewtext['Plugins'][15]);
          }
        }
      $tpl = Infobox($tpl, $viewtext['Plugins'][0], $msg, $viewtext['System'][3], 'index.php?section='.$section.'&action=settings&id='.$id.'');
      }
    }
  }else{
  $tpl = Infobox($tpl, $viewtext['Plugins'][0], $viewtext['Plugins'][16], $viewtext['System'][3], 'index.php?section='.$section.'');
  }
} //--


}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-

} // -----
?>
