<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* clients.php                                     *
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

$tpl->set_var(array(
"menue_client_authtype"     => $viewtext['Clients'][4]
));

$client_authtype          = array();
$client_authtype_value    = array();
$client_authtype[0]       = $viewtext['System'][88];
$client_authtype_value[0] = 'steam';
$client_authtype[1]       = $viewtext['System'][87];
$client_authtype_value[1] = 'ip';
$client_authtype[2]       = $viewtext['System'][70];
$client_authtype_value[2] = 'name';

if ($action == ""){ //--

$tpl->set_file("inhalt", "templates/clients.tpl.htm");


  $tpl->set_var(array(
     "menue_client_id"            => $viewtext['System'][68],
     "menue_client_nick"          => $viewtext['System'][84],
     "client_edit_pic_infotext"   => $viewtext['Clients'][2],
     "menue_client_identity"      => $viewtext['Clients'][3],
     "menue_client_immunity"      => $viewtext['System'][97],
     "menue_client_options"       => $viewtext['System'][65],
     "menue_clients"              => $viewtext['Clients'][0],
     "menue_cleint_groups"        => $viewtext['System'][98],
     "menue_cleint_edit"          => $viewtext['System'][11],
     "add_client_button_text"     => $viewtext['System'][99],
     "client_delete_pic_infotext" => $viewtext['Clients'][1]
     ));




  $tpl->set_block("inhalt", "clientsanzeige", "clients_handle");
  $tpl->set_block("inhalt", "clientsanzeigeempty", "clientsempty_handle");

  // Search Funktion
  $allsearchcategories = array();
  $allsearchcategories['value'] = array('id', 'client', 'identity');
  $allsearchcategories['view'] = array($viewtext['System'][68], $viewtext['System'][82], $viewtext['Clients'][3]);
  $allsearchcategories['table'] = array('id', 'name', 'identity');
  $tpl = show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, '', '');
  $searcquery = search_sql_injection_filter ($allsearchcategories, $searchcat, $searchstring, '');

  // Next Page Funktion
  $all_entrys = mysql_num_rows(mysql_query("SELECT * FROM ".$smtable."_admins ".$searcquery));
  if ($current_site > ceil($all_entrys / $settings['show_clients'])) $current_site = 1;
  $start_entry = $current_site * $settings['show_clients'] - $settings['show_clients'];
  $tpl = showentrys($tpl, $viewtext['System'][21] ,$viewtext['System'][82], $start_entry, $all_entrys, $settings['show_clients']);
  $tpl = site_links($tpl, $all_entrys, $settings['show_clients'], $current_site, $section, '', '', $searchcat, $searchstring, '', $viewtext);
  
  // ----

  $sql = "SELECT * FROM ".$smtable."_admins ".$searcquery." ORDER BY id ASC LIMIT ".$start_entry.", ".$settings['show_clients']."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

$x = 0;
for (;;){
  if ($client_authtype_value[$x]==$row['authtype']) break;
  $x++;
}
$member_anzahl_groups = get_hits_management($smtable."_admins_groups", "admin_id", $row['id']);

  
  $row['name'] = BadStringFilterShow($row['name']);

  $tpl->set_var(array(
            "client_id"          => $row['id'],
            "client_nick"        => $row['name'],
            "client_identity"    => $row['identity'],
            "client_authtype"    => $client_authtype[$x],
            "client_immunity"    => $row['immunity'],
            "client_groups"      => $member_anzahl_groups
    ));
     $tpl->parse("clients_handle", "clientsanzeige", true);

  }
  }else{
    $tpl->set_var(array( "clients_empty" => $viewtext['Clients'][7]));
    $tpl->parse("clientsempty_handle", "clientsanzeigeempty", true);
  }
}//--

if (($action == "addclient") or (($action == "editclient") and ($id != ""))){ //--
$tpl->set_file("inhalt", "templates/client_add_edit.tpl.htm");

$tpl->set_block("inhalt", "passsetblock", "passset_handle");


  $tpl->set_var(array(

     "menue_client_nick"         => $viewtext['System'][84],
     "menue_client_identity"     => $viewtext['Clients'][3],
     "menue_client_immunity"     => $viewtext['System'][97],
     "menue_client_info_general" => $viewtext['System'][71],
     "menue_client_group"        => $viewtext['Clients'][1],
     "menue_client_pass"         => $viewtext['System'][61],
     "menue_client_pass_retry"   => $viewtext['System'][100],
     "password_change_text"      => $viewtext['Clients'][10],
     "back_client_button_text"   => $viewtext['System'][4],
     "add_edit_client_button_text"  => $viewtext['System'][7],
     
  ));

}//--

