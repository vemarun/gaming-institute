<?php
include_once '../db_connect.php';
if($_POST['servername']){	
$servername=$_POST["servername"];

if($servername=='payload' || $servername=='sniper'){
$q="select serveraddress,username,pass from admin_server where servername='".$servername."'";
$r=mysqli_query($db,$q);
$row=mysqli_fetch_array($r);
$host=$row[0];
$username=$row[1];
$password=$row[2];
$command='./tf2server force-update';

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include_once('phpseclib/Net/SSH2.php');

$ssh = new Net_SSH2($host);

if (!$ssh->login($username, $password)) {
    exit('Login Failed');
}

$ssh->exec($command);
}
	
else if($servername=='mge'){
$q="select serveraddress,username,pass from admin_server where servername='".$servername."'";
$r=mysqli_query($db,$q);
$row=mysqli_fetch_array($r);
$host=$row[0];
$username=$row[1];
$password=$row[2];
$command='./tf2server-2 force-update';

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include_once('phpseclib/Net/SSH2.php');

$ssh = new Net_SSH2($host);

if (!$ssh->login($username, $password)) {
    exit('Login Failed');
}
	$ssh->exec($command);
}
else if($servername=='jump'){
$q="select serveraddress,username from admin_server where servername='".$servername."'";
$r=mysqli_query($db,$q);
$row=mysqli_fetch_array($r);
$host=$row[0];
$username=$row[1];
$command='./tf2server force-update';

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include_once('phpseclib/Net/SSH2.php');
include_once('phpseclib/Crypt/RSA.php');

$ssh = new Net_SSH2($host);
$key = new Crypt_RSA();
$key->loadKey(file_get_contents('gic_pug_sg.ppk'));
if (!$ssh->login($username, $key)) {
    exit('Login Failed');
}
$ssh->exec($command);
}	
	
	else if($servername=='pug'){
$q="select serveraddress,username from admin_server where servername='".$servername."'";
$r=mysqli_query($db,$q);
$row=mysqli_fetch_array($r);
$host=$row[0];
$username=$row[1];

$command='./tf2server force-update';

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include_once('phpseclib/Net/SSH2.php');
include_once('phpseclib/Crypt/RSA.php');

$ssh = new Net_SSH2($host);
$key = new Crypt_RSA();
$key->loadKey(file_get_contents('tf2server.ppk'));
if (!$ssh->login($username, $key)) {
    exit('Login Failed');
}
$ssh->exec($command);
}
}
?>
