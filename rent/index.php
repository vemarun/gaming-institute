<?php
require '../steamauth/steamauth.php';
include_once '../db_connect.php';

?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta name="../viewport" content="width=device-width, initial-scale=1.0">
		<link href="../css/style_member.php" rel="stylesheet">
		<link href="../css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/pace.css" rel="stylesheet">
		<script src="../bootstrap/js/pace.min.js" type="text/javascript"></script>
		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="../bootstrap/js/jquery.min.js" type="text/javascript"></script>
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

				/*$("#fm").click(function() {
					$.ajax({
						url: "fm.php",
						success: function(result) {
							$("#content").html(result);
						}
					});
				}); */
				$("#rcon").click(function() {
					$.ajax({
						url: "rc.php",
						success: function(result) {
							$("#content").html(result);
						}
					});
				});


				$("#rs").click(function() {
					$('#refresh').addClass('fa-spin');
					$.ajax({
						url: "ssh_connect.php",
						data: {
							delay: 5
						}
					}).always(function() {
						$('#refresh').removeClass('fa-spin');
					});
				});

			});

			function on() {
				document.getElementById("overlay").style.display = "block";
			}

			function off() {
				document.getElementById("overlay").style.display = "none";
			}

		</script>
		<style>
			a {
				text-decoration: none;
				color: white;
			}

			#overlay {
				position: fixed;
				/* Sit on top of the page content */
				/* Hidden by default */
				width: 100%;
				/* Full width (cover the whole page) */
				height: 100%;
				/* Full height (cover the whole page) */
				top: 0;
				left: 0;
				right: 0;
				/*display: none; */
				bottom: 0;
				background-color: rgba(0, 0, 0, 0.9);
				/* Black background with opacity */
				z-index: 2;
				/* Specify a stack order in case you're using a different order for other elements */
				/* Add a pointer on hover */
			}

			#text {
				position: absolute;
				top: 50%;
				left: 50%;
				font-size: 50px;
				color: white;
				transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
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
						<li><a href="../index.php">Home</a></li>
						<li><a href="../servers">Servers</a></li>
						<li><a href="../download">Downloads</a></li>
						<li><a href="../bans/">Sourcebans</a></li>
						<li><a href="../about">About us</a></li><br>




						<div id="login">
							<?php
if(!isset($_SESSION['steamid'])) {

     header("location:../index.php");//login button

}  else {

    include ('../steamauth/userInfo.php'); //To access the $steamprofile array
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


		<div id="content">
			<?php
		$q="select donator from users where steamid=$_SESSION[steamid]";
		$r=mysqli_query($db,$q);
		$row=mysqli_fetch_array($r);
		if($row[0]=='0'){
			echo "<center><h2 class='alert alert-danger'><strong>Unauthorized! </strong> Only donators are allowed to book server</h2></center><br><br><br><a href='../donate.php'><button class='btn btn-info'>Donate</button></a>";
		}
		else{
		?>
				<h1>
					<?php echo $steamprofile['personaname']; ?>
				</h1>

				<div id="profile_pic"></div>

				<div id="head"></div><br><br>
				<div id="status" style="background-color:lightblue"></div>
				<a class="btn btn-default" id="fm" href="//35.200.158.221:8000" target="_blank">
			<i class="fa fa-cloud-upload fa-2x" aria-hidden="true" alt="File Manager"></i> File Manager</a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-default" id="rs" href="#">
					<i class="fa fa-refresh fa-2x" id="refresh" aria-hidden="true" alt="restart server"></i> Restart Server</a><br><br><br>
				<a class="btn btn-default" id="rcon" href="#">
					<i class="fa fa-terminal fa-2x" aria-hidden="true" alt="rcon"></i> Rcon Server</a><br><br><br>
				<a class="btn btn-default" id="rcon" href="#" disabled>
					<i class="fa fa-user fa-2x" aria-hidden="true" alt="admin-panel"></i> In-game Admin Panel </a><br><br><br>


				<br>
				<center><a href="steam://connect/35.200.158.221:27015">Join server : 35.200.158.221:27015</a></center>

				<?php }?>
		</div>
		<center>
			<a class="btn btn-default" href="index.php">
				<i class="fa fa-home fa-2x" aria-hidden="true" alt="File Manager"></i>Home</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a class="btn btn-default" href="help.php" target="_blank">
				<i class="fa fa-question-circle fa-2x" aria-hidden="true" alt="help"></i>  Help</a>&nbsp;&nbsp;&nbsp;&nbsp;
		</center>
		<div id="overlay">
			<div id="text">
				<?php
		$q="select donator from users where steamid=$_SESSION[steamid]";
		$r=mysqli_query($db,$q);
		$row=mysqli_fetch_array($r);
		if($row[0]=='0'){
			echo "<center><h2 class='alert alert-danger'><strong>Unauthorized! </strong> Only donators are allowed to book server</h2><br><br><br><a href='../donate.php'><button class='btn btn-info'>Donate</button></a></center>";
		}
		else{
			date_default_timezone_set('Asia/Kolkata');
			$current_date = date('d/m/Y');
			echo "<h2>Date: ".$current_date;
		
			?>
					<?php 
			$current_date=date('y-m-d');
			$qu="select sum(active) from rent where date='".$current_date."'";
			$r=mysqli_query($db, $qu);
			$row=mysqli_fetch_array($r);
                        
			if($row[0]=='1'){
				echo "<center><h2 class='alert alert-danger'> All servers are currently reserved. Try after some time.</h2></center>";
			}
			else { ?>
					<br><br>
					<form method="post">
						<input type="submit" class="btn btn-default" name="book" value="Book Now"></form>
			</div>
			<?php }?>
			<?php } ?>
		</div>
		<?php
		$current_date=date('y-m-d');
		if(isset($_POST['book'])){
			
			$q="insert into rent(serverno,steamid,date,active) values('1','$_SESSION[steamid]','$current_date','1')";
			$r=mysqli_query($db,$q);
		}
		$q="select * from rent where steamid=$_SESSION[steamid] AND date='".$current_date."'";
		$r=mysqli_query($db,$q);
		$row=mysqli_fetch_array($r);
		if($row['active']=='1'){
			echo "<script>off();</script>";
		}
		else{
			echo "<script>on();</script>";
		}
			
		?>
	</body>

	</html>
