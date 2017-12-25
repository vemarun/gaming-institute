<?php
/**************************************************
* Sourcemod Webadmin Script                       *
*                                                 *
* function.php                                    *
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

//------------------------------------------------------------------------------

function MySQLConnect($servername, $dbusername, $dbpassword, $dbname, $die){

   $dbverbindung = @mysql_connect ("$servername", "$dbusername", "$dbpassword");
   if(!$dbverbindung){
     $error =  mysql_errno().": ".mysql_error();
     if ($die == 1): echo $error; die; else: return $error; endif;
   }

   $dbselect = @mysql_select_db ("$dbname");
   if(!$dbselect){
     $error = mysql_errno().": ".mysql_error();
     if ($die == 1): echo $error; die; else: return $error; endif;
   }

return True;
}

//------------------------------------------------------------------------------

function setlines_in_ban_scrollbox ($tpl){

          // Set templatevars (lines) to scrollbox.
         $tpl->set_var(array(
           "scrollbanlengthvalue"   => '',
           "scrollbanlengthint"     => '-----------------------',
           "scrollbanlengthdisc"     => "",
           "scrollbanlengthselected" => ""
         ));
         // Parse lines templatevars to the scrollbox block.
         $tpl->parse("scrollbanlengthblock_handle", "scrollbanlengthblock", true);
         

return $tpl;
         
}

//------------------------------------------------------------------------------

function checkipp ($ip, $viewtext){
  // Check empty IP
  if($ip == "") return $viewtext['System'][46];
  // Add IP numbers to a Array
  $part = split("[.]", $ip);
  // Check Count of segments.
  if(count($part) != 4) return $viewtext['System'][48];
  for($i=0; $i<count($part); $i++) {
    // Check segments are numbers
    if(!is_numeric($part[$i])) return $viewtext['System'][48];
    if($part[$i] > 255) return $viewtext['System'][48];
    if(0 > $part[$i]) return $viewtext['System'][48];
  }
   return 1;
}

//------------------------------------------------------------------------------

function checkport ($port, $viewtext){
  // Check empty PORT
  if($port == "") return $viewtext['System'][47];
  if(!is_numeric($port)) return $viewtext['System'][49];
  if(0 > $port) return $viewtext['System'][49];
  return 1;
}

//------------------------------------------------------------------------------

function GetServernme($tpl, $table, $ip, $port){

if ($port <> "") $ip = $ip.':'.$port;
  $sql = "SELECT * FROM ".$table."_server WHERE Ip = '".$ip."'";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){	
 	  $servername = $row['Name_Short']." <".$ip.">";
	}
  }else{
    $servername = $ip;
  }
return $servername;
}
//------------------------------------------------------------------------------

function PluginServerScrollbox($tpl, $table, $plugintable, $ipcolumn, $portcolumn, $select ,$viewtext){

   $server = array();
 
   $sql = "SELECT * FROM ".$plugintable."";
   $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){	    
	    if ($portcolumn <> ""){
	      $port = ':'.$row[$portcolumn];
	    }else{
	      $port = '';
	    }	    
		$ip = $row[$ipcolumn].$port;		
		$server[$ip] = '';	   
	  }
    }

	$tpl->set_file("inhaltserverscrollbox", "templates/inc/scrollbox.tpl.htm");
	$tpl->set_var(array(
	  "scrollboxname" => "serverscrollbox",
	  "scrollboxvalue" => "",
	  "scrollboxselected" => "",
	  "scrollboxtext" => $viewtext['System'][123]	  
	  ));
	$tpl->set_block("inhaltserverscrollbox", "scrollboxblock", "scrollboxblock_handle");
    $tpl->parse("scrollboxblock_handle", "scrollboxblock", true);
		
    foreach($server as $key => $value){
	  $sql = "SELECT * FROM ".$table."_server WHERE Ip = '".$key."'";
      $result = mysql_query($sql) OR die(mysql_error());
	  if(mysql_num_rows($result)){       
	    while($row = mysql_fetch_assoc($result)){
	      $tpl->set_var(array("scrollboxtext" => $row['Name_Short']." <".$key.">"));
		}
	  }else{         
        $tpl->set_var(array("scrollboxtext" => $key));
      }	  
      $tpl->set_var(array(
	    "scrollboxselected" => "",
		"scrollboxvalue" => $key
	  ));
	  if ($key == $select) $tpl->set_var(array("scrollboxselected" => "SELECTED"));
	  $tpl->parse("scrollboxblock_handle", "scrollboxblock", true);	  
	} 
	$tpl->set_var(array("serverscrollbox" => $tpl->parse("out", "inhaltserverscrollbox")));
}

//------------------------------------------------------------------------------

function PluginGameIcon($tpl, $table, $modcolumn, $input, $handle_count, $text){

  $gamepicpath = "inc/pics/games/";

  $tpl->set_file("inhaltmodpic".$handle_count, "templates/inc/modpic.tpl.htm");

  $tpl->set_block("inhaltmodpic".$handle_count, "gamepic", "gamepic_handle".$handle_count);
  $tpl->set_block("inhaltmodpic".$handle_count, "gametext", "gametext_handle".$handle_count);
 
  if (($text <> "") and ($text == $input)){
    $tpl->set_var(array("mod_text" => $input));
    $tpl->parse("gametext_handle".$handle_count, "gametext", true);
  }else{
    $sql = "SELECT * FROM ".$table."_mods WHERE ".$modcolumn." = '".$input."'";
    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){
        $tpl->set_var(array(
          "mod_text" => $row['name'],
          "mod_pic"  => $gamepicpath.$row['icon']
        ));
      }
    }else{
      $tpl->set_var(array(
        "mod_text" => $input,
        "mod_pic"  => "inc/pics/question_gray.gif"
      ));
    }
  $tpl->parse("gamepic_handle".$handle_count, "gamepic", true);
  }

  $tpl->set_var(array("modpic" => $tpl->parse("out", "inhaltmodpic".$handle_count)));

  return $tpl;
}


//------------------------------------------------------------------------------

function PluginGameScrollbox($tpl, $table, $modcolumn, $input){

  $tpl->set_block("inhalt", "scrollgametype", "scrollgametype_handle");

  $sql = "SELECT * FROM ".$table." WHERE ".$modcolumn." <> ''";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
      $tpl->set_var(array(
        "scrollgametypevalue"    => $row[$modcolumn],
        "scrollgametypetext"     => $row['name']
      ));
      if ($input==$row['advert']){
        $tpl->set_var(array("scrollgametypeselected" => "SELECTED"));
      }else{
        $tpl->set_var(array("scrollgametypeselected" => ""));
      }
      $tpl->parse("scrollgametype_handle", "scrollgametype", true);
    }
  }
  Return $tpl;
}

//------------------------------------------------------------------------------

function Picmanagement($tpl, $table, $column, $value, $piccolumn, $path, $viewtext, $link){
  $tpl->set_file("inhalt5", "templates/inc/picmanagement.tpl.htm");

  $tpl->set_block("inhalt5", "uploadbox", "uploadbox_handle");
  $tpl->set_block("inhalt5", "picoptions", "picoptions_handle");

  $delpic = (isset($_GET['delpic'])) ? $_GET['delpic']:'0';
  
  $sql = "SELECT * FROM ".$table." WHERE ".$column." = '".$value."' AND ".$piccolumn." <> 'NULL'";

  $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){

      while($row = mysql_fetch_assoc($result)){
        $pic = $row[$piccolumn];
        $tpl->set_var(array(
           "pic"              => $path.$row[$piccolumn],
           "picname"          => $row[$piccolumn],
           "del_pic_infotext" => $viewtext['System'][72],
           "target"           => $link."&delpic=1"
           ));
        }
      if ($delpic == 1){
        sqlexec("UPDATE ".$table." SET icon = NULL WHERE ".$column." = '".$value."'");
        if(is_file($path.$pic)) unlink($path.$pic);
        $tpl->parse("uploadbox_handle", "uploadbox", true);
      }else{
        $tpl->parse("picoptions_handle", "picoptions", true);
      }
    }else{
      $tpl->parse("uploadbox_handle", "uploadbox", true);
    }

  $tpl->set_var(array("picmanagement" => $tpl->parse("out", "inhalt5")));

return $tpl;

}

//------------------------------------------------------------------------------

function bantime_scrollbox($tpl, $btime, $viewtext, $viewtext ,$row){

// Define block for scrollbox entrys
$tpl -> set_block("inhalt", "scrollbanlengthblock", "scrollbanlengthblock_handle");


      // Define Permanent ban
      $btime['min'][-1] = 0;
      $btime['show'][-1] = $viewtext['Mysqlbans'][12];
      $btime['desc'][-1] = '';


       $definedtime = 0;

      // Check Defined Timeentry-Counts and make a loop
      for ($x = -1; $x <= count($btime['show']) - 2; $x++){
        if ($row['ban_length'] == $btime['min'][$x]) $definedtime = 1;
      }

      if ($definedtime == 0){

         // Call Funtion to get a Time Format
        $bantime = get_time_from_minutes($row['ban_length'], $viewtext, 'short');

        // Set templatevars to scrollbox
        $tpl->set_var(array(
           "scrollbanlengthvalue"   => $row['ban_length'],
           "scrollbanlengthint"     => $bantime['output'],
           "scrollbanlengthdisc"     => ''
         ));
         
         // Parse lines templatevars to the scrollbox block.
         $tpl->parse("scrollbanlengthblock_handle", "scrollbanlengthblock", true);
         
         //Call Funktion to set Lines in Scrollbox
         $tpl = setlines_in_ban_scrollbox ($tpl);
         
      }

      // Check Defined Timeentry-Counts and make a loop
      for ($x = -1; $x <= count($btime['show']) - 2; $x++){

       // Set templatevars to scrollbox in the loop
       $tpl->set_var(array(
         "scrollbanlengthvalue"   => $btime['min'][$x],
         "scrollbanlengthint"     => $btime['show'][$x],
         "scrollbanlengthdisc"     => $btime['desc'][$x],
       ));


       // Check out who entry are selectet in the scrollbox
       if ($row['ban_length'] == $btime['min'][$x]){
         $tpl->set_var(array("scrollbanlengthselected" => "SELECTED"));
       }else{
         $tpl->set_var(array("scrollbanlengthselected" => ""));
       }
       // Parse all templatevars to the scrollbox block.
       $tpl->parse("scrollbanlengthblock_handle", "scrollbanlengthblock", true);

       if (($x == -1) or ($x == 5) or ($x == 11)  or ($x == 17) or ($x == 20)) $tpl = setlines_in_ban_scrollbox ($tpl);
      }

      return $tpl;

}


//------------------------------------------------------------------------------
function SpaceLongWords($input, $maxlenght, $cutlenght){

 $cutlenght = $cutlenght - 1;

 // Split words to an Array
 $text = explode(' ',$input);
 // List all words from array
 foreach($text as $key => $value) {
   // If an word longer than 36 chars add an space every 18 chars
   if (strlen($value) > $maxlenght) $text[$key] = chunk_split($value,$cutlenght,' ');
 }
   // Join teh words from array to an string
   $output = implode (" ", $text);
   return $output;
}


//------------------------------------------------------------------------------
function BadStringFilterShow($text){

  // Convert all HTML entities to their applicable characters
  $text = htmlentities($text);
  $text = str_replace('<', '&lt;', $text);
  $text = str_replace('>', '&gt;', $text);

  $text = str_replace('{', '&#123;', $text);
  $text = str_replace('}', '&#125;', $text);
  
  
  return $text;
}

//------------------------------------------------------------------------------
function BadStringFilterSave($text){

  // Convert all HTML entities to their applicable characters
  $text = htmlentities($text);
  $text = str_replace('&lt;', '<', $text);
  $text = str_replace('&gt;', '>', $text);

  // Replace { }  to asci code!
  $text = str_replace('&#123;', '{', $text);
  $text = str_replace('&#125;', '}', $text);
  
  return $text;
}

// ------------------------------------------------------------------------------

function ConvertLatin1ToHtml($str) { 
    $html_entities = array ( 
        "&" =>  "&amp;",     
		"ß" =>  "&szlig;", 		
        "Á" =>  "&aacute;", 
		"á" =>  "&aacute;",      
        "Â" =>  "&Acirc;",     
        "â" =>  "&acirc;",      
        "Æ" =>  "&AElig;",
        "æ" =>  "&aelig;",
        "À" =>  "&Agrave;",
        "à" =>  "&agrave;",
        "Å" =>  "&Aring;",
        "å" =>  "&aring;",
        "Ã" =>  "&Atilde;",
        "ã" =>  "&atilde;",
        "Ä" =>  "&Auml;",
        "ä" =>  "&auml;",
        "Ç" =>  "&Ccedil;",
        "ç" =>  "&ccedil;",
		"É" =>  "&Eacute;",
        "é" =>  "&eacute;",
        "Ê" =>  "&Ecirc;", 
        "ê" =>  "&ecirc;",
        "È" =>  "&Egrave;",
		"è" =>  "&egrave;",
		"Î" =>  "&Icirc;",
		"î" =>  "&icirc;",
		"Ô" =>  "&Ocirc;",
        "ô" =>  "&ocirc;",
		"Ö" =>  "&Ouml;",
        "ö" =>  "&ouml;",
        "û" =>  "&ucirc;",
        "Ù" =>  "&Ugrave;",
        "ù" =>  "&ugrave;",
        "Ü" =>  "&Uuml;",
        "ü" =>  "&uuml;",
        "Ý" =>  "&Yacute;",
        "ý" =>  "&yacute;",
        "ÿ" =>  "&yuml;",
        "Ÿ" =>  "&Yuml;",
    ); 

    foreach ($html_entities as $key => $value){ 
        $str = str_replace($key, $value, $str); 
    } 
    return $str; 
} 

//------------------------------------------------------------------------------

function Languagefile($file){

  $in = file_get_contents($file);
  $in = Strip_Comments($in);
  $in = ConvertLatin1ToHtml($in);
  // $in = str_replace("»","&raquo;",$in);
  // $in = str_replace("«","&laquo;",$in);
  $in = Languagefile_bb_code($in);
  $in = preg_replace ('#}\s+(\w)#'   , '},$1'    , $in);
  $in = preg_replace ('#(\w+)\s*{#'  , '"$1":{'  , $in);
  $in = preg_replace ('#(\"(?:\\\"|[^"])+\")[\040\t]+(\"(?:\\\"|[^"])+\")(?:.*[\r\n])#U', '$1:$2,'  , $in);
  $in = preg_replace ('#,\s+}#'      , '}'       , $in);
  $in = preg_replace ('#}\s+}#'      , '}}'      , $in);
  $in = preg_replace ('#[\r\n]#'     , ''        , $in);
  $text = json_decode ('{' . $in . '}' , true);
 


 return $text;
}

//------------------------------------------------------------------------------
function Strip_Comments($textin){
    $comment1 = '/\*[^*]*\*+(?:[^/*][^*]*\*+)*/';
    $comment2 = '//[^\n]*';
    $comment3 = '\#[^\n]*';

    $single = "'[^'\\\]*(?:\\.[^'\\\]*)*'";
    $double = '"[^"\\\]*(?:\\.[^"\\\]*)*"';
    $other = '[^"\'/\#<]';

    $eot = '<<<\s?(\S+)\b.*^\\2';

    $textout = preg_replace ("#($other+ |
                  $single$other* |
                  $double$other* |
                  $eot$other*)|
                  $comment1|
                              $comment2|
                              $comment3
                #msx" ,'\1', $textin);

    return $textout;
}


