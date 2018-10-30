<pre><?php
		include("../functions/process-program-info.php");
		include("../functions/save-program-info.php");
		include("db-config.php");
		
		include("../data/list-raw-html-".$_GET["list"].".php");
	
		echo "<pre>";

		foreach($list as $list_item_key => $list_item_value) {
			$list_item_value = str_replace("\n", "", $list_item_value);
			$list_item_value = str_replace("\r", "", $list_item_value);
			
			preg_match('/<td valign="top"><table border="0" cellpadding="3" cellspacing="0" width="100%"><tbody><tr><td style="vertical-align: top;">(.*)<font face="arial, helvetica" size="3"><b><a class="(.*)" href="..\/Program\/Default.aspx\?ProgramID=(.*)">(.*)<\/a><\/b><br><font size="2">(.*)<\/font><\/font><\/td><td style="vertical-align: top;"><table cellpadding="2" cellspacing="0" border="0" width="100%"><tbody>/', $list_item_value, $output_array);
	
			echo "Loading ".$output_array[3]."...";

			$program_id = $output_array[3];
			$data = process_data($program_id);
			
			print_r($data);
			save_data_to_db($db, $data);
			
			echo "<br>";
			
		}

		mysqli_close($db);
	?>
</pre>