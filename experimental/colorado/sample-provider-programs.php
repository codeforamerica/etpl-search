<pre>
<?php
	$file = file_get_contents('data/sample-provider-programs.txt');
	$json = json_decode($file, true);
	
	// print_r($json);
	
	foreach($json as $data => $data_field) {
		print_r($data_field);
		
		echo "<br><br><br>";
	}
?>