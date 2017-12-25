<?php 
require 'steamauth/steamauth.php';
include_once '../db_connect.php';
$status="";
?>
<?php
    extract($_POST);
if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $value=explode('.',$_FILES['image']['name']);
      $file_ext=strtolower(end($value));
      
      
      $expensions= array("jpeg","jpg","png","gif");
      
      if(in_array($file_ext,$expensions)=== false){
   $errors[]="extension not allowed, please choose a JPEG or PNG file.";
         $status="Extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152) {
         $errors[]='File size must be less than 2 MB'; 
         $status='File size must be less than 2 MB';
      }
      
      if(empty($errors)==true) {
        move_uploaded_file($file_tmp,"../images/".$file_name);
         }
	// Use

	$q="insert into web_setting(bg) values('$file_name')";
		  $r=mysqli_query($db,$q);
	$status="Success File Uploaded";
	
         
      }
?>
	<?php
if(isset($_POST['save'])){
	$query2="update web_setting set bg_active=0";
	$result2=mysqli_query($db,$query2);
	$query="update web_setting
	set bg_active=1 where bg='$selected'";
	$result=mysqli_query($db,$query);
	$status="Settings saved";
}
?>
		<?php
if(isset($_POST['save2'])){
	$query3="update web_setting set bg2_active2=0";
	$result3=mysqli_query($db,$query3);
	$query4="update web_setting
	set bg2_active2=1 where bg='$selected2'";
	$result4=mysqli_query($db,$query4);
	$status="Bottom BG Settings saved";
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



							<li>
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

						</ul>
					</div>

				</nav>
				<!-- /. NAV SIDE  -->
				<div id="page-wrapper">
					<div id="page-inner">
						<div style="background-color:cyan">
							<?php echo $status; ?>
						</div>


<!-------------------------------------------------------------------------------------------->

						<h3><u>Change Homepage Central Image</u> </h3>
						<center>
							<?php
						$q="select bg from web_setting where bg_active=1";
						$r=mysqli_query($db,$q);
						$row=mysqli_fetch_array($r);
						?>
								<h4>Current Image:
									<?php echo $row[0]; ?>
								</h4>
								<img src="../images/<?php echo $row[0]; ?>" height="150px" width="250px"><br><br>
								<h4>Choose image or upload</h4>
								<form method="post">


									<?php
						$qu="select bg from web_setting where bg_active=0";
						$res=mysqli_query($db,$qu);
						while($row2=mysqli_fetch_array($res)){
							
						?>

										<img src="../images/<?php echo $row2[0]; ?>" height="150px" width="250px">

										<input type="radio" name="selected" value="<?php echo $row2[0]; ?>">



										<?php } ?><br><br>
										<input type="submit" name="save" value="Save setting">
								</form>
	<!-------------------------------------------------------------------------------------------->							
								<hr style="border-top: 2px solid #ccc">
							<h3><u>Change Homepage bottom background Image</u></h3>
								
						
							<?php
						$que="select bg from web_setting where bg2_active2=1";
						$resu=mysqli_query($db,$que);
						$row3=mysqli_fetch_array($resu);
						?>
								<h4>Current Image:
									<?php echo $row3[0]; ?>
								</h4>
								<img src="../images/<?php echo $row3[0]; ?>" height="150px" width="250px"><br><br>
								<h4>Choose image or upload</h4>
								<form method="post">


									<?php
						$quer="select bg from web_setting where bg2_active2=0";
						$resul=mysqli_query($db,$quer);
						while($row4=mysqli_fetch_array($resul)){
							
						?>

										<img src="../images/<?php echo $row4[0]; ?>" height="150px" width="250px">

									<input type="radio" name="selected2" value="<?php echo $row4[0]; ?>">



										<?php } ?><br><br>
										<input type="submit" name="save2" value="Save setting">
								</form>


<!-------------------------------------------------------------------------------------------->

								<br><br><br>
								<hr style="border-top: 2px solid #ccc">
								<form action="" method="post" enctype="multipart/form-data">
									<input type="file" name="image"><br><br>
									<input type="submit" name="sub" value="Upload">
								</form>
						</center>

					</div>
				</div>
			</div>

		</body>

		</html>
