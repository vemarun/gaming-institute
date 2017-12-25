<html>

<head>
	<title>Redirecting..</title>
</head>
<style type="text/css">
	#loader {
		border: 16px solid #f3f3f3;
		/* Light grey */
		border-top: 16px solid #3498db;
		/* Blue */
		border-radius: 50%;
		width: 120px;
		height: 120px;
		animation: spin 2s linear infinite;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}

	#content {
		margin: auto;
		padding: 2px;
	}

</style>

<body onload="loader()" ;>


	<center>
		<div id="loader"></div>
	</center>
	<div id="content">
		<?php   
require 'db_connect.php';
require 'steamauth/steamauth.php';

$qu= "select * from users where steamid= $_SESSION[steamid]";

$result=mysqli_query($db,$qu) or die(mysqli_error($db));
$rows=mysqli_num_rows($result);
if($rows==1)
{
    header("Location:index.php");
}
    else
    {
    $ins=" insert into users (steamid) values ($_SESSION[steamid])";
    $res=mysqli_query($db, $ins) or die(myqli_error($db));
	$q="update users set group_id=1 where steamid=$_SESSION[steamid]";
		$result=mysqli_query($db,$q) or die(mysqli_error($db));
    header("Location:http://gaminginstitute.in");
    } 
    
?>

		<script>
			var myvar;

			function loader() {
				myvar = setTimeout(showPage, 3000);
			}

			function showPage() {
				document.getElementById("loader").style.display = "none";
				document.getElementById("content").style.display = "block";

			}

		</script>




	</div>
</body>

</html>
