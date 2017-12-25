<?php
$host='35.200.158.221';
$command='./tf2server restart';
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include_once('phpseclib/Net/SSH2.php');
include_once('phpseclib/Crypt/RSA.php');

$ssh = new Net_SSH2($host);
$key = new Crypt_RSA();
$key->loadKey(file_get_contents('.ppk'));
if (!$ssh->login('user', $key)) {
    exit('Login Failed');
}
echo "<center><div style='background-color:black; height:700px; width:700px; color:white'>";
echo $ssh->exec($command);
echo "</div></center>";
?>
