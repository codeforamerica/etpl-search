<?php 
	$id = $_GET["id"];

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "http://www.njtrainingsystems.org/Program/Default.aspx?ProgramID=".$id);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_HEADER, 0);

	$out = curl_exec($ch);

	curl_close($ch);

	$fp = fopen('../data/programs/'.$id.'.html', 'w');
	fwrite($fp, $out);
	fclose($fp);
?>