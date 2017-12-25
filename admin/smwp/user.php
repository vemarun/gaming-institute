<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* user.php                                        *
*                                                 *
* Copyleft (L) 2008-20XX By Andreas Dahl          *
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

$access = 1;

if (($usercheck['id'] == $id) or ($usercheck['edit'] == "1")){ //-----

$tpl->set_var(array(
     "menue_user_name"                 => $viewtext['System'][60],
     "menue_user_mail"                 => $viewtext['User'][2],
     "menue_user_editusers"            => $viewtext['User'][3],
     "menue_user_permissions"          => $viewtext['User'][6],
     "menue_user_editpluginsettings"   => $viewtext['User'][7],
     "menue_user_editsqladmins"        => $viewtext['User'][1],
     "menue_user_interfacesettings"    => $viewtext['User'][11],
     "menue_user_permissionssettings"  => $viewtext['User'][12],
     "menue_user_editserversettings"   => $viewtext['User'][14],
     "menue_user_editmods"             => $viewtext['User'][15],
     



  ));


if (($action == "adduser") or ($action == "edituser")){ //--
$tpl->set_file("inhalt", "templates/user_add_edit.tpl.htm");
$tpl->set_block("inhalt", "admineditusersanzeige", "admineditusersanzeige_handle");
$tpl->set_block("inhalt", "pluginaccessanzeige", "pluginaccessanzeige_handle");

if ($usercheck['editpermissions'] == "1") $tpl->parse("admineditusersanzeige_handle", "admineditusersanzeige", true);

//$tpl->parse("pluginaccessanzeige_handle", "pluginaccessanzeige", true);

  $tpl->set_var(array(

     "menue_user_pass"           => $viewtext['System'][61],
     "menue_user_pass_retry"     => $viewtext['System'][100],
     "menue_user_info_general"   => $viewtext['System'][71],
     "menue_user_checkyes"       => $viewtext['System'][1],
     "menue_user_checkyno"       => $viewtext['System'][2],
     "user_language_discription" => $viewtext['System'][105],
     "back_user_button_text"     => $viewtext['System'][4],
     "add_edit_user_button_text" => $viewtext['System'][7]
  ));

}//--


if ($action == "edituser"){ //--

  $sql = "SELECT * FROM ".$table."_users WHERE UserID = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){


  $row['UserName'] = BadStringFilterShow($row['UserName']);
  
  
       $tpl->set_var(array(
       "user_name"                 => $row['UserName'],
       "user_mail"                 => $row['UserMail'],
       "menue_user"                => $viewtext['User'][9],
       "execaddedituser"           => "index.php?section=user&action=execedituser&id=".$id."",
       "password_change_text"      => $viewtext['System'][104]
    ));


$tpl = showuserlanguagescrollbox($tpl, $viewtext, $row['UserLanguage'],$languagearray);

if ($row['UserOwner'] == "1"){
 $tpl->set_var(array("radiodisabled"       => "disabled",
			   "tdcolor"		 => "tableinhalt3",
			   "userextrastatustext" => $viewtext['User'][13]
                                          ));
}else{
 $tpl->set_var(array("radiodisabled" => "",
                     "tdcolor"		 =>"tableinhalt1",
                     "userextrastatustext" => ""
));
}



if ($row['UserEditPermissions'] == "1"){

      $tpl->set_var(array(
              "editpermissionssettingscheckedyes"        => "checked",
              "editpermissionssettingscheckedno"         => ""
      ));
    }else{
      $tpl->set_var(array(
              "editpermissionssettingscheckedyes"        => "",
              "editpermissionssettingscheckedno"         => "checked"
      ));
    }

    if ($row['UserEditUsers'] == "1"){
      $tpl->set_var(array(
              "editusercheckedyes"        => "checked",
              "editusercheckedno"         => ""
      ));
    }else{
      $tpl->set_var(array(
              "editusercheckedyes"        => "",
              "editusercheckedno"         => "checked"
      ));
    }
    
        if ($row['UserEditMods'] == "1"){
      $tpl->set_var(array(
              "editmodscheckedyes"     => "checked",
              "editmodscheckedno"      => ""
      ));
    }else{
      $tpl->set_var(array(
              "editmodscheckedyes"     => "",
              "editmodscheckedno"      => "checked"
      ));
    }
    
    
    if ($row['UserServersettings'] == "1"){
      $tpl->set_var(array(
              "editserversettingscheckedyes"     => "checked",
              "editserversettingscheckedno"      => ""
      ));
    }else{
      $tpl->set_var(array(
              "editserversettingscheckedyes"     => "",
              "editserversettingscheckedno"      => "checked"
      ));
    }
    if ($row['UserSQLAdmins'] == "1"){
      $tpl->set_var(array(
              "editsqladminscheckedyes"        => "checked",
              "editsqladminscheckedno"         => ""
      ));
    }else{
      $tpl->set_var(array(
              "editsqladminscheckedyes"        => "",
              "editsqladminscheckedno"         => "checked"
      ));
    }
    if ($row['UserEditPluginsettings'] == "1"){
      $tpl->set_var(array(
              "editpluginsettingscheckedyes"        => "checked",
              "editpluginsettingscheckedno"         => ""
      ));
    }else{
      $tpl->set_var(array(
              "editpluginsettingscheckedyes"        => "",
              "editpluginsettingscheckedno"         => "checked"
      ));
    }

    if ($row['UserEditInterfacesettings'] == "1"){
      $tpl->set_var(array(
              "editinterfacesettingscheckedyes"     => "checked",
              "editinterfacesettingscheckedno"      => ""
      ));
    }else{
      $tpl->set_var(array(
              "editinterfacesettingscheckedyes"     => "",
              "editinterfacesettingscheckedno"      => "checked"
      ));
    }

    }
    }

}//--



