<?php
	include("../functions/process-program-info.php");
	
	if($_GET["list"] == "sample") {
		include("../data/list-raw-html-sample.php");
	} elseif($_GET["list"] == "all") {
		include("../data/list-raw-html-all.php");
	}
	
	echo "<pre>";

	foreach($list as $list_item_key => $list_item_value) {
		preg_match('/<td valign="top"><table border="0" cellpadding="3" cellspacing="0" width="100%"><tbody><tr><td style="vertical-align: top;">(.*)<font face="arial, helvetica" size="3"><b><a class="(.*)" href="..\/Program\/Default.aspx\?ProgramID=(.*)">(.*)<\/a><\/b><br><font size="2">(.*)<\/font><\/font><\/td><td style="vertical-align: top;"><table cellpadding="2" cellspacing="0" border="0" width="100%"><tbody>/', $list_item_value, $output_array);
	
		echo "<b>".$output_array[3]."</b>";
		echo "<br>";
		
		$program_id = $output_array[3];
		print_r(process_data($program_id));
	}
?>