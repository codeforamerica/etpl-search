<pre>
<?php
	include("save-program-data-to-db.php");
	
	$file = file_get_contents('data/all-programs.txt');
	$json = json_decode($file, true);
	
	// print_r($json);
	
	foreach($json as $data => $data_field) {
		
		foreach($data_field["careerPathways"] as $careerPathways => $careerPathway) {
			
			foreach($careerPathway["occupations"] as $occupations => $occupation) {
				
				foreach($occupation["programsOfStudy"] as $programsOfStudy => $programOfStudy) {
					print_r($programOfStudy["cipCode"]);
					echo "<br>";
					
					getProviderPrograms($programOfStudy["cipCode"]);
				}
				
			}
			
		}
	}
?>