//------------------------------------------------------------------------------
function Languagefile_bb_code($text){

  $text = str_replace("[br]", "<br>", $text);
  $text = preg_replace("/\[b\](.*?)\[\/b\]/si","<b>\\1</b>", $text);
  $text = preg_replace("/\[b\](.*?)\[\/b\]/si","<b>\\1</b>", $text);
  $text = preg_replace("/\[i\](.*?)\[\/i\]/si","<i>\\1</i>", $text);
  $text = preg_replace("/\[u\](.*?)\[\/u\]/si","<u>\\1</u>", $text);
  
  
  
  $text = preg_replace("/\[color=(.*)\](.*)\[\/color\]/Usi", "<font color='\\1'>\\2</font>", $text);


  return $text;
								  
}

//------------------------------------------------------------------------------


function unregister_globals() {
  if (ini_get('register_globals')) {
    $array = array('_REQUEST', '_FILES');
    foreach ($array as $value) {
      if(isset($GLOBALS[$value])){
        foreach ($GLOBALS[$value] as $key => $var) {
          if (isset($GLOBALS[$key]) && $var === $GLOBALS[$key]) {
            //echo 'found '.$key.' = '.$var.' in $'.$value."<br>";
            unset($GLOBALS[$key]);
          }
        }
      }
    }
  }
}