if ($action == "addclient"){ //--

 $tpl->set_var(array(
     "menue_clients"                => $viewtext['System'][99],
     "execaddeditclient"            => "index.php?section=clients&action=execaddclient",
     "client_nick"                  => "",
     "client_immunity"              => "0",
     "client_passset"               => $viewtext['System'][2]
  ));

  $tpl->set_block("inhalt", "scrollauthtypeblock", "authtype_handle");

  for ($x = 0; $x <= 2; $x++){

    $tpl->set_var(array(
       "scrollauthtype"         => $client_authtype[$x],
       "scrollauthtypevalue"    => $client_authtype_value[$x],
       "scrollauthtypeselected" => ""
    ));
    $tpl->parse("authtype_handle", "scrollauthtypeblock", true);
  }

} //--


if ($action == "editclient"){ //--
if ($id != ""){

  $tpl->set_var(array(
     "menue_clients"               => $viewtext['Clients'][2],
     "execaddeditclient"           => "index.php?section=clients&action=execeditclient&id=".$id."",
     "password_change_text"        => $viewtext['System'][104]
  ));

  $sql = "SELECT * FROM ".$smtable."_admins WHERE id = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){
  
  $tpl->set_block("inhalt", "scrollauthtypeblock", "authtype_handle");

  for ($x = 0; $x <= 2; $x++){

    $tpl->set_var(array(
       "scrollauthtype"         => $client_authtype[$x],
       "scrollauthtypevalue"    => $client_authtype_value[$x],
    ));

    if ($client_authtype_value[$x]==$row['authtype']){
      $tpl->set_var(array("scrollauthtypeselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("scrollauthtypeselected" => ""));
    }

    $tpl->parse("authtype_handle", "scrollauthtypeblock", true);
  }

if ($row['password'] != ""){

    $tpl->set_var(array(
     "menue_client_passset" => $viewtext['Clients'][5],
     "client_passset"       => $viewtext['System'][1],
     "client_id"            => $row['id'],
     "menue_pw_del"         => $viewtext['Clients'][6]
     ));

  $tpl->parse("passset_handle", "passsetblock", true);

    }

   $row['name'] = BadStringFilterShow($row['name']);

  $tpl->set_var(array(
     "client_nick"        => $row['name'],
     "client_identity"    => $row['identity'],
     "client_immunity"    => $row['immunity']
  ));
  }
  }

}
}//--

if (($action == "execaddclient") or ($action == "execeditclient")){ //--

$client = array();

$client['nick']            = (isset($_POST['clientnick'])) ? $_POST['clientnick']:'';
$client['authtype']        = (isset($_POST['clientauthtype'])) ? $_POST['clientauthtype']:'';
$client['identity']        = (isset($_POST['clientidentity'])) ? $_POST['clientidentity']:'';
$client['immunity']        = (isset($_POST['clientimmunity'])) ? $_POST['clientimmunity']:'';
$client['pass']            = (isset($_POST['clientpass'])) ? $_POST['clientpass']:'';
$client['passretry']       = (isset($_POST['clientpassretry'])) ? $_POST['clientpassretry']:'';

if ($client['authtype'] <> "name") $client['identity'] = str_replace(' ','',$client['identity']);

}

