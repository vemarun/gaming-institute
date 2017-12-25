<?php
require 'steamauth/steamauth.php';
require 'db_connect.php';

$q="select group_id from users where steamid=$_SESSION[steamid]";
$res=mysqli_query($db,$q);
$row=mysqli_fetch_array($res);
if($row[0]==1) //member
{
	header("location:member.php");
}
if($row[0]==2) //moderator
{
	header("location:mod.php");
}
if($row[0]==3) //admin
{
	header("location:admin/");
}
?>
