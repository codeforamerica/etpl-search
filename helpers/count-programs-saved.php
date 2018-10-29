<?php if(isset($_GET["auto-refresh"])) { ?>
	<script type="text/javascript">
		setTimeout(function(){
		   window.location.reload(1);
		}, 1000);
	</script>
<?php } ?>

<body style="background: black;">
<h1 style="font-family: -apple-system, system-ui; width: 100%; text-align: center; font-size: 200px; color: white; margin-top: 200px;">
	<?php
		include("db-config.php");

		if($result = mysqli_query($db, "SELECT * FROM programs")) {
			echo mysqli_num_rows($result);
			mysqli_free_result($result);
		}

		mysqli_close($db);
	?>
</h1>
</body>