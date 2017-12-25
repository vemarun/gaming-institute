<!DOCTYPE html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<title>CDN - GIC </title>

	<style>
		table, th, td {
   border: 1px solid black;
}
	</style>
	
</head>

<body>
<center><h1>GIC | CDN<sub>α</sub></h1></center><br><br>

<table class="table table-responsive">
<tr><th><center>Map Name</center></th></tr>
<?php
	
$dir = "lava/tf/maps/";

// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file =readdir($dh)) !== false){
      if ($file == '.' or $file == '..') continue;
      echo "<center><h5><a href='lava/tf/maps/$file'>". $file. "</a></h5></center><br>";
    }
    closedir($dh);
  }
}
?>
	</table>
	
	</body>
	
</html>