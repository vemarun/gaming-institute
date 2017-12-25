<?php

require 'steamauth/steamauth.php';
include_once '../db_connect.php';
?>
	<?php
$status="";
extract($_POST);
if(isset($_POST['sub'])){
	$q="insert into gic_tour(tour_name,player_num) values('$tour_name','$player_num')";
	$res=mysqli_query($db,$q);
	$qu="create table IF NOT EXISTS $tour_name (clan_name varchar(50),clanid varchar(4) primary key,clan_owner bigint(17),member1 bigint(17) NULL,member2 bigint(17) NULL,member3 bigint(17) NULL,member4 bigint(17) NULL,member5 bigint(17) NULL,member6 bigint(17) NULL,member7 bigint(17) NULL,member8 bigint(17) NULL,member9 bigint(17) NULL,member10 bigint(17) NULL,member11 bigint(17) NULL,group_url varchar(50),time_created timestamp)";
	$result=mysqli_query($db,$qu);
$myFile ="../$tour_name.php"; // or .php   
$fh = fopen($myFile, 'w'); // or die("error");  
$stringData ="<?php
require 'steamauth/steamauth.php';
include_once 'db_connect.php';
\$tour_name='$tour_name';
?>


		<!DOCTYPE html>
		<html>

		<head>
			<meta name='viewport' content='width=device-width, initial-scale=1.0'>
			<link href='css/style_tour.css' rel='stylesheet'>
			<link href='css/font-awesome-4.7.0/css/font-awesome.min.css' rel='stylesheet'>
			<link href='bootstrap/css/bootstrap.min.css' rel='stylesheet'>
			<link href='css/pace.css' rel='stylesheet'>
			<script src='bootstrap/js/pace.min.js' type='text/javascript'></script>
			<script src='bootstrap/js/bootstrap.min.js' type='text/javascript'></script>
			<script src='bootstrap/js/jquery.min.js' type='text/javascript'></script>
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

		</head>

		<body>

			<header>
				<div id='header-inner'>
					<a href='index.php' id='logo'></a>
					<nav>
						<a href='#' id='menu-icon'></a>
						<ul>
							<li><a href='index.php'>Home</a></li>
							<li><a href='servers.php'>Servers</a></li>
							<li><a href='download.php'>Downloads</a></li>
							<li><a href='bans/'>Sourcebans</a></li>
							<li><a href='about.php'>About us</a></li><br>




							<div id='login'>
								<?php
if(!isset(\$_SESSION['steamid'])) {

     loginbutton('rectangle');//login button

}  else {

    include ('steamauth/userInfo.php'); //To access the \$steamprofile array
    //Protected content
?>
									<div class='dropdown'>
										<?php echo '<img src='.\$_SESSION['steam_avatar'].'>'; ?>
										<div class='dropdown-content'>
											<a href='profile.php' class='drop'>Dashboard</a>
											<a href='clans.php' class='drop'>Clans</a>
											<a href='#' class='drop'>
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
		extract (\$_POST);
		\$today = date('Y-m-d H:i:s');
		if(isset(\$_POST['sub'])){
			\$q=\"insert into \$tour_name(clan_name,clanid,clan_owner,member1,time_created) values('\$clan_name','\$clanid','\$clan_owner','\$member1','\$today')\";
			if(mysqli_query(\$db,\$q)){
				echo '<h3 style=background-color:cyan>Verified</h3>';
			}
			\$qu=\"insert into clans(clanid,clan_name,clan_owner,time_created) values('\$clanid','\$clan_name','\$clan_owner','\$today')\";
			if(mysqli_query(\$db,\$qu)){
			echo '<h3 style=background-color:cyan>Successfully Registered</h3>';}
			
		}
		
		?>



				<center>
					<?php
if(!isset(\$_SESSION['steamid'])) {

     echo '<h1>Login to Register Team</h1>';
	loginbutton('rectangle');//login button

}
		else { ?>
						<form method='post'>
							<input type='text' placeholder='Team Name' size='20' name='clan_name'><br>
							Team Tag (max 4 characters) : <input type='text' placeholder='Team Tag (max 4 char)' size='20' name='clanid'>
							<hr style='border-top: 2px solid #ccc'><br> Team Captain : &nbsp;<input type='text' placeholder='Member 1' size='50' Value='<?php echo \$_SESSION['steamid']; ?>' name='clan_owner'><br><br>
							<div class='alert alert-info'>
								<strong>Team Members -</strong> Fill the steamid(64) of team members</div>
							<input type='text' placeholder='Member 2' size='50' name='member1'>
							<br>
							<input type='submit' value='submit' name='sub'><br><br>
						</form>
						<?php } ?>
				</center>

		</body>

		</html>



		"; fwrite($fh, $stringData); fclose($fh); 
$status="<h4 style='background-color:cyan'>Data Successfully submitted</h4>";
} 

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
		<a href="#" style="color:#fff;">
			<?php logoutbutton(); ?>
		</a>

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
				<div id="page-wrapper">
					<div id="page-inner">
						<center>
                 <div id="status">
					<?php echo $status; ?>
				</div>
							<form method="post">
								<b>Enter tournament_name:</b><br>
								<div class="alert alert-info"><strong>Info! &nbsp;&nbsp;</strong>Don't use spaces in tournament name. This name will create a registration page and can be accessed via gaminginstitute.in/tournament_name</div>
								<input type="text" placeholder="tournament_name" name="tour_name"><br><br>
								<b>Enter number of players allowed to register per team:</b>
								<div class="alert alert-info"><strong>E.g. &nbsp;&nbsp;</strong>2 for ultiduo , 12 for highlander, 8 for 6vs6</div>
								<input type="text" placeholder="Players per team" name="player_num"><br><br>
								<input type="submit" class="btn btn-info" name="sub"> </form>
						</center>

					</div>
				</div>
			</div>



		</body>

		</html>
