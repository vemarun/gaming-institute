<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* index.php                                       *
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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

For support and installation notes visit:
EN: http://forums.alliedmods.net/showthread.php?t=60174
DE: http://www.sourceserver.info/viewtopic.php?f=48&t=451
*/

error_reporting(E_ALL);
session_start();

$time = time();

include ("inc/function.php");


if (!(substr(phpversion(), 0, 1) >= 6))unregister_globals();
if ((substr(phpversion(), 0, 3) < "5.2")) include ("inc/json.php");



$section = (isset($_GET['section'])) ? $_GET['section']:'';
$action = (isset($_GET['action'])) ? $_GET['action']:'';
$id = (isset($_GET['id'])) ? $_GET['id']:'';
$lang = (isset($_GET['lang'])) ? $_GET['lang']:'';
$setlanguage = (isset($_GET['setlanguage'])) ? $_GET['setlanguage']:'';
$current_site = (isset($_GET["page"])) ? $_GET["page"]:'1';

$searchcat = (isset($_POST['serachcat'])) ? $_POST['serachcat']:'';
$searchstring = (isset($_POST['searchstring'])) ? $_POST['searchstring']:'';
if ($searchcat == '') $searchcat = (isset($_GET['searchcat'])) ? $_GET['searchcat']:'';
if ($searchstring == '') $searchstring = (isset($_GET['search'])) ? $_GET['search']:'';

if(isset($id)) settype($id, "integer");
if(isset($current_site)) settype($current_site, "integer");
if ($current_site < 1) $current_site = 1;

include ("inc/template.inc.php");
include ("inc/config.php");
include ("inc/define.php");

$tpl = new Template();
$tpl->set_file("header",       "templates/header.tpl.htm");
$tpl->set_file("left_navi",    "templates/left_navi.tpl.htm");
$tpl->set_file("hauptseite",   "templates/mainpage.tpl.htm");
$tpl->set_file("login",        "templates/login.tpl.htm");
$tpl->set_file("footer",       "templates/footer.tpl.htm");
$tpl->set_file("blank",        "templates/blank.tpl.htm");
$tpl->set_file("inhalt",       "templates/404.tpl.htm");

MySQLConnect($servername.':'.$dbport, $dbusername, $dbpassword, $dbname, '1');

$settings = Read_Settings($table);

if ($setlanguage != ""){
$lang = $setlanguage;
}

if ($lang != ""){
output_add_rewrite_var('lang', $lang);
$language = $lang;
}

if (isset($language)==False) $language = $settings['default_language'];

include ("inc/sessionhelpers.inc.php");
include ("inc/logged_in.php");

$in = Languagefile("inc/language_".$language.".txt");
$viewtext = $in['Language'];

include ("inc/serverstatus.inc.php");
include ("inc/languages.php");

$ses = "0";

$tpl->set_var(array(
   "404_header"       => $viewtext['System'][13],
   "404_text"         => $viewtext['System'][14],
   "404_backlink"     => "index.php",
   "404_button_text"  => $viewtext['System'][3],
));


$tpl->set_var(array(
   "header"       => $tpl->parse("out", "header"),
   "footer"       => $tpl->parse("out", "footer"),
   "logo"         => '<IMG src="inc/pics/logo.jpg" alt="Logo" border=0>',
   "version"      => $settings['version'],
   "chartset"	  => $languagearray['chartset'][$settings['default_language']]
));

$filename = 'setup.php';

if (file_exists($filename)) {

  $viewtext['System'][27] = str_replace('%file%', $filename, $viewtext['System'][27]);

  $tpl->set_file("blank", "templates/blank.tpl.htm");
  $tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][27], $viewtext['System'][3], 'index.php');
  $tpl->set_var(array("include" => $tpl->parse("out", "inhalt")));
  $tpl->parse("out", "blank");

}else{

if (!logged_in($table)){

  $ses = "0";
  $tpl->set_var(array(
     "logintext"         => $viewtext['Login'][0],
     "usernametext"      => $viewtext['System'][60],
     "passwordtext"      => $viewtext['System'][61],
     "passwordsavetext"  => $viewtext['Login'][3],
     "loginbuttontext"   => $viewtext['Login'][1]
  ));

  if ($loginimsg == "1"){
    $tpl->set_var(array("warning" => $viewtext['Login'][2].'<br>'));
  }else{
    $tpl->set_var(array("warning" => ""));
  }

  $tpl->parse("out", "login");

}else{

  $ses = "1";
  $usercheck = Getselfuserdata($table);
  
  if (($setlanguage == "") and ($lang == "") and ($usercheck['userlanguage'] != 'default')){
     $in = Languagefile("inc/language_".$usercheck['userlanguage'].".txt");
     $viewtext = $in['Language'];   
     $tpl->set_var(array("chartset" => $languagearray['chartset'][$usercheck['userlanguage']]));
  }

$foldercheck = 1;

if (!is_writable( 'temp' )){
$viewtext['System'][20] = str_replace('%dir%', '../temp', $viewtext['System'][20]);
$foldercheck = 0;
}

if (!is_writable( 'inc/pics/games' )){
$viewtext['System'][20] = str_replace('%dir%', '../inc/pics/games', $viewtext['System'][20]);
$foldercheck = 0;
}


if ($foldercheck == 0){
$viewtext['System'][20] = str_replace('%chmod%', '777', $viewtext['System'][20]);
$tpl = Infobox($tpl, $viewtext['System'][6], $viewtext['System'][20], $viewtext['System'][3], 'index.php');

}else{


  if($section != ""){
    if (isset($dateien[$section])) include $dateien[$section];
  }else{
    include $dateien['news'];
  }

}


  include ("navi.php");

  $tpl->set_var(array(     
     "messages"       => $viewtext['System'][16],
     "navi"           => $tpl->parse("out", "left_navi"),
     "anzeige"        => $tpl->parse("out", "inhalt"),
  ));
  
  $tpl->parse("out", "hauptseite");

}

}///////////////



$tpl->p("out");

mysql_close();
?>
