<?php

require 'steamauth/steamauth.php';
include_once '../db_connect.php';
?>

	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>GIC Administrative zone</title>



		<!-- BOOTSTRAP STYLES-->
		<link href="assets/css/bootstrap.css" rel="stylesheet" />
		<!-- FONTAWESOME STYLES-->
		<link href="assets/css/font-awesome.css" rel="stylesheet" />
		<!-- CUSTOM STYLES-->
		<link href="assets/css/custom.css" rel="stylesheet" />
		<!-- GOOGLE FONTS-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
		<link href="../css/pace.css" rel="stylesheet">
		<script src="../bootstrap/js/pace.min.js" type="text/javascript"></script>
		<!-- /. WRAPPER  -->
		<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->

		<!-- BOOTSTRAP SCRIPTS -->
		<script src="assets/js/bootstrap.min.js"></script>
		<!-- CUSTOM SCRIPTS -->
		<script src="assets/js/custom.js"></script>
		<!-- JQUERY SCRIPTS -->
		<script src="assets/js/jquery-1.10.2.js"></script>


		<!--<script>
			$(document).ready(function() {

				$("#btn2").click(function() {
					$("#page-inner").html("<img src='assets/img/loader.gif'>").show();
					$.ajax({
						url: "admin_match.php",
						success: function(result) {
							$("#page-inner").html(result);
						}
					});
				});
				$('form').on('submit', function(e) {

					e.preventDefault();
				});
			});

		</script> -->
	</head>

	<body>



		<div id="wrapper">
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="adjust-nav">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
						<a class="navbar-brand" href="../index.php">
							<img src="assets/img/logo.png" id="logo" />

						</a>

					</div>


					<span class="logout-spn">
                 <?php
						$q="select group_id from users where steamid=$_SESSION[steamid]";
						$res=mysqli_query($db,$q);
						$row=mysqli_fetch_array($res);
if(!isset($_SESSION['steamid']) || $row[0]<>3) {

     header("location:../index.php");//login button

}  else {

    include ('steamauth/userInfo.php'); //To access the $steamprofile array

    //Protected content
?>
                  <a href="#" style="color:#fff;"><?php logoutbutton(); ?></a>  

                </span>
					<?php }?>
				</div>
			</div>
			<!-- /. NAV TOP  -->
			<nav class="navbar-default navbar-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav" id="main-menu">



						<li class="active-link">
							<a href="index.php"><i class="fa fa-desktop "></i>Dashboard <span class="badge">#</span></a>
						</li>


						<li>
							<a href="admin_match.php"><i class="fa fa-calendar "></i>Match Schedules  <span class="badge"></span></a>
						</li>
						<li>
							<a href="admin_users.php"><i class="fa fa-users "></i>See Users  <span class="badge"></span></a>
						</li>


						<li>
							<a href="admin_clans.php"><i class="fa fa-qrcode "></i>Clans</a>
						</li>
						<li>
							<a href="../member.php"><i class="fa fa-bar-chart-o"></i>Your profile</a>
						</li>

						<li>
							<a href="../rcon2.php"><i class="fa fa-laptop "></i>Server Control </a>
						</li>
						<li>
							<a href="admin_adduser.php"><i class="fa fa-table "></i>Register user Manually</a>
						</li>
						<li>
							<a href="settings.php"><i class="fa fa-gear "></i>Settings </a>
						</li>
						<li>
							<a href="admin_tour.php"><i class="fa fa-qrcode "></i>Tournament Settings </a>
						</li>

					</ul>
				</div>

			</nav>
			<!-- /. NAV SIDE  -->
			<div id="page-wrapper">
				<div id="page-inner">
					<div class="row row2">
						<div class="col-lg-12">
							<h2>ADMIN DASHBOARD</h2>
							<?php echo "<img src='".$_SESSION['steam_avatarmedium']."' id='profileimage'>"; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						</div>
					</div>
					<!-- /. ROW  -->
					<div class="row row2">
						<div class="col-lg-12 ">
							<div>
								<strong>Welcome, <?php echo $_SESSION['steam_personaname']; ?> .</strong> From here you can control nearly everything.
							</div>

						</div>
					</div>
					<div id="loader"></div>
					<!-- /. ROW  -->
					<div class="row text-center pad-top">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="../member.php">
									<i class="fa fa-pencil-square-o fa-5x"></i>
									<h4>Edit Your Clan</h4>
								</a>
							</div>


						</div>

						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="admin_match.php">
									<i class="fa fa-calendar fa-5x"></i>
									<h4>Match Schedules</h4>

								</a>
							</div>


						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="admin_aboutme_page.php">
									<i class="fa fa-plus fa-5x"></i>
									<h4>Website About Page</h4>
								</a>
							</div>


						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="admin_users.php">
									<i class="fa fa-users fa-5x"></i>
									<h4>See Users</h4>
								</a>
							</div>


						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="admin_settings.php">
									<i class="fa fa-key fa-5x"></i>
									<h4>Admin </h4>
								</a>
							</div>


						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="admin_tour">
									<i class="fa fa-clock-o fa-5x"></i>
									<h4>Hold a Tournament</h4>
								</a>
							</div>


						</div>
					</div>
					<!-- /. ROW  -->
					<div class="row text-center pad-top">

						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="admin_clans.php">
									<i class="fa fa-wrench fa-5x"></i>
									<h4>View/Edit Clans</h4>
								</a>
							</div>


						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="settings.php">
									<i class="fa fa-gear fa-5x"></i>
									<h4>Website Settings</h4>
								</a>
							</div>


						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="../rcon2.php">
									<i class="fa fa-laptop fa-5x"></i>
									<h4>Rcon Server</h4>
								</a>
							</div>


						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="server_info.php">
									<i class="fa fa-info-circle fa-5x"></i>
									<h4>Server Information </h4>
								</a>
							</div>


						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="admin_server.php">
									<i class="fa fa-eraser fa-5x"></i>
									<h4>Add/ Remove Server</h4>
								</a>
							</div>


						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
							<div class="div-square">
								<a href="admin_adduser.php">
									<i class="fa fa-user fa-5x"></i>
									<h4>Register User</h4>
								</a>
							</div>


						</div>
					</div>
					<!-- /. ROW  -->
					<div class="row text-center pad-top">




						<!-- /. ROW  -->
						<div class="row">
							<div class="col-lg-12 ">
								<br/>
								<div class="alert alert-danger">
									<strong>Facing any problem ?</strong> Contact Support : <a target="_blank" href="http://steamcommunity.com/id/dynamiteofficial/">Click Here</a>.
								</div>

							</div>
						</div>
						<!-- /. ROW  -->
					</div>
					<!-- /. PAGE INNER  -->
				</div>
				<!-- /. PAGE WRAPPER  -->
			</div>


			<div class="footer">


				<div class="row">
					<div class="col-lg-12">
						&copy; 2017 GIC | All Rights Reserved
					</div>
				</div>
			</div>
		</div>





	</body>


	</html>