//------------------------------------------------------------------------------

function define_time_from_minutes ($viewtext){

// Define Minutes in Seconds
$settime['minutes']['min'] = array('1', '2', '10', '15', '30', '45');
$settime['minutes']['show'] = array('1', '5', '10', '15', '30', '45');
$settime['minutes']['desc'] = array_fill(0, count($settime['minutes']['show']), $viewtext['Date'][1]);
$settime['minutes']['desc'][0] = $viewtext['Date'][15];


// Define Hours in Seconds
$settime['hours']['min'] = array('60', '120', '180', '240', '480', '720');
$settime['hours']['show'] = array('1', '2', '3', '4', '8', '12');
$settime['hours']['desc'] = array_fill(0, count($settime['hours']['show']), $viewtext['Date'][2]);
$settime['hours']['desc'][0] = $viewtext['Date'][16];

// Define Days in Seconds
$settime['days']['min']= array('1440', '2880', '4320', '5760', '7200', '8640');
$settime['days']['show'] = array('1', '2', '3', '4', '5', '6');
$settime['days']['desc']= array_fill(0, count($settime['days']['show']), $viewtext['Date'][3]);
$settime['days']['desc'][0] = $viewtext['Date'][17];

// Define Weeks in Seconds
$settime['weeks']['min']= array('10080', '20160', '30240');
$settime['weeks']['show'] = array('1', '2', '3');
$settime['weeks']['desc']= array_fill(0, count($settime['weeks']['show']), $viewtext['Date'][4]);
$settime['weeks']['desc'][0] = $viewtext['Date'][18];

// Define Months in Seconds
$settime['months']['min']= array('40320', '80640', '120960', '241920', '483840');
$settime['months']['show'] = array('1', '2', '3', '6', '12');
$settime['months']['desc']= array_fill(0, count($settime['months']['show']), $viewtext['Date'][5]);
$settime['months']['desc'][0] = $viewtext['Date'][19];

$btime['desc'] = array_merge($settime['minutes']['desc'], $settime['hours']['desc'], $settime['days']['desc'], $settime['weeks']['desc'], $settime['months']['desc']);
$btime['min'] = array_merge($settime['minutes']['min'], $settime['hours']['min'], $settime['days']['min'], $settime['weeks']['min'], $settime['months']['min']);
$btime['show'] = array_merge($settime['minutes']['show'], $settime['hours']['show'], $settime['days']['show'], $settime['weeks']['show'], $settime['months']['show']);

return $btime;

}

//------------------------------------------------------------------------------

function get_time_from_minutes($btime, $viewtext, $art){


$getbtime = array();
$view = array();

// when seconds over 1 Jear then set to 1 Jear
if ($btime > '483840') $btime = '483840';

// Get count of month and rest
$getbtime['month'] = floor($btime/40320);
$rest = $btime%40320;
// Get count of weeks and rest
$getbtime['weeks'] = floor($rest/10080);
$rest = $rest%10080;
// Get count of days and rest
$getbtime['days'] = floor($rest/1440);
$rest = $rest%1440;
// Get count of hours and rest
$getbtime['hours'] = floor($rest/60);
$rest = $rest%60;
// Get count of minutes and rest
$getbtime['minutes'] = $rest%60;

if ($art == 'short'){
$view[1] = $viewtext['Date'][8];
$view[2] = $viewtext['Date'][9];
$view[3] = $viewtext['Date'][10];
$view[4] = $viewtext['Date'][11];
$view[5] = $viewtext['Date'][12];
}

if ($art == 'long'){
$view[1] = '&nbsp;'.$viewtext['Date'][1];
$view[2] = '&nbsp;'.$viewtext['Date'][2];
$view[3] = '&nbsp;'.$viewtext['Date'][3];
$view[4] = '&nbsp;'.$viewtext['Date'][4];
$view[5] = '&nbsp;'.$viewtext['Date'][5];
}

$getbtime['output'] = '';

// Check Timevars is not 0
if ($getbtime['month'] != 0){
$getbtime['output'] = $getbtime['month'].''.$view[5].' ';
}
if ($getbtime['weeks'] != 0){
$getbtime['output'] = $getbtime['output'].$getbtime['weeks'].''.$view[4].' ';
}
if ($getbtime['days'] != 0){
$getbtime['output'] = $getbtime['output'].$getbtime['days'].''.$view[3].' ';
}
if ($getbtime['hours'] != 0){
$getbtime['output'] = $getbtime['output'].$getbtime['hours'].''.$view[2].' ';
}
if ($getbtime['minutes'] != 0){
$getbtime['output'] = $getbtime['output'].$getbtime['minutes'].''.$view[1].' ';
}

return $getbtime;

}

 //------------------------------------------------------------------------------

function Read_Settings($table){

$settings = array();
$sql = "SELECT * FROM ".$table."_settings";
$result = mysql_query($sql) OR die(mysql_error());
if(mysql_num_rows($result)){
  while($row = mysql_fetch_assoc($result)){
    $settings[$row['Name']] = $row['Value'];
  }
}

return $settings;

}

 //------------------------------------------------------------------------------

function Table_Exists($table_name){

$sql = mysql_query("show tables like '" .$table_name . "'");
  if(mysql_fetch_row($sql) === false){
  return false;
  }else{
  return true;
  }
  
}