if (($action == "execadduser") or ($action == "execedituser")){ //--

$user = array();
$user['name']           = (isset($_POST['username'])) ? $_POST['username']:'';
$user['pass']           = (isset($_POST['userpass'])) ? $_POST['userpass']:'';
$user['passretry']      = (isset($_POST['userpassretry'])) ? $_POST['userpassretry']:'';
$user['mail']           = (isset($_POST['usermail'])) ? $_POST['usermail']:'';
$user['userlanguage']   = (isset($_POST['userlanguage'])) ? $_POST['userlanguage']:'';

$user['name'] = BadStringFilterSave($user['name']);

if ($usercheck['editpermissions'] == "1"){

if ($action == "execedituser"){

$sql = "SELECT * FROM ".$table."_users WHERE UserID = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

   $userowner = $row['UserOwner'];

   }
   }

}else{
   $userowner = 0;
}


  $user['edit'] = (isset($_POST['editusers'])) ? $_POST['editusers']:'';
  $user['sqladmins']      = (isset($_POST['editsqladmins'])) ? $_POST['editsqladmins']:'';
  $user['pluginsettings'] = (isset($_POST['editpluginsettings'])) ? $_POST['editpluginsettings']:'';
  $user['serversettings'] = (isset($_POST['editserversettings'])) ? $_POST['editserversettings']:'';
  $user['editmods'] = (isset($_POST['editmods'])) ? $_POST['editmods']:'';
  $user['interfacesettings'] = (isset($_POST['editinterfacesettings'])) ? $_POST['editinterfacesettings']:'';
  $user['permissionssettings'] = (isset($_POST['editpermissionssettings'])) ? $_POST['editpermissionssettings']:'';


if ($userowner == "1"){
  $permissionsettings = "UserEditUsers='1',UserSQLAdmins='1',UserServersettings='1',UserEditPluginsettings='1',UserEditInterfacesettings='1',UserEditPermissions='1',";
}else{
  $permissionsettings = "UserEditUsers='".$user['edit']."',UserSQLAdmins='".$user['sqladmins']."',UserServersettings='".$user['serversettings']."',UserEditPluginsettings='".$user['pluginsettings']."',UserEditInterfacesettings='".$user['interfacesettings']."',UserEditMods='".$user['editmods']."',UserEditPermissions='".$user['permissionssettings']."',";
}

}else{




  if ($action == "execadduser"){
    $permissionsettings = "UserEditUsers='0',UserSQLAdmins='0',UserServersettings='0',UserEditPluginsettings='0',UserEditInterfacesettings='0',UserEditMods='0',UserEditPermissions='0',";
  }else{
    $permissionsettings = "";
  }

}

}

