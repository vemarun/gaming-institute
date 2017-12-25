<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* overrides.php                                   *
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

$overridename = (isset($_GET['name'])) ? $_GET['name']:'';
$overridetype = (isset($_GET['type'])) ? $_GET['type']:'';
$overridegroupid = (isset($_GET['groupid'])) ? $_GET['groupid']:'';


$overrides_type          = array();
$overrides_type_value    = array();
$overrides_type[0]       = $viewtext['Overrides'][13];
$overrides_type_value[0] = 'command';
$overrides_type[1]       = $viewtext['System'][83];
$overrides_type_value[1] = 'group';

$overrides_access          = array();
$overrides_access_value    = array();
$overrides_access[0]       = $viewtext['Overrides'][14];
$overrides_access_value[0] = 'allow';
$overrides_access[1]       = $viewtext['Overrides'][12];
$overrides_access_value[1] = 'deny';

$overrides_type_name = $overridetype;

foreach($overrides_type_value as $key => $value) {
  if ($value==$overridetype) $overrides_type_name = $overrides_type[$key];
}
  
if ($section == "groupoverrides"){ //-//-=============================

if ($action == ""){ //--

$tpl->set_file("inhalt", "templates/group_overrides.tpl.htm");
  
  $tpl->set_var(array(
     "menue_groupoverrides"            => $viewtext['Menu'][11],
     "menue_groupoverrides_name"       => $viewtext['System'][70],
     "menue_groupoverrides_group_id"   => $viewtext['Overrides'][2],
     "menue_groupoverrides_group_name" => $viewtext['Overrides'][3],
     "menue_groupoverrides_type"       => $viewtext['System'][66],
     "menue_groupoverrides_access"     => $viewtext['Overrides'][5],
     "menue_groupoverrides_action"     => $viewtext['System'][40],
     "menue_groupoverrides_edit"       => $viewtext['System'][11],
     "menue_groupoverrides_del"        => $viewtext['System'][12],
     "add_groupoverride_button_text"   => $viewtext['Overrides'][9]

  ));

  $tpl->set_block("inhalt", "groupoverrideanzeige", "groupoverride_handle");
  $tpl->set_block("inhalt", "groupoverrideanzeigeempty", "groupoverrideempty_handle");

  $sql = "SELECT * FROM ".$smtable."_group_overrides ORDER BY name ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){
  
      $x = 0;
      for (;;){
        if ($overrides_type_value[$x]==$row['type']) break;
        $x++;
      }
    
      $y = 0;
      for (;;){
        if ($overrides_access_value[$y]==$row['access']) break;
        $y++;
      }
  
      $sql2 = "SELECT * FROM ".$smtable."_groups WHERE id = ".$row['group_id']."";

     $result2 = mysql_query($sql2) OR die(mysql_error());
      if(mysql_num_rows($result2))
      {        while($row2 = mysql_fetch_assoc($result2)){
    

	  $row['name'] = BadStringFilterShow($row['name']);
	

 $tpl->set_var(array(
              "groupoverrides_name"        => $row['name'],
              "groupoverrides_group_id"    => $row['group_id'],
              "groupoverrides_group_name"  => $row2['name'],
              "groupoverrides_type"        => $overrides_type[$x],
			  "overrides_type_value"       => $overrides_type_value[$x],
              "groupoverrides_access"      => $overrides_access[$y]

      ));
       $tpl->parse("groupoverride_handle", "groupoverrideanzeige", true);

      }
      }

    }
  }else{
  

  $tpl->set_var(array("groupoverrides_empty" => $viewtext['Overrides'][10]));
  $tpl->parse("groupoverrideempty_handle", "groupoverrideanzeigeempty", true);
  
  }
  
  
}//--

