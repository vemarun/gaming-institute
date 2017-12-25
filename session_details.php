<?php
include_once 'db_connect.php';
$query="select clanid from users where steamid=$_SESSION[steamid]";
$result=mysqli_query($db,$query);
$row=mysqli_fetch_array($result);
$_SESSION['clanid']=$row[0];
$query2="select clan_name from clans where clanid=$row[0]";
$res=mysqli_query($db,$query2);
$row2=mysqli_fetch_array($res);
$_SESSION['clan_name']=$row2[0];
?>