if ($action == "execedituser"){ //--





  $checkresult = Checkuseredit($table, $user['name'], $user['mail'], $user['pass'], $user['passretry'], $id, $user['userlanguage']);

  if ($checkresult === True){

    if ($user['pass'] == ""){

      sqlexec("UPDATE
                  ".$table."_users
      	       SET
                  UserName = '".$user['name']."',
                  UserMail='".$user['mail']."',
                  ".$permissionsettings."
                  UserLanguage='".$user['userlanguage']."'
               WHERE
  	              UserID = ".$id.""
             );
    }else{
      sqlexec("UPDATE
                  ".$table."_users
      	       SET
                  UserName = '".$user['name']."',
                  UserMail='".$user['mail']."',
                  ".$permissionsettings."
                  UserLanguage='".$user['userlanguage']."',
                  UserPass=MD5('".$user['pass']."')
               WHERE
  	              UserID = ".$id.""
             );
    }

        $viewtext['User'][17] = str_replace('%user%', $user['name'], $viewtext['User'][17]);
      $tpl = Infobox($tpl, $viewtext['User'][9], $viewtext['User'][17], $viewtext['System'][3], 'index.php?section=user');

  }else{
    $viewtext['System'][$checkresult] = str_replace('%user%', $user['name'], $viewtext['System'][$checkresult]);
    $tpl = Infobox($tpl, $viewtext['User'][9], $viewtext['System'][$checkresult], $viewtext['System'][4], 'index.php?section=user&action=edituser&id='.$id.'');
  }
}//--



}else{

$access = 0;

}


if (($usercheck['edit'] != "1") and (($action == "adduser") or ($action == "execadduser") or ($action == "deluser") or ($action == "execdeluser"))){

$access = 0;

}

if ($usercheck['edit'] == "1"){ //-----


if ($action == "adduser"){ //--

$tpl = showuserlanguagescrollbox($tpl, $viewtext, 'default', $languagearray);


  $tpl->set_var(array(
     "menue_user"                        => $viewtext['User'][8],
     "tdcolor"		                       => "tableinhalt1",
     "radiodisabled"                     => "",
     "editpermissionssettingscheckedyes" => "",
     "editpermissionssettingscheckedno"  => "checked",
     "editusercheckedyes"                => "",
     "editusercheckedno"                 => "checked",
     "editinterfacesettingscheckedyes"   => "",
     "editinterfacesettingscheckedno"    => "checked",
     "editserversettingscheckedyes"      => "",
     "editserversettingscheckedno"       => "checked",
     "editpluginsettingscheckedyes"      => "",
     "editpluginsettingscheckedno"       => "checked",
     "editsqladminscheckedyes"           => "",
     "editsqladminscheckedno"            => "checked",
     "user_name"                         => "",
     "user_mail"                         => "",
     "execaddedituser"                   => "index.php?section=user&action=execadduser",
     "password_change_text"              => ""
  ));

} //--



if ($action == "execadduser"){ //--

  $checkresult = Checkuseradd($table, $user['name'], $user['mail'], $user['pass'], $user['passretry'], $user['userlanguage']);
  
  if ($checkresult === True){
  

    sqlexec("INSERT INTO ".$table."_users SET
              UserName='".$user['name']."',
              UserPass=MD5('".$user['pass']."'),
              UserMail='".$user['mail']."',
              ".$permissionsettings."
              UserLanguage='".$user['userlanguage']."',
              UserOwner='0',
              UserDatum = ".$time.""
            );
    $viewtext['User'][16] = str_replace('%user%', $user['name'], $viewtext['User'][16]);  
	$tpl = Infobox($tpl, $viewtext['User'][8], $viewtext['User'][16], $viewtext['System'][3], 'index.php?section=user');
  }else{
    $viewtext['System'][$checkresult] = str_replace('%user%', $user['name'], $viewtext['System'][$checkresult]);
    $tpl = Infobox($tpl, $viewtext['User'][8], $viewtext['System'][$checkresult], $viewtext['System'][4], 'javascript:history.back()');
  }
}//--

if (($action == "deluser") or ($action == "execdeluser")){ //--

  $sql = "SELECT * FROM ".$table."_users WHERE UserID = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

   $viewtext['User'][18] = str_replace('%user%', $row['UserName'], $viewtext['User'][18]);
   $viewtext['User'][19] = str_replace('%user%', $row['UserName'], $viewtext['User'][19]);
   $viewtext['User'][20] = str_replace('%user%', $row['UserName'], $viewtext['User'][20]);

   $userowner = $row['UserOwner'];

   }
   }
}