if (($action == "addoverride") or ($action == "editoverride")){ //---


$go = 1;



if ($action == "addoverride") { //--

 $tpl->set_var(array(
     "menue_groupoverrides"        => $viewtext['Overrides'][9],
     "execaddeditoverride"         => "index.php?section=groupoverrides&action=execaddoverride",
     "groupoverridesname"          => ""
  ));
  
$type = '';
$name = '';
$access = '';
$groupid = '';

  } //--

if ($action == "editoverride") { //--




$sql = "SELECT * FROM ".$smtable."_group_overrides WHERE name = '".$overridename."' AND group_id = '".$overridegroupid."' AND type = '".$overridetype."'";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){        
  
  while($row = mysql_fetch_assoc($result)){

  
    $row['name'] = BadStringFilterShow($row['name']);
  
$type = $row['type'];
$name = $row['name'];
$access = $row['access'];
$groupid = $row['group_id'];


  }
 }else{
    
	$go = 0;
  
  }

  $tpl->set_var(array(
     "menue_groupoverrides"        => $viewtext['Overrides'][7],
     "execaddeditoverride"         => "index.php?section=groupoverrides&action=execeditoverride&name=".$overridename."&groupid=".$overridegroupid."&type=".$overridetype.""
     
  ));
  

} //--

