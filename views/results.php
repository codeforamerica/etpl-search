<?php
	include("../helpers/db-config.php");
	include("../functions/generate-rating.php");
	
	if($_GET["query"]) {
		$query = $_GET["query"];
		
		$additional_query .= "WHERE
			(program_name LIKE '%".mysqli_real_escape_string($db, $query)."%'";
		
		$query_words = explode("/", $query);
		if(count($query_words) > 1) {
			foreach($query_words as $query_word) {
				if($query_word) {
					$additional_query .= " OR
					program_name LIKE '%".mysqli_real_escape_string($db, $query_word)."%'";
				}
			}
		}

		$additional_query .= ")";
	} else {
		$additional_query .= "WHERE program_name LIKE '%'";
	}
		
	if($_GET["attributes_in_demand"] == "on") {
		$additional_query .= " AND attributes_in_demand = 1";
	}
	
	if($_GET["features_child_care_offered_on_site"] == "on") {
		$additional_query .= " AND features_child_care_offered_on_site = 1";
	}
	
	if($_GET["features_distance_learning_services_provider"] == "on") {
		$additional_query .= " AND features_distance_learning_services_provider = 1";
	}
	
	if($_GET["features_spanish_spoken_by_staff"] == "on") {
		$additional_query .= " AND features_spanish_spoken_by_staff = 1";
	}
	
	if($_GET["features_other_languages_spoken_by_staff"] == "on") {
		$additional_query .= " AND features_other_languages_spoken_by_staff = 1";
	}
	
	if($_GET["features_wia_eligible"] == "on") {
		$additional_query .= " AND features_wia_eligible = 1";
	}
	
	if($_GET["features_wheelchair_accessible"] == "on") {
		$additional_query .= " AND features_wheelchair_accessible = 1";
	}
	
	if($_GET["features_career_counseling_available"] == "on") {
		$additional_query .= " AND features_career_counseling_available = 1";
	}

	$query = "SELECT * FROM programs ".$additional_query." LIMIT 250";
	// echo $query;
	$programs = mysqli_query($db, $query);

	if(mysqli_num_rows($programs) > 0) {
		while($program = mysqli_fetch_assoc($programs)) {
			$rating = generate_rating($program);
			$sort = str_pad($rating["total"], 5, '0', STR_PAD_LEFT)."-".str_replace(" ", "_", $program["program_name"]).$program["program_id"];
			$program_list[$sort] = $program;
			$program_list[$sort]["rating"] = $rating;
		}
	} else {
		echo '<div class="no-results">Sorry, no matches...</div>';
	}
	
	mysqli_close($db);
	
	krsort($program_list);
	$list_index = 0;
	$research_prompt_position = rand(4, 8);
	
	foreach($program_list as $sort => $program) {
		$list_index++;
?>
		<?php
			if($list_index == $research_prompt_position) {
				include("../views/shared/research-prompt.php");
			}
		?>
		
		<a href="program.php?id=<?php echo $program["program_id"];?>" class="program-link-wrapper">
			<div class="program" id="<?php echo $sort; ?>">
				<h1><div class="rating" style="background-color: <?php echo $program["rating"]["color"]; ?>;"><?php echo $program["rating"]["total"]; ?></div><?php echo $program["program_name"];?></h1>
				<h2><?php echo $program["provider_name"];?></h2>
				<div class="data">
					<?php include("shared/program-data.php"); ?>
				</div>
				<div class="debug">
					<pre><?php print_r($program["rating"]["components"]); ?></pre>
				</div>
			</div>
		</a>
		<?php
	}
?>