if ($action == "deluser"){ //--

  if ($userowner == "1"){
    $tpl = Infobox($tpl, $viewtext['User'][10], $viewtext['User'][18], $viewtext['System'][4], 'index.php?section=user');
  }else{
    $tpl = Infoboxselect($tpl, $viewtext['User'][10], $viewtext['User'][20], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section=user', 'index.php?section=user&action=execdeluser&id='.$id.'' );
  }
}//--

if ($action == "execdeluser"){ //--

  if ($userowner == "1"){
    $tpl = Infobox($tpl, $viewtext['User'][8], $viewtext['User'][18], $viewtext['System'][4], 'index.php?section=user');
  }else{
    sqlexec("DELETE FROM ".$table."_users WHERE UserID = ".$id."");
    $tpl = Infobox($tpl, $viewtext['User'][8], $viewtext['User'][19], $viewtext['System'][3], 'index.php?section=user');
  }
}//--


if ($action == ""){ //--

$tpl->set_file("inhalt", "templates/user.tpl.htm");

  $tpl->set_var(array(
     "menue_user"                => $viewtext['User'][0],
     "menue_user_owner"          => $viewtext['User'][4],
     "menue_user_options"        => $viewtext['System'][65],
     "user_edit_pic_infotext"    => $viewtext['User'][9],
     "user_delete_pic_infotext"  => $viewtext['User'][10],
     "add_user_button_text"      => $viewtext['User'][8]
  ));

  $tpl->set_block("inhalt", "useranzeige", "user_handle");


  // Search Funktion
  $allsearchcategories = array();
  $allsearchcategories['value'] = array('name', 'mail', 'edituser');
  $allsearchcategories['view'] = array($viewtext['System'][60], $viewtext['User'][2], $viewtext['User'][3]);
  $allsearchcategories['table'] = array('UserName', 'UserMail', 'UserEditUsers');
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, '', '');
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, '');

  // Next Page Funktion
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$table."_users ".$searcquery));
  if ($current_site > ceil($all_entrys / $settings['show_users'])) $current_site = 1;
  $start_entry = $current_site * $settings['show_users'] - $settings['show_users'];
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['User'][0], $start_entry, $all_entrys, $settings['show_users']);
  $tpl = site_links($tpl, $all_entrys, $settings['show_users'], $current_site, $section, '', '', $searchcat, $searchstring, '', $viewtext);

  // ----

  $sql = "SELECT * FROM ".$table."_users ".$searcquery." ORDER BY UserDatum ASC LIMIT ".$start_entry.", ".$settings['show_users']."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){
  
    $row['UserName'] = BadStringFilterSave($row['UserName']);

    $tpl->set_var(array(
            "user_id"             => $row['UserID'],
            "user_name"           => $row['UserName'],
            "user_mail"           => $row['UserMail'],
            "user_editadmin"      => $row['UserEditUsers'],
            "user_owner"          => $row['UserOwner']
    ));
    $tpl->parse("user_handle", "useranzeige", true);

  }
  }
}//--

} //-----


if ($access == 0){

$tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');

}



?>