//------------------------------------------------------------------------------
function picupload($inputname, $target_patch, $viewtext, $width, $height){

  $data = array();
  $data['check'] = 0;
  $data['picname'] = (isset($_FILES[$inputname]['name'])) ? $_FILES[$inputname]['name']:'';
  
  if ($data['picname']!= ""){
     $type = (isset($_FILES[$inputname]['type'])) ? $_FILES[$inputname]['type']:'';

	 $types = array('image/gif','image/jpeg','image/pjpeg','image/png');
    
	 if (in_array($type,$types)) {

         $tempname = (isset($_FILES[$inputname]['tmp_name'])) ? $_FILES[$inputname]['tmp_name']:'';
         $size = (isset($_FILES[$inputname]['size'])) ? $_FILES[$inputname]['size']:'';

	  

		 if (file_exists($target_patch.'/'.$data['picname'])){		   
		   for($a=1; $a < 1000000; $a++){
		     if (!file_exists($target_patch.'/'.$a.'_'.$data['picname'])){
		       $data['picname'] = $a.'_'.$data['picname'];
		       break;
		     }
		   } 		
		 }
		 
		 $target = $target_patch.'/'.$data['picname'];
		 
		 $viewtext['System'][78] = str_replace('%filename%', $data['picname'], $viewtext['System'][78]);
         $viewtext['System'][78] = str_replace('%filesize%', $size, $viewtext['System'][78]);
         $data['msg'] = $viewtext['System'][78];
	  
		 list($filewidth, $fileheight) = @getimagesize($tempname);
		 
		 if (($filewidth > $width ) or ($fileheight > $height)){
		     $viewtext['System'][81] = str_replace('%width%', $width, $viewtext['System'][81]);
     		 $viewtext['System'][81] = str_replace('%height%', $height, $viewtext['System'][81]);
	         $data['msg'] = $data['msg'].$viewtext['System'][81]; 
		 }else{
		       
	         if(!@move_uploaded_file($tempname, $target)){		       
				 $data['msg'] = $data['msg'].$viewtext['System'][80]; 		
	         }else{			   
	             $data['msg'] = $data['msg'].$viewtext['System'][32].'<br>--<br>';
				 $data['check'] = 1;
	         }			 
		 }
		
	 }else{
       $viewtext['System'][79] = str_replace('%type%', $type, $viewtext['System'][79]);
	   $data['msg'] = $viewtext['System'][79];
     }	
  }else{
     $data['msg'] = $viewtext['System'][31];
  }
  
  return $data;

}

// $FILES[$inputname]['error'];
//------------------------------------------------------------------------------
function uploadfile($inputname, $viewtext){


$data = array();
$data['file_content'] = '';

  $name = (isset($_FILES[$inputname]['name'])) ? $_FILES[$inputname]['name']:'';
  
  if ($name != ""){
  	
	$data['filename'] = $name;
  
    $type = (isset($_FILES[$inputname]['type'])) ? $_FILES[$inputname]['type']:'';
    if (($type != "application/octet-stream") or ($type != "text/plain")) {
      
      $tempname = (isset($_FILES[$inputname]['tmp_name'])) ? $_FILES[$inputname]['tmp_name']:'';
      $size = (isset($_FILES[$inputname]['size'])) ? $_FILES[$inputname]['size']:'';
	  	  

$viewtext['System'][29] = str_replace('%filelines%', count(file($tempname)), $viewtext['System'][29]);
$viewtext['System'][29] = str_replace('%filename%', $name, $viewtext['System'][29]);
$viewtext['System'][29] = str_replace('%filesize%', $size, $viewtext['System'][29]);

     
      $data['msg'] = $viewtext['System'][29];
      $data['file_content'] = file_get_contents($tempname);
    }else{
      $data['msg'] = $viewtext['System'][30];

    }
  }else{
    $data['msg'] = $viewtext['System'][31];
	$data['filename'] = '';
  }
  
  return $data;
}


//------------------------------------------------------------------------------

function read_banfile($content, $bannedby, $playername, $reason, $time, $viewtext){

$ban = array();
$date = date("Y-m-d H:m:s", $time);

$idcount = 0;
$ipcount = 0;

$pattern = '/^\s*(\w+)\s+(\d+)\s+(.*?)(?:\s|$)/m';
preg_match_all($pattern, $content, $matches, PREG_PATTERN_ORDER);

for ($x = 0; $x <= count($matches[3])-1; $x++){
  $ban['entry']['name'][$x] = $playername;
  $ban['entry']['reason'][$x] = $reason;
  $ban['entry']['admin'][$x] = $bannedby;
  $ban['entry']['date'][$x] = $date;
  $ban['entry']['banlenght'][$x] = $matches[2][$x];

  if ($matches[1][$x] == 'banid'){
    $ban['entry']['steam'][$x] = $matches[3][$x];
    $ban['entry']['ip'][$x] = '0';
    $idcount++;
  }
  if ($matches[1][$x] == 'addip'){
    $ban['entry']['steam'][$x] = '0';
    $ban['entry']['ip'][$x] = $matches[3][$x];
    $ipcount++;
  }

}

// Filter dubbleentrys

$ban['dubblentrys'] = array();
$ban['dubblentrys'][0] ='';

for ($x = 0; $x <= count($matches[3]) - 1; $x++){
  $i=0;
  foreach($matches[3] as $key => $value){
    if ($value == $matches[3][$x]) $i++;
    if (($i > 1) and ($value == $matches[3][$x])) $ban['dubblentrys'][] = $key;
  }
}

$ban['dubblentrys'] = array_unique($ban['dubblentrys']);


// Show Entrys Count

if (count($matches[3]) == 1){
  $ban['msg'] = $viewtext['Mysqlbans'][27];
}else{
  $ban['msg'] = str_replace('%num%', count($matches[3]), $viewtext['Mysqlbans'][28]);
}

// Show ID or IP Bans count

if ($idcount == 1){
  $ban['msg'] = $ban['msg'].'<br>'.str_replace('%numip%', $ipcount, $viewtext['Mysqlbans'][29]);
}else{
  $viewtext['Mysqlbans'][30] = str_replace('%numip%', $ipcount, $viewtext['Mysqlbans'][30]);
  $viewtext['Mysqlbans'][30] = str_replace('%numid%', $idcount, $viewtext['Mysqlbans'][30]);
  $ban['msg'] = $ban['msg'].'<br>'.$viewtext['Mysqlbans'][30];
}

// Show dubbleentrys count

if (count($ban['dubblentrys']) == 1){
  $ban['msg'] = $ban['msg'].'<br>'.$viewtext['Mysqlbans'][31];
}else{
  $ban['msg'] = $ban['msg'].'<br>'.str_replace('%num%', count($ban['dubblentrys'])-1, $viewtext['Mysqlbans'][32]);
}

// Retun with array $ban
return $ban; 

}

//------------------------------------------------------------------------------

function show_search($tpl, $allsearchcategories, $searchcat, $searchstring, $viewtext, $plugin, $extracommand){

  $tpl->set_file("inhalt4", "templates/inc/search.tpl.htm");
  $tpl->set_block("inhalt4", "scrollsearchblock", "scrollsearchblock_handle");
  
  if ($plugin != '') $tpl->set_var(array("searchplugin" => '&plugin='.$plugin.''));

  for ($x = 0; $x <= count($allsearchcategories['value']) - 1; $x++){

    $tpl->set_var(array(
      "scrollserachcatvalue" => $allsearchcategories['value'][$x],
      "scrollserachcat" => $allsearchcategories['view'][$x]
    ));

    if ($allsearchcategories['value'][$x]==$searchcat){
      $tpl->set_var(array("scrollserachcatselected" => "SELECTED"));
    }else{
      $tpl->set_var(array("scrollserachcatselected" => ""));
    }

    $tpl->parse("scrollsearchblock_handle", "scrollsearchblock", true);

  }
  
  $tpl->set_var(array(
  "searchstring" => $searchstring,
  "searchbuttontext" => $viewtext['System'][28],
  "search_discription" => $viewtext['System'][28],
  "extracommand" => $extracommand
  ));
  
   $tpl->set_var(array("search"  => $tpl->parse("out", "inhalt4")));

  return $tpl;
}

//------------------------------------------------------------------------------

