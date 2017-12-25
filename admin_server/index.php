<?php
require '../steamauth/steamauth.php';
include_once '../db_connect.php';
$stat= " ";

?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta name="../viewport" content="width=device-width, initial-scale=1.0">
		<link href="style_admin_server.css" rel="stylesheet">
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

				$("#rs").click(function() {
					$('#refresh').addClass('fa-spin');
					$('#stat2').html('<center>Restarting Server.... Wait for few seconds</center>');
					var datas = $("[name='servername']:checked").val();
					$.post('ssh_connect.php', {
						servername: datas
					}).always(function() {
						$('#refresh').removeClass('fa-spin');
						$('#stat2').html('<center><strong>Success || Server restarted</strong></center>');
					});


				});

				$("#up").click(function() {
					$('#update').addClass('fa-spin');
					$('#stat2').html('<center>Checking and updating Server.... Wait for few minutes</center>');
					var datas = $("[name='servername']:checked").val();
					$.post('ssh_update.php', {
						servername: datas
					}).always(function() {
						$('#update').removeClass('fa-spin');
						$('#stat2').html('<center><strong>Success || Server Updated</strong></center>');
					});
				});

				function showValues() {
					var str = $("form#servers").serialize();
					$("#stat2").text(str);
				}
				$("input[type='checkbox'], input[type='radio']").on("click", showValues);
				$("select").on("change", showValues);
				showValues();

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
				font-size: 20px;
				color: black;
				transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
			}

		</style>
	</head>

	<body>
		<header>
			<div id="header-inner">
				<a href="../index.php" id="logo"></a>
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
	
loginbutton('rectangle');
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


		<div id="content"><br><br><br><br>
			<div id="stat2" style="background-color:lightblue;color:green;font-size:150%"></div><br><br>
			<center>
				<?php if(!isset($_SESSION['steamid'])) { 
	echo "<h2>Authentication Required</h2>";
	loginbutton('rectangle');
} 
			else { 
				$q="select "
				?>
				<form id="servers" method="post">
					<input type="radio" value="payload" name="servername"> PAYLOAD NEW 24/7 | GIC | INDIA<br><br>
					<input type="radio" value="pug" name="servername"> PuG server | GIC | India<br><br>
					<input type="radio" value="jump" name="servername"> Jump Mania | GIC | India<br><br>
					<input type="radio" value="sniper" name="servername"> Sniper War | GIC | India<br><br>
					<input type="radio" value="mge" name="servername"> MGE | GIC | India<br><br><br>

				</form>
				<div class="btn-group">
					<a class="btn btn-default" id="up" href="#">
			<i class="fa fa-clock-o fa-2x" id="update" aria-hidden="true" alt="Update server"></i>  Update Server </a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a class="btn btn-default" id="rs" href="#">
					<i class="fa fa-refresh fa-2x" id="refresh" aria-hidden="true" alt="restart server"></i> Restart Server</a><br><br><br>
					<a class="btn btn-default" id="rcon" href="http://gaminginstitute.in/rcon2.php" target="_blank">
					<i class="fa fa-terminal fa-2x" aria-hidden="true" alt="rcon"></i> Rcon Server</a><br><br><br>

				</div>

			</center>
			<br>



		</div>
		<center>
			<a class="btn btn-default" href="index.php">
				<i class="fa fa-home fa-2x" aria-hidden="true" alt="File Manager"></i>Home</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a class="btn btn-default" href="help.php" target="_blank">
				<i class="fa fa-question-circle fa-2x" aria-hidden="true" alt="help"></i>  Help</a>&nbsp;&nbsp;&nbsp;&nbsp;
		</center>

		<?php }?>

		<div id="overlay">
			<div id="text">

				<br><br>
				<div id="stat" style='backgrond:black;color:red;margin:20%'>
				</div><br><br>
				<form method="post">
					<input type="password" name="pass" placeholder="        Enter passcode"><br><br>
					<input type="submit" class="btn btn-default" name="sub" value="Enter" style="margin-left:35%"></form>
			</div>
		</div>
		<?php
		
		if(isset($_POST['sub'])){
		
		if($_POST['pass']=='9632'){
			echo "<script>off();</script>";
		}
		else{
			echo "<script>document.getElementById('stat').innerHTML='Wrong Passcode'</script>";
		}
		}
			
			?>
	</body>

	</html>
