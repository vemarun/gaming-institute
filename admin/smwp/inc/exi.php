<?php
//include ("language_de.php");
//include ("language_en.php");
include ("language_fr.php");
//include ("language_ru.php");

$linereturn='
';

$output = 'Language'.$linereturn.'[['.$linereturn;

$output = Trans('System', $viewtext, $linereturn, $output);
$output = Trans('Date', $viewtext, $linereturn, $output);
$output = Trans('Menue', $viewtext, $linereturn, $output);
$output = Trans('Login', $viewtext, $linereturn, $output);
$output = Trans('Flagsmenu', $viewtext, $linereturn, $output);
$output = Trans('Flags', $viewtext, $linereturn, $output);
$output = Trans('Groups', $viewtext, $linereturn, $output);
$output = Trans('Clients', $viewtext, $linereturn, $output);
$output = Trans('User', $viewtext, $linereturn, $output);
$output = Trans('Overrides', $viewtext, $linereturn, $output);
$output = Trans('Servergroups', $viewtext, $linereturn, $output);
$output = Trans('Export', $viewtext, $linereturn, $output);
$output = Trans('Upload', $viewtext, $linereturn, $output);
$output = Trans('Settings', $viewtext, $linereturn, $output);
$output = Trans('Plugins', $viewtext, $linereturn, $output);
$output = Trans('Setup', $viewtext, $linereturn, $output);
$output = Trans('Server', $viewtext, $linereturn, $output);

$output = Trans('Mods', $viewtext, $linereturn, $output);
// Plugins ---->
$output = Trans('Mysqlbans', $viewtext, $linereturn, $output);
$output = Trans('Maprate', $viewtext, $linereturn, $output);
$output = Trans('Serverconfigs', $viewtextconfigs, $linereturn, $output);
$output = Trans('Chatlogex', $viewtext, $linereturn, $output);
$output = Trans('Advertisements', $viewtext, $linereturn, $output);
$output = Trans('Basicplayertracker', $viewtext, $linereturn, $output);
$output = Trans('Sqlfeedback', $viewtext, $linereturn, $output);

$output = $output.']]';

//-------------------------------------------------
$output = str_replace('[[', '{', $output);
$output = str_replace(']]', '}', $output);
$output = str_replace('</font>', '[/color]', $output);
$output = str_replace('<b>', '[b]', $output);
$output = str_replace('</b>', '[/b]', $output);
$output = str_replace('<u>', '[u]', $output);
$output = str_replace('</u>', '[/u]', $output);
$output = str_replace('<i>', '[i]', $output);
$output = str_replace('</i>', '[/i]', $output);

$output = str_replace("&prime;", "'", $output);

// --=Plugins=------------------------------------------------------------------

function Trans($newname, $array, $linereturn, $output){

$output = $output.'  '.$newname.$linereturn;
$output = $output.'  [['.$linereturn;

foreach($array as $key => $value) {

 
 
 $value = str_replace('<font color="FF5D29">', '[color=FF5D29]', $value);
 

 
 
 $value = str_replace('<font color="red">', '[color=red]', $value);
 $value = str_replace('<font color="green">', '[color=green]', $value);
 $value = str_replace('"', '&quot;', $value);

  if ($value == "") $value = ' ';
  if ($key < 10){
    $output= $output.'    "'.$key.'"  "'.$value.'"'.$linereturn;
  }else{
    $output= $output.'    "'.$key.'" "'.$value.'"'.$linereturn;
  }
}

$output = $output.'  ]]'.$linereturn.$linereturn;

return $output;

}

//-------------------------------------------------

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="out.txt"');

print $output;

?>
