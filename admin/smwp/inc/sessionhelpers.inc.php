<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* sessionhelpers.inc.php                          *
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

function check_user($name, $pass, $cook, $ftabel)
{


// echo ''.$name.' + '.$pass.' + '.$cook.'';


if ($cook == "Y") $upass = $pass;
if ($cook == "N") $upass = MD5($pass);


// echo '='.$upass.'=';



    $sql=sprintf("SELECT UserId
    FROM ".$ftabel."_users
    WHERE UserName='".$name."' AND UserPass='".$upass."' LIMIT 1",
	mysql_real_escape_string($name),
	mysql_real_escape_string($upass));




    $result= mysql_query($sql) or die(mysql_error());
    if ( mysql_num_rows($result)==1)
    {
        $user=mysql_fetch_assoc($result);
        return $user['UserId'];

            }
    else
        return false;
}





function login($userid, $ftabel)
{

    $sql="UPDATE ".$ftabel."_users
    SET UserSession='".session_id()."'
    WHERE UserId=".$userid;
     mysql_query($sql);

}

function logged_in($ftabel)
{

    $sql="SELECT UserId
    FROM ".$ftabel."_users
    WHERE UserSession='".session_id()."'
    LIMIT 1";
    $result= mysql_query($sql);

return ( mysql_num_rows($result)==1);
}

function logout($ftabel)
{
    $sql="UPDATE ".$ftabel."_users
    SET UserSession=NULL
    WHERE UserSession='".session_id()."'";
     mysql_query($sql);
     
setcookie("wert17f20f88","0",Time()+62208000);
setcookie("wert27f20f88","0",Time()+62208000);
setcookie("wert17f20f88","",time()-3600);
setcookie("wert27f20f88","",time()-3600);
$wert17f20f88 = '';
$wert27f20f88 = '';

}


?>