function site_links($tpl, $all_entrys, $entrys_per_site, $current_site, $section, $id, $plugin, $searchcat, $searchstring, $extracommand, $viewtext){

$limit = 10;

$leftlimit = $current_site - ($limit +1);
$rightlimit = $current_site + ($limit +1);

//Errechnen wieviele Seiten es geben wird
$count_sites = $all_entrys / $entrys_per_site;

$tpl->set_file("inhalt3", "templates/inc/list_sites.tpl.htm");

$tpl->set_block("inhalt3", "prew_firs_site", "prew_firs_site_handle");
$tpl->set_block("inhalt3", "link_prew_firs_site", "link_prew_firs_site_handle");
$tpl->set_block("inhalt3", "next_last_site", "next_last_site_handle");
$tpl->set_block("inhalt3", "link_next_last_site", "link_next_last_site_handle");

$tpl->set_var(array("section"  => $section));

if ($id != '') $tpl->set_var(array("pid"  => '&id='.$id.''));
if ($plugin != '') $tpl->set_var(array("pageplugin" => '&plugin='.$plugin.''));
if ($searchcat != '') $tpl->set_var(array("searchcat" => '&searchcat='.$searchcat.''));
if ($searchstring != '') $tpl->set_var(array("searchstring" => '&search='.$searchstring.''));
if ($extracommand != '') $tpl->set_var(array("extracommand" => $extracommand));


if  ($current_site == 1){

//$tpl->parse("prew_firs_site_handle", "prew_firs_site", true);

}else{

   if($current_site > $limit +1){
  $tpl->set_var(array(
    "first_site"  => 1,
    "prev_site" => $current_site - 1));
  $tpl->parse("link_prew_firs_site_handle", "link_prew_firs_site", true);
  }
}



if  ($current_site == ceil($count_sites)){
 //$tpl->parse("next_last_site_handle", "next_last_site", true);
}else{

 //echo $current_site.' < '.$limit;
  if($current_site < ($count_sites - $limit)){

  $tpl->set_var(array(
    "next_site" => $current_site + 1,
    "last_site" => ceil($count_sites)
    ));
  $tpl->parse("link_next_last_site_handle", "link_next_last_site", true);
  }
}
// Ausgabe der Links zu den Seiten

$tpl->set_block("inhalt3", "linktosite_left", "linktosite_left_handle");
$tpl->set_block("inhalt3", "current_site_block", "current_site_block_handle");
$tpl->set_block("inhalt3", "linktosite_right", "linktosite_right_handle");

if ($count_sites == 0){


     $tpl->set_var(array("current_site"  => '1'));
     $tpl->parse("current_site_block_handle", "current_site_block", true);
}else{



for($a=0; $a < $count_sites; $a++){
  $b = $a + 1;

   if($current_site == $b){
     // Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben
     $tpl->set_var(array("current_site"  => $b));
     $tpl->parse("current_site_block_handle", "current_site_block", true);
   }else{

    // Auf dieser Seite ist der User nicht

    // Limit Checken
    if (($b > $leftlimit) and ($b < $rightlimit)){

    //Einen Link ausgeben
     $tpl->set_var(array("to_site"  => $b));
     if ($b < $current_site) $tpl->parse("linktosite_left_handle", "linktosite_left", true);
     if ($b > $current_site) $tpl->parse("linktosite_right_handle", "linktosite_right", true);
   }
   }

}

}
$tpl->set_var(array(
    "switch_sites_links"  => $tpl->parse("out", "inhalt3"),
	"text_goto_page"      => $viewtext['System'][22]
	
	));

return $tpl;
}

//------------------------------------------------------------------------------


function showentrys($tpl ,$text ,$entryname, $start_entry, $all_entrys, $show_clients){


if (($all_entrys - $start_entry) < $show_clients){
$end_entry = $all_entrys;
}else{
$end_entry = ($start_entry + $show_clients);
}

if ($end_entry != 0) $start_entry = $start_entry +1;

$text = str_replace('%entry%', $entryname, $text);
$text = str_replace('%start%', $start_entry, $text);
$text = str_replace('%end%', $end_entry, $text);
$text = str_replace('%count%', $all_entrys, $text);

$tpl->set_var(array("show_sites_antry_counts"  => $text));

return $tpl;

}

//------------------------------------------------------------------------------

function get_hits_management($manage_tabel, $managesource_coll, $source) {


$sql_hits = "select count(*) as hits from ".$manage_tabel." WHERE ".$managesource_coll." = '".$source."'";

$count = mysql_query($sql_hits) OR die("Query: <pre>".$sql_hits."</pre>\n".
"Antwort: ".mysql_error());

$count = mysql_fetch_row($count);

    return $count[0];
}



//------------------------------------------------------------------------------

function get_hits_servermanagement($manage_tabel, $managesource_coll, $source, $plugin) {


$sql_hits = "select count(*) as hits from ".$manage_tabel." WHERE (".$managesource_coll." = '".$source."') AND (Plugin_Systemname = '".$plugin."')";

$count = mysql_query($sql_hits) OR die("Query: <pre>".$sql_hits."</pre>\n".
"Antwort: ".mysql_error());

$count = mysql_fetch_row($count);

    return $count[0];
}



//------------------------------------------------------------------------------


function get_extrainfos_hits_servermanagement($table, $getcoll, $serverid) {

$sql_hits = "SELECT ".$getcoll.", COUNT(*) FROM ".$table." WHERE Server_ID = '".$serverid."' GROUP BY ".$getcoll." HAVING COUNT(*) > 0 ORDER BY ".$getcoll."";

$result = mysql_query($sql_hits) OR die("Query: <pre>".$sql_hits."</pre>\n".
"Antwort: ".mysql_error());

 $i = 0;
     if(mysql_num_rows($result)){
       while($row = mysql_fetch_assoc($result)){

       $i++;
       }
       }

    return $i;
}

//------------------------------------------------------------------------------

function GetFriendID($pszAuthID){

// Thx to Seather for this nice function
// http://forums.alliedmods.net/showthread.php?p=565979

	$iServer = "0";
  $iAuthID = "0";
	
	$szAuthID = $pszAuthID;
	
	$szTmp = strtok($szAuthID, ":");
	
	while(($szTmp = strtok(":")) !== false){
    $szTmp2 = strtok(":");
    if($szTmp2 !== false){
      $iServer = $szTmp;
      $iAuthID = $szTmp2;
    }
  }

  if($iAuthID == "0") return "0";

  $i64friendID = bcmul($iAuthID, "2");
  $i64friendID = bcadd($i64friendID, bcadd("76561197960265728", $iServer));
	
	$i64friendIDLink = 'http://steamcommunity.com/profiles/'.$i64friendID;
	
	return $i64friendIDLink;
}

//------------------------------------------------------------------------------

function search_sql_injection_filter($allsearchcategories, $searchcat, $searchstring, $extracommand){

  if ($searchstring <> ''){
    $x = 0;
    for (;;){
      if ($allsearchcategories['value'][$x]==$searchcat) break;
      $x++;
    }

    if ($extracommand == ""){
      return "WHERE ".$allsearchcategories['table'][$x]." LIKE '%$searchstring%'";
    }else{
      return "WHERE ".$allsearchcategories['table'][$x]." LIKE '%$searchstring%' AND ".$extracommand."";
    }
  }else{
    if ($extracommand == ""){
      return "";
    }else{
      return "WHERE ".$extracommand."";
    }
  }
 }

//------------------------------------------------------------------------------

function select_result($allsearchcategories, $searchcat, $searchstring, $row) {

  if ($searchstring <> ''){

  $x = 0;
  for (;;){
    if ($allsearchcategories['value'][$x]==$searchcat) break;
    $x++;
  }
  return mysql_num_rows(mysql_query("SELECT * FROM ".$table." WHERE ".$allsearchcategories['table'][$x]." LIKE '%$searchstring%'"));
}else{
  return mysql_num_rows(mysql_query("SELECT * FROM ".$table.""));
}



}
//------------------------------------------------------------------------------

