<!DOCTYPE html>

<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/style_servers.css" rel="stylesheet">
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
					<li><a href="about.php">About us</a></li>
				</ul>
			</nav>
		</div>
	</header>
	<div id="addserver">
		<h3>Execute rcon command</h3>
		<form method="post" id="serverform" action="rcon2.php">
			<input type="text" placeholder="ip" name="ip">
			<input type="text" placeholder="port" name="port"><br>
			<input type="text" placeholder="rcon password" name="rpass">
			<input type="text" placeholder="command" name="command"><br><br>
			<input type="submit" name="sub" value="Execute Rcon">
		</form>
	</div>
	<?php
   
   
	require __DIR__ . '/PHP-Source-Query-master/SourceQuery/bootstrap.php';

	use xPaw\SourceQuery\SourceQuery;
	
	// For the sake of this example
	//Header( 'Content-Type: text/plain' );
	//Header( 'X-Content-Type-Options: nosniff' );
	
	// Edit this ->
	define( 'SQ_SERVER_ADDR', 'localhost' );
	define( 'SQ_SERVER_PORT', 27015 );
	define( 'SQ_TIMEOUT',     1 );
	define( 'SQ_ENGINE',      SourceQuery::SOURCE );
	// Edit this <-
	
	$Query = new SourceQuery( );
    extract($_POST);
	if(isset($_POST['sub'])){
	try
	{
        $Query->Connect( $ip, $port);
		//$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
		
		$Query->SetRconPassword( $rpass ); ?>
		<center><br><br>
			<div style="background-color:black; height:300px; width:600px;">

				<?php var_dump( $Query->Rcon( $command ) );
	}
	catch( Exception $e )
	{
		echo $e->getMessage( );
	}
	finally
	{
		$Query->Disconnect( );
	}
   }
?>
			</div>
		</center>
