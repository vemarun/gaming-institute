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
		<link href="css/pace.css" rel="stylesheet">
		<script src="bootstrap/js/pace.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/jquery.min.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
		<link rel="stylesheet" href="css/css.php" media="screen">
		<script src="brackets/brackets.min.js" type="text/javascript"></script>




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

				$(".brackets").brackets({

					titles: titles,

					rounds: rounds

					// MORE OPTIONS HERE

				});
				$(".brackets").brackets({

					rounds: false,

					titles: false,

					color_title: 'black',

					border_color: 'black',

					color_player: 'cyan',

					bg_player: 'white',

					color_player_hover: 'black',

					bg_player_hover: 'white',

					border_radius_player: '0px',

					border_radius_lines: '0px',

				});
			});

			var rounds;



			rounds = [





				//-- round 1

				[



					{

						player1: {
							name: "Player 111",
							winner: true,
							ID: 111
						},

						player2: {
							name: "Player 211",
							ID: 211
						}

					},



					{

						player1: {
							name: "Player 112",
							winner: true,
							ID: 112
						},

						player2: {
							name: "Player 212",
							ID: 212
						}

					},



					{

						player1: {
							name: "Player 113",
							winner: true,
							ID: 113
						},

						player2: {
							name: "Player 213",
							ID: 213
						}

					},



					{

						player1: {
							name: "Player 114",
							winner: true,
							ID: 114
						},

						player2: {
							name: "Player 214",
							ID: 214
						}

					},



					{

						player1: {
							name: "Player 115",
							winner: true,
							ID: 115
						},

						player2: {
							name: "Player 215",
							ID: 215
						}

					},



					{

						player1: {
							name: "Player 116",
							winner: true,
							ID: 116
						},

						player2: {
							name: "Player 216",
							ID: 216
						}

					},



					{

						player1: {
							name: "Player 117",
							winner: true,
							ID: 117
						},

						player2: {
							name: "Player 217",
							ID: 217
						}

					},



					{

						player1: {
							name: "Player 118",
							winner: true,
							ID: 118
						},

						player2: {
							name: "Player 218",
							ID: 218
						}

					},

				],



				//-- round 2

				[



					{

						player1: {
							name: "Player 111",
							winner: true,
							ID: 111
						},

						player2: {
							name: "Player 212",
							ID: 212
						}

					},



					{

						player1: {
							name: "Player 113",
							winner: true,
							ID: 113
						},

						player2: {
							name: "Player 214",
							ID: 214
						}

					},



					{

						player1: {
							name: "Player 115",
							winner: true,
							ID: 115
						},
						player2: {
							name: "Player 216",
							ID: 216
						}

					},



					{

						player1: {
							name: "Player 117",
							winner: true,
							ID: 117
						},

						player2: {
							name: "Player 218",
							ID: 218
						}

					},

				],



				//-- round 3

				[


					{

						player1: {
							name: "Player 111",
							winner: true,
							ID: 111
						},

						player2: {
							name: "Player 113",
							ID: 113
						}

					},



					{

						player1: {
							name: "Player 115",
							winner: true,
							ID: 115
						},

						player2: {
							name: "Player 218",
							ID: 218
						}

					},

				],



				//-- round 4

				[



					{

						player1: {
							name: "Player 113",
							winner: true,
							ID: 113
						},

						player2: {
							name: "Player 218",
							winner: true,
							ID: 218
						},

					},

				],



				//-- Champion

				[



					{

						player1: {
							name: "Player 113",
							winner: true,
							ID: 113
						},

					},

				],



			];



			var titles = ['round 1', 'round 2', 'round 3', 'round 4', 'round 5'];

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
		<div class="brackets">

		</div>
	</body>

	</html>
