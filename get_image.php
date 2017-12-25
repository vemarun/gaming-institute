<?php
require 'steamauth/steamauth.php';
require 'db_connect.php';

  $id = $_GET['id'];
  // do some validation here to ensure id is safe

  $sql = "SELECT clan_img FROM clans where clanid=$id";
  $result = mysqli_query($db,$sql);
  $row = mysqli_fetch_assoc($result);
  mysqli_close($db);
  header("Content-type: image/jpeg");
  echo $row['clan_img'];
?>
