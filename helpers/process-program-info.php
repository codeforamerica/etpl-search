<?php
	include("../functions/process-program-info.php");	
	$program_id = $_GET["id"];
?>

<pre><?php print_r(process_data($program_id)); ?></pre>