function alter($alter) {
    $jahre = 0;
    $now = time();
    $jahrnow = (int) date("Y",$now);
    $jahrdann = (int) date("Y",$alter);
    $jahre = $jahrnow - $jahrdann;
    $totest = array ("n","j","G","i","s");
    for ($i = 0; $i < count($totest);$i++) {
        if (((int) date($totest[$i],$alter)) != ((int) date($totest[$i],$now)))
            return ((int) date($totest[$i],$alter) > (int) date($totest[$i],$now)) ?  --$jahre : $jahre;
    }
    return $jahre;
}


//------------------------------------------------------------------------------

function Infobox($tpl, $header, $text, $buttontext, $backlink){

  $tpl->set_file("inhalt", "templates/infobox.tpl.htm");
  
  $tpl->set_var(array(
     "infobox_header"      => $header,
     "infobox_text"        => $text,
     "infobox_button_text" => $buttontext,
     "infobox_backlink"    => $backlink
  ));

return $tpl;

}

//------------------------------------------------------------------------------

function Infoboxselect($tpl, $header, $text, $buttontextno, $buttontextyes, $nolink, $yeslink ){

  $tpl->set_file("inhalt", "templates/infoboxselect.tpl.htm");

  $tpl->set_var(array(
     "infobox_header"          => $header,
     "infobox_text"            => $text,
     "infobox_button_text_no"  => $buttontextno,
     "infobox_button_text_yes" => $buttontextyes,
     "infobox_nolink"          => $nolink,
     "infobox_yeslink"         => $yeslink
  ));

return $tpl;

}

//------------------------------------------------------------------------------

function checkip($ip) {

$explode_array = explode(".", $ip);

if (count($explode_array) == 4){
  foreach($explode_array as $key => $value){
    if ((($value >= 1) and ( 255 >= $value)) and (is_numeric($value) === True)) {
    }else{
      return 0;
    }
  }
}else{
  return 0;
}
  return 1;
}

//------------------------------------------------------------------------------

function Checkuseradd($table, $username, $usermail, $userpass, $userpassretry, $userlanguage){

  $sql = "SELECT * FROM ".$table."_users WHERE
  UserName = '".$username."' OR UserMail = '".$usermail."'";
  $result = mysql_query($sql) OR die(mysql_error());

  if(mysql_num_rows($result)) return "102";
  if (($username == "") or ($userpass == "") or ($usermail == "") or ($userlanguage == ""))return "25";
  if ($userpass != $userpassretry) return "101";
  if (!ereg("^.+@.+\\..+$", $usermail)) return "103";

return True;

}

//------------------------------------------------------------------------------

function Checkuseredit($table, $username, $usermail, $userpass, $userpassretry, $userid, $userlanguage){

  $sql = "SELECT * FROM ".$table."_users WHERE
  ((UserName = '".$username."' OR UserMail = '".$usermail."') AND (UserID <>'".$userid."'))";
  $result = mysql_query($sql) OR die(mysql_error());

  if(mysql_num_rows($result)) return "102";
  if (($username == "") or ($usermail == "") or ($userlanguage == ""))return "25";
  if ($userpass != $userpassretry) return "101";
  if (!ereg("^.+@.+\\..+$", $usermail)) return "103";

return True;

}

//------------------------------------------------------------------------------

function showuserlanguagescrollbox($tpl, $viewtext, $inputkey, $languagearray){

$tpl->set_block("inhalt", "scrolluserlanguageblock", "scrolluserlanguageblock_handle");

//$language = language();

$languagearray['view']['default'] = $viewtext['User'][5];

foreach($languagearray['view'] as $key => $value) {

  $tpl->set_var(array(
    "scrolluserlanguagevalue"  => $key,
    "scrolluserlanguage"       => $value,
  ));

  if ($key == $inputkey){
    $tpl->set_var(array("scrolluserlanguageselected" => "SELECTED"));
  }else{
    $tpl->set_var(array("scrolluserlanguageselected" => ""));
  }

  $tpl->parse("scrolluserlanguageblock_handle", "scrolluserlanguageblock", true);
}

 return $tpl;

 }

//------------------------------------------------------------------------------

/*function Checkgroupoverride($smtable, $name, $groupid, $type, $access){

  if (($name == "") or ($groupid == "") or ($type == "") or ($access == "")) return "17";

 $sql = "SELECT * FROM ".$smtable."_group_overrides WHERE name = '".$name."'";
 $result = mysql_query($sql) OR die(mysql_error());
 if(mysql_num_rows($result))return "19";

 return "18";
}*/


//------------------------------------------------------------------------------

/*function Checkoverride($smtable, $name, $type){

  if (($name == "") or ($type == "")) return "17";

 $sql = "SELECT * FROM ".$smtable."_overrides WHERE name = '".$name."'";
 $result = mysql_query($sql) OR die(mysql_error());
 if(mysql_num_rows($result))return "37";

 return "33";
}*/


//------------------------------------------------------------------------------

function Gettableentry($table, $targetcolumn, $entry, $getcolumn){

  $sql = "SELECT * FROM ".$table." WHERE ".$targetcolumn." = '".$entry."'";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
      if ($row[$getcolumn] == '0') $row[$getcolumn] = '00';
      return $row[$getcolumn];
    }
  }else{
    return False;
  }
}


//------------------------------------------------------------------------------

function CheckColumnExist($table, $column){

  $result=mysql_query("SELECT ".$column." FROM ".$table);
  if (mysql_errno()){
    return False;
  }else{
    return True;
  }
}

//------------------------------------------------------------------------------

function Maxlength($quelle){

  $zahl1 = 0;
  $zahl2 = 0;

   for ($x = 1; $x <= count($quelle); $x++){
     $zahl1 = strlen($quelle[$x]);
   if ($zahl1 >= $zahl2){
      $zahl2 = $zahl1;
    }
  }
return $zahl2;
}


//------------------------------------------------------------------------------


function Getspace($output, $maxlengt){

$space = '';

$lengt = strlen($output);
$spacelengt = $maxlengt - $lengt;

  for ($x = 1; $x <= $spacelengt + 1; $x++){
    $space = $space." ";
  }

return $space;
}

//------------------------------------------------------------------------------

function get_servername($table, $sid){

$sql = "SELECT * FROM ".$table."_server WHERE ID = '".$sid."' ORDER BY Name_Short ASC";
        $result = mysql_query($sql) OR die(mysql_error());
        if(mysql_num_rows($result)){
          while($row = mysql_fetch_assoc($result)){

         return $row['Name_Short'];

          }
        }


}

//------------------------------------------------------------------------------

function get_servernames_from_pluginsid($table, $pluginsystemname, $serverpluginid){

 $server = array();
 $sql = "SELECT * FROM ".$table."_server_plugins_mss WHERE (Plugin_Systemname = '".$pluginsystemname."') AND (Plugin_Server_ID = '".$serverpluginid."')";

    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result)){
      while($row = mysql_fetch_assoc($result)){
      
      
      
       $server[$row['Interface_Server_ID']] = get_servername($table, $row['Interface_Server_ID']);

    
      }
    }

return $server;
}



//------------------------------------------------------------------------------

