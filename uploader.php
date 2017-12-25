<?php

require 'steamauth/steamauth.php';
include_once 'db_connect.php';
?>
	<!DOCTYPE html>
	<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/style.css" rel="stylesheet">
		<link rel="icon" href="images/favicon.ico">
		<link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/jquery.min.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
		<link href="spop/spop.min.css" rel="stylesheet" type="text/css">
		<script src="spop/spop.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="css/css.php" media="screen">



		<title>Home - GIC </title>

		<script>
			$(document).ready(function() {


				var num = 80;
				$(window).scroll(function() {
					//if you hard code, then use console
					//.log to determine when you want the 
					//nav bar to stick.  
					console.log($(window).scrollTop())
					if ($(window).scrollTop() > num) {
						$('#header-inner').addClass('navbar-fixed');
					}
					if ($(window).scrollTop() < num) {
						$('#header-inner').removeClass('navbar-fixed');
					}
				});

			});

		</script>
		<style>
			.fileUpload {
				position: relative;
				overflow: hidden;
				margin: 10px;
			}
			
			.fileUpload input.upload {
				position: absolute;
				top: 0;
				right: 0;
				margin: 0;
				padding: 0;
				font-size: 20px;
				cursor: pointer;
				opacity: 0;
				filter: alpha(opacity=0);
			}

		</style>
	</head>

	<body>
		<header>
			<div id="header-inner">
				<a href="index.php" id="logo"></a>
				<nav>
					<a href="#" id="menu-icon"></a>
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="servers.php">Servers</a></li>
						<li><a href="download.php">Downloads</a></li>
						<li><a href="http://gaminginstitute.in/bans/">Sourcebans</a></li>
						<li><a href="about.php">About us</a></li><br>





						<div id="login">
							<?php
if(!isset($_SESSION['steamid'])) {

    loginbutton("rectangle"); //login button

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array
    //Protected content
?>
								<div class="dropdown">
									<?php echo "<img src='".$_SESSION['steam_avatar']."'>"; ?>
									<div class="dropdown-content">
										<a href="profile.php" class="drop">Dashboard</a>
										<a href="clans.php" class="drop">Clans</a>
										<a href="#" class="drop">
											<?php logoutbutton(); } ?>
										</a>


									</div>
								</div>
						</div>
					</ul>
				</nav>
			</div>
		</header>
		<?php
if(!isset($_SESSION['steamid'])) {
	echo "<center><h3>Login to upload</h3>";

    loginbutton("rectangle"); //login button
	echo "</center>";

}  else {
			$q="select group_id from users where steamid=$_SESSION[steamid]";
						$res=mysqli_query($db,$q);
						$row=mysqli_fetch_array($res);
			if(!isset($_SESSION['steamid']) || $row[0]<>3) {

     echo "<center><h3>Unauthorized ! Not Allowed</h3><h3>Contact any admin.</h3>";//login button

}  else { ?>

			<div id="uploader">
				<center>


					<form action="" method="post" enctype="multipart/form-data">
						<br><br>
						<input id="uploadFile" placeholder="Selected File" disabled="disabled" />
						<div class="fileUpload btn btn-primary">
							<span>Select File</span>
							<input id="uploadBtn" type="file" name="file" class="upload">
						</div><br><br>
						<input type="submit" value="Upload File" name="submit"><br><br><br><br>
					</form>

				</center>
			</div>


			<?php 
            ini_set('upload_max_filesize', '200M');
ini_set('post_max_size', '180M');
    extract($_POST);
if(isset($_FILES['file'])){
      $errors= array();
      $file_name = $_FILES['file']['name'];
      $file_size = $_FILES['file']['size'];
      $file_tmp = $_FILES['file']['tmp_name'];
      $file_type = $_FILES['file']['type'];
      $value=explode('.',$_FILES['file']['name']);
      $file_ext=strtolower(end($value));
      
      $expensions= array("bsp");
    $name=pathinfo($file_name,PATHINFO_FILENAME);
      
      if(in_array($file_ext,$expensions)=== false){
             echo "<script>
     spop({
	template: 'Error! Only .bsp files < 200Mb allowed',
	group: 'submit-satus',
    position  : 'top-right',
	style: 'error',
	autoclose: 2000
        });</script>";
           $errors[]="extension not allowed, please choose a .bsp file.";
      }
      
      if($file_size > 1024*200*1024) {
             echo "<script>
     spop({
	template: 'Error! Only .bsp < 200Mb allowed',
	group: 'submit-satus',
    position  : 'top-right',
	style: 'error',
	autoclose: 2000
        });</script>";
       $errors[]='File size must be less than 200 MB';
      }
      
      if(empty($errors)==true) {
          
    move_uploaded_file($file_tmp,"/var/www/cdn.gaminginstitute.in/html/lava/tf/maps/".$file_name);      
          echo "<script>
     spop({
	template: 'Success! File uploaded',
	group: 'submit-satus',
    position  : 'top-right',
	style: 'success',
	autoclose: 2000
        });</script>";
          
   }
} }
}?>
			<script>
				document.getElementById("uploadBtn").onchange = function() {
					document.getElementById("uploadFile").value = this.value;
				};

			</script>
