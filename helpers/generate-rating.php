<?php	
	include("../functions/generate-rating.php");
	include("../helpers/db-config.php");
	
	$sql = "SELECT * FROM programs WHERE program_id = '".$_GET["id"]."'";
	$programs = mysqli_query($db, $sql);

	$program = mysqli_fetch_assoc($programs);

	$rating = generate_rating($program);
	echo $rating["total"];
	echo "<br><br>";

	mysqli_close($db);
?>