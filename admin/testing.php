<!DOCTYPE html>
<html>

<head>
	<script src="assets/js/jquery-1.10.2.js"></script>
	<script>
		$(document).ready(function() {
			$("#btn").click(function() {
				$.ajax({
					url: "admin_match.php",
					success: function(result) {
						$("#div1").html(result);
					}
				});
			});
		});

	</script>
</head>

<body>

	<div id="div1">
		<h2>Let jQuery AJAX Change This Text</h2>
	</div>

	<button id="btn">Get External Content</button>

</body>

</html>
