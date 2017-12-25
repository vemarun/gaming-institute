<?php

require 'steamauth/steamauth.php';
include_once 'db_connect.php';
include_once 'session_details.php';
?>


	<!DOCTYPE html>
	<html>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/sch_match.css" rel="stylesheet">
		<link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/pace.css" rel="stylesheet">
		<script src="bootstrap/js/pace.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/jquery.min.js" type="text/javascript"></script>
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
			<div id="header-inner">
				<a href="index.php" id="logo"></a>
				<nav>
					<a href="#" id="menu-icon"></a>
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="servers.php">Servers</a></li>
						<li><a href="download.php">Downloads</a></li>
						<li><a href="bans/">Sourcebans</a></li>
						<li><a href="about.php">About us</a></li><br>




						<div id="login">
							<?php
if(!isset($_SESSION['steamid'])) {

     header("location:index.php");//login button

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
		<div id="title_quote">
			<h1>[ Be So Good They Can't Ignore You ]</h1>
			<h3>GIC</h3>
			<h4>Presents</h4>
		</div>
		<div id="title_div">
			<img src="images/fight.png" id="title_image">
		</div>
		<div id="title_form">
			<form method="post">
				<input type="text" placeholder="<?php  echo $_SESSION['clanid']; echo " -- ";echo   $_SESSION['clan_name']; ?>" disabled><br>
				<h3>vs</h3>
				<select name="clanid">
		<?php
		include 'db_connect.php';
		$qu="select clan_name from clans";
		$result=mysqli_query($db,$qu);
		
		while($row=mysqli_fetch_array($result)){
		?>
			
		<option><?php echo $row[0];?></option>	
		
			<?php } ?>
		</select>
				<br><br>

				<h4>Choose Date: (dd/mm/yyyy)</h4><br>
				<input type="date"><br>
				<br> Select time: <br>
				<select name="time">
                <option value="1">1</option>
                <option value="1">2</option>
                <option value="1">3</option>
                <option value="1">4</option>
                <option value="1">5</option>
                <option value="1">6</option>
                <option value="1">7</option>
                <option value="1">8</option>
                <option value="1">9</option>
                <option value="1">10</option>
                <option value="1">11</option>
                <option value="1">12</option>
                </select>
				<select name="ampm">
                 <option value="1">am</option>
                <option value="1">pm</option>
                </select><br><br>
				<input type="submit" value="Submit" name="sub">
				<br><br>
			</form>



			</form>
		</div>
	</body>

	</html>