if ($go == 1){


$tpl->set_file("inhalt", "templates/group_overrides_add_edit.tpl.htm");


 $tpl->set_var(array(
     "menue_groupoverrides_name"          => $viewtext['System'][70],
     "menue_groupoverrides_info_general"  => $viewtext['System'][71],
     "menue_groupoverrides_group_name"    => $viewtext['System'][83],
     "menue_groupoverrides_type"          => $viewtext['System'][66],
     "menue_groupoverrides_access"        => $viewtext['Overrides'][5],
     "add_edit_groupoverride_button_text" => $viewtext['System'][7],
     "back_groupoverride_button_text"     => $viewtext['System'][4],
	 "groupoverridesname"          		  => $name
  ));

  $tpl->set_block("inhalt", "scrollgroupsblock", "scrollgroupsblock_handle");
  
  $sql = "SELECT * FROM ".$smtable."_groups ORDER BY id ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

    $tpl->set_var(array(
       "scrollgroup"         => $row['name'],
       "scrollgroupvalue"    => $row['id']
    ));
    

    if ($groupid == $row['id']){
      $tpl->set_var(array("scrollgroupselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("scrollgroupselected" => ""));
    }
    
    
    $tpl->parse("scrollgroupsblock_handle", "scrollgroupsblock", true);


  }
  }
  
  $tpl->set_block("inhalt", "scrolltypeblock", "scrolltypeblock_handle");

  for ($x = 0; $x <= 1; $x++){

    $tpl->set_var(array(
       "scrolltype"         => $overrides_type[$x],
       "scrolltypevalue"    => $overrides_type_value[$x]
    ));
    
    if ($type == $overrides_type_value[$x]){
      $tpl->set_var(array("scrolltypeselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("scrolltypeselected" => ""));
    }
    $tpl->parse("scrolltypeblock_handle", "scrolltypeblock", true);
  }
  
  $tpl->set_block("inhalt", "scrollaccessblock", "scrollaccessblock_handle");

  for ($x = 0; $x <= 1; $x++){

    $tpl->set_var(array(
       "scrollaccess"         => $overrides_access[$x],
       "scrollaccessvalue"    => $overrides_access_value[$x],
    ));
    
    if ($access == $overrides_access_value[$x]){
      $tpl->set_var(array("scrollaccessselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("scrollaccessselected" => ""));
    }
    $tpl->parse("scrollaccessblock_handle", "scrollaccessblock", true);
  }

}else{

   $viewtext['Overrides'][20] = str_replace('%name%', $overridename, $viewtext['Overrides'][20]);
   $viewtext['Overrides'][20] = str_replace('%type%', $overrides_type_name, $viewtext['Overrides'][20]);
   $viewtext['Overrides'][20] = str_replace('%groupid%', $overridegroupid, $viewtext['Overrides'][20]);
  
   $tpl = Infobox($tpl, $viewtext['Overrides'][7], $viewtext['Overrides'][20], $viewtext['System'][3], 'index.php?section=overrides');

}


}//---

if (($action == "execaddoverride") or ($action == "execeditoverride")){ //---

$groupoverride = array();

$groupoverride['name']            = (isset($_POST['name'])) ? $_POST['name']:'';
$groupoverride['group']           = (isset($_POST['group'])) ? $_POST['group']:'';
$groupoverride['type']            = (isset($_POST['type'])) ? $_POST['type']:'';
$groupoverride['access']            = (isset($_POST['access'])) ? $_POST['access']:'';


$groupoverride['type_name'] = $groupoverride['type'];
foreach($overrides_type_value as $key => $value) {
  if ($value==$groupoverride['type']) $groupoverride['type_name'] = $overrides_type[$key];
}

  $groupoverride['name'] = BadStringFilterSave($groupoverride['name']);
  
  
} //---

if ($action == "execaddoverride"){ //--

   //$check = Checkgroupoverride($smtable, $groupoverride['name'], $groupoverride['group'], $groupoverride['type'], $groupoverride['access']);

  if (($groupoverride['name'] == "") or ($groupoverride['group'] == "") or ($groupoverride['type'] == "") or ($groupoverride['access'] == "")){ 
    $tpl = Infobox($tpl, $viewtext['Overrides'][9], $viewtext['System'][25], $viewtext['System'][4], 'index.php?section=groupoverrides&action=addoverride');
  }else{

    $sql = "SELECT * FROM ".$smtable."_group_overrides WHERE name = '".$groupoverride['name']."' AND group_id = '".$groupoverride['group']."' AND type = '".$groupoverride['type']."'";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      
	  $viewtext['Overrides'][16] = str_replace('%name%', $groupoverride['name'], $viewtext['Overrides'][16]);
      $viewtext['Overrides'][16] = str_replace('%type%', $groupoverride['type_name'], $viewtext['Overrides'][16]);
      $viewtext['Overrides'][16] = str_replace('%groupid%', $groupoverride['group'], $viewtext['Overrides'][16]);
	  
	  $tpl = Infobox($tpl, $viewtext['Overrides'][9], $viewtext['Overrides'][16], $viewtext['System'][4], 'javascript:history.back()');
   
    }else{
	
      sqlexec("INSERT INTO ".$smtable."_group_overrides SET
                group_id ='".$groupoverride['group']."',
                type='".$groupoverride['type']."',
                name='".$groupoverride['name']."',
                access='".$groupoverride['access']."'
              ");
    
	  $viewtext['Overrides'][15] = str_replace('%name%', $groupoverride['name'], $viewtext['Overrides'][15]);
      $viewtext['Overrides'][15] = str_replace('%type%', $groupoverride['type_name'], $viewtext['Overrides'][15]);
      $viewtext['Overrides'][15] = str_replace('%groupid%', $groupoverride['group'], $viewtext['Overrides'][15]);	  


	  
      $tpl = Infobox($tpl, $viewtext['Overrides'][9], $viewtext['Overrides'][15], $viewtext['System'][3], 'index.php?section=groupoverrides');
    }
  }
} //--



if ($action == "execeditoverride"){ //--

if (($groupoverride['name'] == "") or ($groupoverride['group'] == "") or ($groupoverride['type'] == "") or ($groupoverride['access'] == "")){

    $tpl = Infobox($tpl, $viewtext['Overrides'][9], $viewtext['System'][25], $viewtext['System'][4], 'index.php?section=groupoverrides&action=editoverride&name='.$overridename.'&groupid='.$overridegroupid.'&type='.$overridetype.'');

}else{

    $sql = "SELECT * FROM ".$smtable."_group_overrides WHERE 
	(name = '".$groupoverride['name']."' AND group_id = '".$groupoverride['group']."' AND type = '".$groupoverride['type']."')
	AND (name <> '".$overridename."' OR group_id <> '".$overridegroupid."' OR type <> '".$overridetype."')";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
	
	  $viewtext['Overrides'][16] = str_replace('%name%', $groupoverride['name'], $viewtext['Overrides'][16]);
      $viewtext['Overrides'][16] = str_replace('%type%', $groupoverride['type_name'], $viewtext['Overrides'][16]);
      $viewtext['Overrides'][16] = str_replace('%groupid%', $groupoverride['group'], $viewtext['Overrides'][16]);
	  
	  $tpl = Infobox($tpl, $viewtext['Overrides'][9], $viewtext['Overrides'][16], $viewtext['System'][4], 'javascript:history.back()');
	

	}else{
	

   sqlexec("UPDATE ".$smtable."_group_overrides SET
                group_id ='".$groupoverride['group']."',
                type='".$groupoverride['type']."',
                name='".$groupoverride['name']."',
                access='".$groupoverride['access']."'
             WHERE
                name = '".$overridename."' AND group_id = '".$overridegroupid."' AND type = '".$overridetype."'"
           );

    $viewtext['Overrides'][17] = str_replace('%name%', $groupoverride['name'], $viewtext['Overrides'][17]);
	$viewtext['Overrides'][17] = str_replace('%type%', $groupoverride['type_name'], $viewtext['Overrides'][17]);
    $viewtext['Overrides'][17] = str_replace('%groupid%', $groupoverride['group'], $viewtext['Overrides'][17]);	  
	

    $tpl = Infobox($tpl, $viewtext['Overrides'][9], $viewtext['Overrides'][17], $viewtext['System'][3], 'index.php?section=groupoverrides');

	}
	
}

  } //--

if ($action == "deloverride"){ //--
  $viewtext['Overrides'][18] = str_replace('%name%', $overridename, $viewtext['Overrides'][18]);
  $viewtext['Overrides'][18] = str_replace('%type%', $overrides_type_name, $viewtext['Overrides'][18]);
  $viewtext['Overrides'][18] = str_replace('%groupid%', $overridegroupid, $viewtext['Overrides'][18]);	  
  
  $tpl = Infoboxselect($tpl, $viewtext['Overrides'][11], $viewtext['Overrides'][18], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section=groupoverrides', 'index.php?section=groupoverrides&action=execdeloverride&name='.$overridename.'&groupid='.$overridegroupid.'&type='.$overridetype.'' );
}//--

if ($action == "execdeloverride"){ //--
  sqlexec("DELETE FROM ".$smtable."_group_overrides WHERE name = '".$overridename."' AND group_id = '".$overridegroupid."' AND type = '".$overridetype."'");
  $viewtext['Overrides'][19] = str_replace('%name%', $overridename, $viewtext['Overrides'][19]);
  $viewtext['Overrides'][19] = str_replace('%type%', $overrides_type_name, $viewtext['Overrides'][19]);
  $viewtext['Overrides'][19] = str_replace('%groupid%', $overridegroupid, $viewtext['Overrides'][19]);
  $tpl = Infobox($tpl, $viewtext['Overrides'][11], $viewtext['Overrides'][19], $viewtext['System'][3], 'index.php?section=groupoverrides');
}//--


} //-=============================

if ($section == "overrides"){ //--------------------------------------------------------



// 

if ($action == ""){ //--


$tpl->set_file("inhalt", "templates/overrides.tpl.htm");

  $tpl->set_var(array(
     "menue_overrides"            => $viewtext['Menu'][10],
     "menue_overrides_name"       => $viewtext['System'][70],
     "menue_overrides_type"       => $viewtext['System'][66],
     "menue_overrides_flags"      => $viewtext['System'][106],
     "menue_overrides_action"     => $viewtext['System'][40],
     "menue_overrides_edit"       => $viewtext['System'][11],
     "menue_overrides_del"        => $viewtext['System'][12],
     "add_override_button_text"   => $viewtext['Overrides'][6]

  ));
  
  $tpl->set_block("inhalt", "overrideanzeige", "override_handle");
  $tpl->set_block("inhalt", "overrideanzeigeempty", "overrideempty_handle");

  $sql = "SELECT * FROM ".$smtable."_overrides ORDER BY name ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

      $x = 0;
      for (;;){
        if ($overrides_type_value[$x]==$row['type']) break;
        $x++;
      }

          $flags = chunk_split( $row['flags'], 1, '&nbsp;' );

          if ($row['flags'] == "") $flags = $viewtext['Overrides'][4];

		  
		  $row['name'] = BadStringFilterShow($row['name']);
		  
       $tpl->set_var(array(

              "overrides_name"        => $row['name'],
              "overrides_type"        => $overrides_type[$x],
			  "overrides_type_value"  => $overrides_type_value[$x],
              "overrides_flags"       => $flags
      ));
       $tpl->parse("override_handle", "overrideanzeige", true);



    }
  }else{

  $tpl->set_var(array("overrides_empty" => $viewtext['Overrides'][8]));
  $tpl->parse("overrideempty_handle", "overrideanzeigeempty", true);

  }
  
  
  $flag = array();
  $flag = Setflags($viewtext);

 $tpl = ViewFlaginfo($tpl, $flag, $viewtext, $viewtext);
  

} //--

if (($action == "addoverride") or ($action == "editoverride")){ //---

$go = 1;

$tpl->set_file("inhalt", "templates/overrides_add_edit.tpl.htm");

if ($action == "addoverride"){
$tpl->set_var(array(
     "menue_overrides"                   => $viewtext['Overrides'][6],
     "execaddeditoverride"               => "index.php?section=overrides&action=execaddoverride"
    ));
    
$name = '';
$type = '';
$flags = '';

}

if ($action == "editoverride"){

$tpl->set_var(array(
     "menue_overrides"                => $viewtext['Overrides'][0],
     "execaddeditoverride"            => "index.php?section=overrides&action=execeditoverride&name=".$overridename."&type=".$overridetype.""
    ));


   $sql = "SELECT * FROM ".$smtable."_overrides WHERE name = '".$overridename."' AND type = '".$overridetype."'";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){        
    while($row = mysql_fetch_assoc($result)){

  
  $row['name'] = BadStringFilterShow($row['name']);
  
$name = $row['name'];
$type = $row['type'];
$flags = $row['flags'];


  }
  }else{
  
  $go = 0;
  
  }
    
}

if ($go == 1){


$tpl->set_var(array(
     "add_edit_override_button_text" => $viewtext['System'][7],
     "back_override_button_text"     => $viewtext['System'][4],
     "menue_overrides_info_general"  => $viewtext['System'][71],
     "menue_overrides_name"          => $viewtext['System'][71],
     "menue_overrides_type"          => $viewtext['System'][66],
     "menue_overrides_flags"         => $viewtext['System'][106],
     "overridesname"                 => $name
     ));



$tpl->set_block("inhalt", "scrolltypeblock", "scrolltypeblock_handle");

  for ($x = 0; $x <= 1; $x++){

    $tpl->set_var(array(
       "scrolltype"         => $overrides_type[$x],
       "scrolltypevalue"    => $overrides_type_value[$x]
    ));

    if ($type == $overrides_type_value[$x]){
      $tpl->set_var(array("scrolltypeselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("scrolltypeselected" => ""));
    }
    $tpl->parse("scrolltypeblock_handle", "scrolltypeblock", true);
  }
  
    $flag = array();
    $flag = Setflags($viewtext);

    $tpl = ViewFlagCheckbox($tpl, $flag, $smtable, 'overrides', $overridename, 'name', $viewtext, $viewtext);
  
  }else{
  
   $viewtext['Overrides'][26] = str_replace('%name%', $overridename, $viewtext['Overrides'][26]);
   $viewtext['Overrides'][26] = str_replace('%type%', $overrides_type_name, $viewtext['Overrides'][26]);
  
   $tpl = Infobox($tpl, $viewtext['Overrides'][0], $viewtext['Overrides'][26], $viewtext['System'][3], 'index.php?section=overrides');
  
  }
} //---

if (($action == "execaddoverride") or ($action == "execeditoverride")){ //---

$override = array();
$override['name']            = (isset($_POST['name'])) ? $_POST['name']:'';
$override['type']            = (isset($_POST['type'])) ? $_POST['type']:'';




$override['type_name'] = $override['type'];
foreach($overrides_type_value as $key => $value) {
  if ($value==$override['type_name']) $override['type_name'] = $overrides_type[$key];
}



$override['name'] = BadStringFilterShow($override['name']);


  $setflags = '';
  $flag = array();
  $flag = Setflags($viewtext);

  for ($x = 0; $x <= (count($flag['flag'])-1); $x++){

    if ((isset($_POST['flag_'.$flag['flag'][$x].''])) ? $_POST['flag_'.$flag['flag'][$x].'']:'' == "checked"){

        $setflags = $setflags.$flag['flag'][$x];
    }
  }


} //---

if ($action == "execaddoverride"){

//$check = Checkoverride($smtable, $override['name'], $override['type']);

  if (($override['name'] == "") or ($override['type'] == "")){
    $tpl = Infobox($tpl, $viewtext['Overrides'][6], $viewtext['System'][25], $viewtext['System'][4], 'index.php?section=overrides&action=addoverride');   
  }else{
    $sql = "SELECT * FROM ".$smtable."_overrides WHERE name = '".$override['name']."' AND type = '".$override['type']."'";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      $viewtext['Overrides'][25] = str_replace('%name%', $override['name'], $viewtext['Overrides'][25]);
	  $viewtext['Overrides'][25] = str_replace('%type%', $override['type_name'], $viewtext['Overrides'][25]);
      $tpl = Infobox($tpl, $viewtext['Overrides'][6], $viewtext['Overrides'][25], $viewtext['System'][4], 'javascript:history.back()');
    }else{
      sqlexec("INSERT INTO ".$smtable."_overrides SET
                type='".$override['type']."',
                name='".$override['name']."',
                flags='".$setflags."'
              ");
      $viewtext['Overrides'][21] = str_replace('%name%', $override['name'], $viewtext['Overrides'][21]);
	  $viewtext['Overrides'][21] = str_replace('%type%', $override['type_name'], $viewtext['Overrides'][21]);
	  $tpl = Infobox($tpl, $viewtext['Overrides'][6], $viewtext['Overrides'][21], $viewtext['System'][3], 'index.php?section=overrides');
    }
  }
} //-------


if ($action == "execeditoverride"){ //----
  if (($override['name'] == "") or ($override['type'] == "")){
    $tpl = Infobox($tpl, $viewtext['Overrides'][9], $viewtext['System'][25], $viewtext['System'][4], 'index.php?section=overrides&action=editoverride&name='.$overridename.'&type='.$overridetype.'');
  }else{
  
  
  
     $sql = "SELECT * FROM ".$smtable."_overrides WHERE 
	   (name = '".$override['name']."' AND type = '".$override['type']."') AND (name <> '".$overridename."' OR type <> '".$overridetype."')";
	 
	 $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
	
      $viewtext['Overrides'][25] = str_replace('%name%', $override['name'], $viewtext['Overrides'][25]);
	  $viewtext['Overrides'][25] = str_replace('%type%', $override['type_name'], $viewtext['Overrides'][25]);
      $tpl = Infobox($tpl, $viewtext['Overrides'][6], $viewtext['Overrides'][25], $viewtext['System'][4], 'javascript:history.back()');
	

	}else{
  
  
  
    sqlexec("UPDATE ".$smtable."_overrides SET
                type='".$override['type']."',
                name='".$override['name']."',
                flags='".$setflags."'
             WHERE
                name = '".$overridename."' AND type = '".$overridetype."'"
           );

    $viewtext['Overrides'][22] = str_replace('%name%', $override['name'], $viewtext['Overrides'][22]);
	$viewtext['Overrides'][22] = str_replace('%type%', $override['type_name'], $viewtext['Overrides'][22]);
    $tpl = Infobox($tpl, $viewtext['Overrides'][9], $viewtext['Overrides'][22], $viewtext['System'][3], 'index.php?section=overrides');
	
	
	
	}
	
	
	
  }
} //----

if ($action == "deloverride"){ //--
  $viewtext['Overrides'][23] = str_replace('%name%', $overridename, $viewtext['Overrides'][23]);
  $viewtext['Overrides'][23] = str_replace('%type%', $overrides_type_name, $viewtext['Overrides'][23]);
  $tpl = Infoboxselect($tpl, $viewtext['Overrides'][1], $viewtext['Overrides'][23], $viewtext['System'][2], $viewtext['System'][1], 'index.php?section=overrides', 'index.php?section=overrides&action=execdeloverride&name='.$overridename.'&type='.$overridetype.'' );
}//--

if ($action == "execdeloverride"){ //--
  sqlexec("DELETE FROM ".$smtable."_overrides WHERE name = '".$overridename."' AND type = '".$overridetype."'");
  $viewtext['Overrides'][24] = str_replace('%name%', $overridename, $viewtext['Overrides'][24]);
  $viewtext['Overrides'][24] = str_replace('%type%', $overrides_type_name, $viewtext['Overrides'][24]);
  $tpl = Infobox($tpl, $viewtext['Overrides'][1], $viewtext['Overrides'][24], $viewtext['System'][3], 'index.php?section=overrides');
}//--


} //-

}else{ // -+-+-
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][5], $viewtext['System'][3], 'index.php');
} // -+-+-


?>
