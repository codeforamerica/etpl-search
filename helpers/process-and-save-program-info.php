<?php
	include("../functions/process-program-info.php");
	include("../functions/save-program-info.php");
	include("db-config.php");
	
	if(isset($_GET["id"])) {
		$program_id = $_GET["id"];
		$data = process_data($program_id);

		save_data_to_db($db, $data);

		mysqli_close($db);
	}
?>