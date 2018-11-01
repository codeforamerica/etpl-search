<?php 
	$ch = curl_init();

	$alphas = range('a', 'z');
	
	foreach($alphas as $alpha) {

		curl_setopt($ch, CURLOPT_URL, "https://www.cotrainingproviders.org/api/provider/getProgramsSearchItems/".$alpha."/999999999");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HEADER, 0);

		$out[$alpha] = curl_exec($ch);

		/*
		$fp = fopen('data/'.$alpha.'.txt', 'w');
		fwrite($fp, $out);
		fclose($fp);
		*/
		
		curl_close($ch[$alpha]);

		echo "<br><br>-------".$alpha."-------<br>";
		// echo $out[$alpha];
		$fp = fopen('data/alpha/'.$alpha.'.txt', 'w');
		fwrite($fp, $out[$alpha]);
		fclose($fp);
		// echo 'data/'.$alpha.'.txt'."<br>";
	}
?>