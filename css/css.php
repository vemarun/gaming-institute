<?php header("Content-type: text/css");
include_once '../db_connect.php'; 
$q="select bg from web_setting where bg_active=1"; 
$r=mysqli_query($db,$q);
$row=mysqli_fetch_array($r);
$qu="select bg from web_setting where bg2_active2=1"; 
$res=mysqli_query($db,$qu);
$row2=mysqli_fetch_array($res);
?>.banner { 
width: 100%; 
background-color: #6991AC;
background-image: url(../images/<?php echo $row[0]; ?>); 
background-size: cover; 
background-blend-mode: multiply; 
height: 618px; 
background-attachment: fixed; 
}#up_matches { 
color: white; 
background: url(../images/<?php echo $row2[0]; ?>); 
background-attachment: fixed; 
background-size: cover; 
background-color: #6ca2c6; 
padding-top: 200px; 
}