if ($action == "execaddclient"){ //--

  //$checkresult = Checkclientadd($table, $client['nick'], $client['authtype'], $client['identity'], $client['immunity'], $client['pass'], $client['passretry']);
  
  if (($client['nick'] == "") or ($client['authtype'] == "") or ($client['identity'] == "") or ($client['immunity'] == "")){
    $tpl = Infobox($tpl, $viewtext['System'][99], $viewtext['System'][25], $viewtext['System'][3], 'index.php?section=clients');
  }else{
    if ($client['pass'] != $client['passretry']){
      $tpl = Infobox($tpl, $viewtext['System'][99], $viewtext['System'][101] , $viewtext['System'][3], 'index.php?section=clients');
    }else{
      if ($client['pass'] == ""){
        sqlexec("INSERT INTO ".$smtable."_admins SET
                name='".$client['nick']."',
                authtype='".$client['authtype']."',
                identity='".$client['identity']."',
                immunity='".$client['immunity']."',
                flags=''
              ");
      }else{
        sqlexec("INSERT INTO ".$smtable."_admins SET
                name='".$client['nick']."',
                authtype='".$client['authtype']."',
                identity='".$client['identity']."',
                immunity='".$client['immunity']."',
                password='".$client['pass']."',
                flags=''
              ");
      }
    $viewtext['Clients'][11] = str_replace('%client%', $client['nick'], $viewtext['Clients'][11]);
    $tpl = Infobox($tpl, $viewtext['System'][99], $viewtext['Clients'][11], $viewtext['System'][3], 'index.php?section=clients');
    }
  }
}//--

if ($id != ""){//-----

if ($action == "execeditclient"){ //--

//$checkresult = Checkclientedit($table, $client['nick'], $client['authtype'], $client['identity'], $client['immunity'], $client['pass'], $client['passretry']);
  if (($client['nick'] == "") or ($client['authtype'] == "") or ($client['identity'] == "") or ($client['immunity'] == "")){
    $tpl = Infobox($tpl, $viewtext['Clients'][2], $viewtext['System'][25], $viewtext['System'][4], 'index.php?section=clients&action=editclient&id='.$id.'');
  }else{
    if ($client['pass'] != $client['passretry']){
      $tpl = Infobox($tpl, $viewtext['System'][99], $viewtext['System'][101] , $viewtext['System'][4], 'index.php?section=clients&action=editclient&id='.$id.'');
    }else{
	  if ($client['pass'] == ""){

        sqlexec("UPDATE
                  ".$smtable."_admins
               SET
                  name='".$client['nick']."',
                  authtype='".$client['authtype']."',
                  identity='".$client['identity']."',
                  immunity='".$client['immunity']."'
               WHERE
                  id = ".$id.""
              );
      }else{
        sqlexec("UPDATE
                  ".$smtable."_admins
               SET
                  name='".$client['nick']."',
                  authtype='".$client['authtype']."',
                  identity='".$client['identity']."',
                  immunity='".$client['immunity']."',
                  password='".$client['pass']."'
               WHERE
                  id = ".$id.""
              );
      }
      $viewtext['Clients'][12] = str_replace('%client%', $client['nick'], $viewtext['Clients'][12]);
	  $tpl = Infobox($tpl, $viewtext['Clients'][2], $viewtext['Clients'][12], $viewtext['System'][3], 'index.php?section=clients');
    }
  }
}//--

if (($action == "delclient") or ($action == "execdelclient") or ($action == "delpw")){ //--

  $sql = "SELECT * FROM ".$smtable."_admins WHERE id = ".$id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

   $viewtext['Clients'][13] = str_replace('%client%', $row['name'], $viewtext['Clients'][13]);
   $viewtext['Clients'][14] = str_replace('%client%', $row['name'], $viewtext['Clients'][14]);
   $viewtext['Clients'][15] = str_replace('%client%', $row['name'], $viewtext['Clients'][15]);
   }
   }
}

if ($action == "delclient"){ //--
  $tpl = Infoboxselect($tpl, $viewtext['Clients'][1], $viewtext['Clients'][13], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section=clients', 'index.php?section=clients&action=execdelclient&id='.$id.'' );
}//--

if ($action == "execdelclient"){ //--
  sqlexec("DELETE FROM ".$smtable."_admins WHERE id = ".$id."");
  sqlexec("DELETE FROM ".$smtable."_admins_groups WHERE admin_id = ".$id."");
  $tpl = Infobox($tpl, $viewtext['Clients'][1], $viewtext['Clients'][14], $viewtext['System'][3], 'index.php?section=clients');
}//--

if ($action == "delpw"){ //--

 sqlexec("UPDATE ".$smtable."_admins SET password = NULL WHERE id = ".$id."");
 $tpl = Infobox($tpl, $viewtext['Clients'][6], $viewtext['Clients'][15], $viewtext['System'][3], 'index.php?section=clients&action=editclient&id='.$id);

}

}//-----

}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-

?>
