<?php 

include_once '../db_connect.php';
extract($_POST);
$status="";
if(isset($_POST['sub'])){
	$query="update matches 
	set team1='".$team1."',team2='".$team2."',date='".$date."',time='".$time."',winner='".$winner."',logs='".$logs."',complete='".$complete."' where matchid='".$matchid."'";
	
	if($result=mysqli_query($db,$query)){
		$status="<h4 style='background-color:cyan'>Data Successfully submitted</h4>";
	}
		else
		{
		$status="<h4 style='background-color:cyan'>Something went wrong. Contact support.</h4>";
		  die(mysqli_error($db));
		}

	
}
?>
