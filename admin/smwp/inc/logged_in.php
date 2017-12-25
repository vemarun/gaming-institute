<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* logged_in.php                                   *
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



$loginimsg = '0';

//echo $wert17f20f88.' moin2'.$wert27f20f88;



//if(isset($_COOKIE['wert27f20f88'])){
//echo $_COOKIE['wert17f20f88'];
//}



if ( (isset($_COOKIE['wert17f20f88'])) and (isset($_COOKIE['wert27f20f88'])) ){



    $userid=check_user($_COOKIE['wert17f20f88'], $_COOKIE['wert27f20f88'], 'Y', $table);
    if ($userid!=false){
    
    $execi = 'no';

login($userid, $table);
}
}


//--

if ($section == "logout"){
if (!logged_in($table)){
}else{

logout($table);


}
}

if (isset($_POST['login']))
{

if (!logged_in($table)){
}else{
logout($table);
}



    $userid=check_user($_POST['username'], $_POST['userpass'], 'N', $table);
    if ($userid!=false){

     $execi = 'no';

    login($userid, $table);

  

  if ((isset($_POST['checkboxpsave'])) ? $_POST['checkboxpsave']:'' == "save"){
  setcookie("wert27f20f88",md5($_POST['userpass']),Time()+62208000);
  setcookie("wert17f20f88",$_POST['username'],Time()+62208000);
}


    }else{

$loginimsg = '1';
        // Ihre Anmeldedaten waren nicht korrekt!
}
}

?>


