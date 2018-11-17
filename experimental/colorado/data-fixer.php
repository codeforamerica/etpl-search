<?php
	include("../../helpers/db-config.php");
	
	/*
	$query = "SELECT * FROM programs WHERE state = 'CO'";
	$programs = mysqli_query($db, $query);

	while($program = mysqli_fetch_assoc($programs)) {
		mysqli_query($db, "UPDATE programs SET `provider_name` = '".$program["cip_name"]."' WHERE `id` = '".$program["id"]."' AND state = 'CO'");		
	}
	
	*/
	
	$query = "SELECT * FROM programs WHERE state = 'NJ'";
	$programs = mysqli_query($db, $query);

	while($program = mysqli_fetch_assoc($programs)) {
		mysqli_query($db, "UPDATE programs SET `program_id` = 'NJ-".$program["program_id"]."' WHERE `id` = '".$program["id"]."'");		
	}
	
	mysqli_close($db);
?>