function Servermmslist($table, $pluginmsscolum, $plugintablename, $pluginsystemname){
	 $serverids = array();
     $sql = "SELECT ".$pluginmsscolum.", COUNT(*) FROM ".$plugintablename." GROUP BY ".$pluginmsscolum." HAVING COUNT(*) > 0 ORDER BY ".$pluginmsscolum."";
     $result = mysql_query($sql) OR die(mysql_error());
     if(mysql_num_rows($result)){
         while($row = mysql_fetch_assoc($result)){
             $servercount = get_hits_servermanagement($table."_server_plugins_mss", "Plugin_Server_ID", $row[$pluginmsscolum], $pluginsystemname);
             $serverids['1'][$row[$pluginmsscolum]] = $servercount;
        }
    }
     $sql = "SELECT Plugin_Server_ID FROM ".$table."_server_plugins_mss WHERE Plugin_Systemname = '".$pluginsystemname."' ORDER BY Plugin_Server_ID";
     $result = mysql_query($sql) OR die(mysql_error());
     if(mysql_num_rows($result)){
         while($row = mysql_fetch_assoc($result)){
             $found = '0';
             if (isSet($serverids['1'])){
                 foreach($serverids['1'] as $key => $value) {
                     if ($key == $row['Plugin_Server_ID']) $found = '1';
                }
             }
             if ($found == '0'){
                 if (!isSet($serverids['2'][$row['Plugin_Server_ID']])) $serverids['2'][$row['Plugin_Server_ID']] = '0';
                 $serverids['2'][$row['Plugin_Server_ID']] = $serverids['2'][$row['Plugin_Server_ID']] + 1;
             }
         }
     }
	 
	// $serveridsready = array();
	 
    if ((isSet($serverids['1'])) OR (isSet($serverids['2']))){
         $serveridsready = array();
         
		 if (isSet($serverids['1'])){
             foreach($serverids['1'] as $key => $value){ //-+-
                 $serveridsready[$key] = $value;
             }
         }
		 
         if (isSet($serverids['2'])){
             foreach($serverids['2'] as $key => $value){ //-+-
             $serveridsready[$key] = $value;
             }
         }
     return $serveridsready;
	 }
     
}

//------------------------------------------------------------------------------


function Getselfuserdata($table){

$usercheck = array();

  $sql="SELECT * FROM ".$table."_users WHERE UserSession='".session_id()."' LIMIT 1";
      $result= mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){

      $usercheck['id'] = $row['UserID'];
      $usercheck['name'] = $row['UserName'];
      $usercheck['mail'] = $row['UserMail'];
      $usercheck['edit'] = $row['UserEditUsers'];
      $usercheck['userlanguage'] = $row['UserLanguage'];
      $usercheck['owner'] = $row['UserOwner'];
      $usercheck['datum'] = $row['UserDatum'];
      $usercheck['editpermissions'] = $row['UserEditPermissions'];
      $usercheck['sqladmins'] = $row['UserSQLAdmins'];
      $usercheck['editpluginsettings'] = $row['UserEditPluginsettings'];
      $usercheck['editinterfacesettings'] = $row['UserEditInterfacesettings'];
      $usercheck['editmods'] = $row['UserEditMods'];
      $usercheck['editserversettings'] = $row['UserServersettings'];

    }
    return $usercheck;
  }else{
    return "0";
  }
}

//------------------------------------------------------------------------------

function Setflags($viewtext){

$flag = array();

$flag['name'][0]  = 'reservation';
$flag['name'][1]  = 'generic';
$flag['name'][2]  = 'kick ';
$flag['name'][3]  = 'ban';
$flag['name'][4]  = 'unban';
$flag['name'][5]  = 'slay';
$flag['name'][6]  = 'map';
$flag['name'][7]  = 'cvar';
$flag['name'][8]  = 'config';
$flag['name'][9]  = 'chat';
$flag['name'][10] = 'vote';
$flag['name'][11] = 'password';
$flag['name'][12] = 'rcon';
$flag['name'][13] = 'cheats';
$flag['name'][14] = 'root';
$flag['name'][15] = 'Custom1';
$flag['name'][16] = 'Custom2';
$flag['name'][17] = 'Custom3';
$flag['name'][18] = 'Custom4';
$flag['name'][19] = 'Custom5';
$flag['name'][20] = 'Custom6';

$flag['flag'][0]  = 'a';
$flag['flag'][1]  = 'b';
$flag['flag'][2]  = 'c';
$flag['flag'][3]  = 'd';
$flag['flag'][4]  = 'e';
$flag['flag'][5]  = 'f';
$flag['flag'][6]  = 'g';
$flag['flag'][7]  = 'h';
$flag['flag'][8]  = 'i';
$flag['flag'][9]  = 'j';
$flag['flag'][10] = 'k';
$flag['flag'][11] = 'l';
$flag['flag'][12] = 'm';
$flag['flag'][13] = 'n';
$flag['flag'][14] = 'z';
$flag['flag'][15] = 'o';
$flag['flag'][16] = 'p';
$flag['flag'][17] = 'q';
$flag['flag'][18] = 'r';
$flag['flag'][19] = 's';
$flag['flag'][20] = 't';

for ($x = 0; $x <= count($flag['flag']) - 1; $x++){
$flag['purpose'][$x] = $viewtext['Flags'][$x];
}

return $flag;

}

//------------------------------------------------------------------------------

function ViewFlaginfo($tpl, $flag, $viewtext, $viewtext){

  $tpl->set_file("inhalt2", "templates/inc/flags_info.tpl.htm");

  $tpl->set_block("inhalt2", "flaginfoanzeigeblock", "flaginfoanzeigeblock_handle");


    $tpl->set_var(array(
     "menue_flag_name"              => $viewtext['System'][70],
     "menue_flag_flag"              => $viewtext['Flagsmenu'][0],
     "menue_flag_purpose"           => $viewtext['Flagsmenu'][5],
     "menue_flag_header"            => $viewtext['Flagsmenu'][6]
     ));


  for ($x = 0; $x <= (count($flag['flag'])-1); $x++){

    $tpl->set_var(array(
        "info_flag_flag"              => $flag['flag'][$x],
        "info_flag_name"              => $flag['name'][$x],
        "info_flag_purpose"           => $flag['purpose'][$x]
    ));

    $tpl->parse("flaginfoanzeigeblock_handle", "flaginfoanzeigeblock", true);

  }

  $tpl->set_var(array(
  "flaginfo" => $tpl->parse("out", "inhalt2")
  ));
  
return $tpl;

}
//------------------------------------------------------------------------------

function ParseArrows($tpl, $start_entry, $all_entrys, $show, $handle_count, $current_site, $id, $viewtext){

  //$handle_count_parse = $handle_count + 10;

  $tpl->set_file("inhaltarrows".$handle_count, "templates/inc/arrows.tpl.htm");

  $tpl->set_block("inhaltarrows".$handle_count, "arrowupenabled", "arrowupenabled_handle".$handle_count);
  $tpl->set_block("inhaltarrows".$handle_count, "arrowupdisabled", "arrowupdisabled_handle".$handle_count);
  $tpl->set_block("inhaltarrows".$handle_count, "arrowdownenabled", "arrowdownenabled_handle".$handle_count);
  $tpl->set_block("inhaltarrows".$handle_count, "arrowdowndisabled", "arrowdowndisabled_handle".$handle_count);

    $tpl->set_var(array(
    "arrow_id" => $id,
    "arrowup_pic_infotext" => $viewtext['System'][75],
    "arrowdown_pic_infotext" => $viewtext['System'][76],
    "arrows" => $tpl->parse("out", "inhaltarrows".$handle_count)
     ));

  if (($all_entrys - $start_entry) < $show){
    $end_entry = $all_entrys;
  }else{
    $end_entry = ($start_entry + $show);
  }
  if ($handle_count != 0) $start_entry = $start_entry +1;

  if ($handle_count == 1){
    $tpl->parse("arrowupdisabled_handle".$handle_count, "arrowupdisabled", true);
  }else{
    if ($start_entry == $handle_count){
      $tpl->set_var(array("arrow_page" => $current_site -1));
    }else{
      $tpl->set_var(array("arrow_page" => $current_site));
    }
    $tpl->parse("arrowupenabled_handle".$handle_count, "arrowupenabled", true);
  }

  if ($all_entrys == $handle_count){
    $tpl->parse("arrowdowndisabled_handle".$handle_count, "arrowdowndisabled", true);
  }else{
    if ($end_entry == $handle_count){
      $tpl->set_var(array("arrow_page" => $current_site +1));
    }else{
      $tpl->set_var(array("arrow_page" => $current_site));
    }
    $tpl->parse("arrowdownenabled_handle".$handle_count, "arrowdownenabled", true);
  }
  


return $tpl;

}

