<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* output.php                                      *
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

session_start();

$exarea = (isset($_GET['exarea'])) ? $_GET['exarea']:'';

$space ='
';

if ($exarea == "sqladmins"){
  $filename = (isset($_GET['file'])) ? $_GET['file']:'';
  $outputfilecode = (isset($_POST['code'])) ? $_POST['code']:'';
  $outputfilecode =  stripslashes($outputfilecode);
  $file_content_output = file_get_contents("output/".$filename."");
  $file_content_output = str_replace('%insert%', $outputfilecode, $file_content_output);
  $file_content_output = str_replace('%date%', date("d/m/y"), $file_content_output);
}


if ($exarea == "serverconfigs"){

$filename = (isset($_GET['file'])) ? $_GET['file']:'';
$outputfilecode = (isset($_POST['code'])) ? $_POST['code']:'';
$outputfilecode =  stripslashes($outputfilecode);

$file_content_output = file_get_contents("output/config.cfg");
$file_content_output = str_replace('%file%', $filename, $file_content_output);
$file_content_output = str_replace('%insert%', $outputfilecode, $file_content_output);

}

if ($exarea == "mysqlbans"){
  $filename = (isset($_POST['file'])) ? $_POST['file']:'';
  
  $banexport = $_SESSION['baninfo'];

  $file_content_output = '';

  if ($filename == "banned_user.cfg") {
    foreach($banexport['steamid'] as $key => $value){
      $file_content_output = $file_content_output.'banid 0 '.$value.$space;
    }
  }
  if ($filename == "banned_ip.cfg") {
    foreach($banexport['ip'] as $key => $value){
      $file_content_output = $file_content_output.'addip 0 '.$value.$space;
    }
  }
}

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="'.$filename.'"');

print $file_content_output;
?>