//------------------------------------------------------------------------------

function ViewFlagCheckbox($tpl, $flag, $smtable, $tableart, $fokus, $fokusname, $viewtext, $viewtext){

  $flags = '';

  $tpl->set_file("inhalt2", "templates/inc/flags_checkbox.tpl.htm");
  
      $tpl->set_var(array(
     "menue_flag_name"              => $viewtext['System'][70],
     "menue_flag_flag"              => $viewtext['Flagsmenu'][0],
     "menue_flag_purpose"           => $viewtext['Flagsmenu'][5]
     ));
  

    $tpl->set_block("inhalt2", "flagsblock", "flagsblock_handle");

    $sql = "SELECT * FROM ".$smtable."_".$tableart." WHERE ".$fokusname." = '$fokus'";

    $result = mysql_query($sql) OR die(mysql_error());
    if(mysql_num_rows($result))
    {        while($row = mysql_fetch_assoc($result)){

    $flags = $row['flags'];

    }
    }
      for ($x = 0; $x <= (count($flag['flag'])-1); $x++){

        $tpl->set_var(array(
             "flag_name"                   => 'flag_'.$flag['flag'][$x],
             "flag_checkbox_text"          => $flag['flag'][$x],
             "flag_checkbox_text_name"     => $flag['name'][$x],
             "flag_checkbox_text_purpose"  => $flag['purpose'][$x]
        ));

        if(strpos($flags,$flag['flag'][$x])!==false){
          $tpl->set_var(array("flag_checkbox_value_checked" => "checked"));
        }else{
          $tpl->set_var(array("flag_checkbox_value_checked" => ""));
        }

        $tpl->parse("flagsblock_handle", "flagsblock", true);
      }

    
  $tpl->set_var(array(
  "flagscheckbox" => $tpl->parse("out", "inhalt2")
  ));

return $tpl;

}

//------------------------------------------------------------------------------

function managementgettargetname($sql, $smtable, $target_tabel, $tid){

  $sql = "SELECT * FROM ".$smtable."_".$target_tabel." WHERE id = ".$tid."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {    while($row = mysql_fetch_assoc($result)){

  return $row['name'];

  }
  }

}


//------------------------------------------------------------------------------

function sqlexec($sql){

$result = mysql_query($sql) OR die("Query: <pre>".$sql."</pre>\n".
"Antwort: ".mysql_error());


return $result;

}


//------------------------------------------------------------------------------

function GetGroupinfoFromClientid($smtable, $client_id){

  $sql = "SELECT * FROM ".$smtable."_admins_groups WHERE admin_id = ".$client_id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($row = mysql_fetch_assoc($result)){

    $group_id  = $row['group_id'];
    $inherit_order  = $row['inherit_order'];
  }

  $group = array();

  $sql = "SELECT * FROM ".$smtable."_groups WHERE id = ".$group_id."";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result))
  {        while($group = mysql_fetch_assoc($result)){

  return $group['name'];

  }
  }

  }else{

  return "";

  }
  
}

//------------------------------------------------------------------------------

function Ftpconnect($ftp_ip, $ftp_port, $ftp_username, $ftp_password, $viewtext, $viewtext){

$con = array();

// Angaben überprüfen
if (($ftp_ip == "") or ($ftp_username == "") or ($ftp_password == "") or ($ftp_port == "")){
  $con['msg'] = $viewtext['Upload'][4];
  return $con;
  die;
}

// Herstellen der Basis-Verbindung
if (!$con['id'] = @ftp_connect($ftp_ip, $ftp_port, 10)) {
  $con['msg'] = $viewtext['Upload'][5];
  return $con;
  die;
}

// Einloggen mit Benutzername und Kennwort
if (!$login_result = @ftp_login($con['id'], $ftp_username, $ftp_password)){
  $con['msg'] = $viewtext['Upload'][6];
  ftp_quit($con['id']);
  return $con;
  die;
}

 $con['msg'] = $viewtext['System'][32];


 return $con;

}


//------------------------------------------------------------------------------

function Createtempfile($file_content_output, $viewtext, $viewtext){

$tmpfname = array();

  if (!$tmpfname['name'] = @tempnam("temp", "")){
    $tmpfname['msg'] = $viewtext['Upload'][7];
    return $tmpfname;
    die;
  }
  if (!$handle = @fopen($tmpfname['name'], "w")){
    $tmpfname['msg'] = $viewtext['Upload'][8];
    return $tmpfname;
    die;
  }else{
    fwrite($handle, $file_content_output);
    fclose($handle);
  }
  
  $tmpfname['msg'] = $viewtext['System'][32];
  return $tmpfname;
}

//------------------------------------------------------------------------------

function AdminsToArray($smtable){

  $admins = array();

  $sql = "SELECT * FROM ".$smtable."_admins ORDER BY id ASC";

  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
     while($row = mysql_fetch_assoc($result)){
  
            $admins[$row['id']]['name'] = $row['name'];
            $admins[$row['id']]['identity'] = $row['identity'];
            $admins[$row['id']]['authtype'] = $row['authtype'];

     }
  }
  
  return $admins;
}


//------------------------------------------------------------------------------

function SourcebansToArray($table){

//define bans-array
  $bans = array();
  //Set startvalue of counter
  $x = 0;
  // Read Sourcebans banlist
  $sql = "SELECT * FROM ".$table."_bans";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
	  // Count bans-array key
	  $x++;
	  //apply sourcebans banlistdata to array
	  $bans[$x]['name'] = str_replace("'", "", $row['name']);
      $bans[$x]['steam'] = str_replace("'", "", $row['authid']);
      $bans[$x]['ip'] = $row['ip'];
	  $bans[$x]['length'] = $row['length'];
	  $bans[$x]['date'] = $row['created'];
      $bans[$x]['reason'] = $row['reason'];
      
      // Read Sourcebans administ
	  $sql2 = "SELECT * FROM ".$table."_admins WHERE aid = ".$row['aid']."";
      $result2 = mysql_query($sql2) OR die(mysql_error());
	  // check if aid is found in adminlist 	
      if(mysql_num_rows($result2)){
        while($row2 = mysql_fetch_assoc($result2)){
          //add adminname to bans-array
		  $bans[$x]['admin'] = $row2['user'];
        }
      //if noadmin found in adminlist
	  }else{
	    //add Unknown adminname to bans-array
		$bans[$x]['admin'] = 'Unknown';
	  }  	  
    }
  }  
  return $bans;

  }
 //------------------------------------------------------------------------------


 function MysqlBansToArray($table){

 //define bans-array
  $bans = array();
  //Set startvalue of counter
  $x = 0;
  // Read Sourcebans banlist
 $sql = "SELECT * FROM ".$table."";
  $result = mysql_query($sql) OR die(mysql_error());
  if(mysql_num_rows($result)){
    while($row = mysql_fetch_assoc($result)){
	  // Count bans-array key
	  $x++;
	  //apply MySQL-Bans banlistdata to array
	  $bans['name'][$x] = $row['player_name'];
      $bans['steam'][$x] = $row['steam_id'];
      $bans['ip'][$x] = $row['ipaddr'];
      $bans['length'][$x] = $row['ban_length'] * 60;
	  $bans['date'][$x] = strtotime($row['timestamp']);
      $bans['reason'][$x] = $row['ban_reason'];
      $bans['admin'][$x] = $row['banned_by'];
	  
     }
  } 
 
 return $bans;
 }
 
 
?>
