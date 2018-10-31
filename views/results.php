<?php
	include("../helpers/db-config.php");
	include("../functions/generate-rating.php");
	
	if($_GET["query"]) {
		$additional_query .= "WHERE
			(program_name LIKE '%".mysqli_real_escape_string($db, $_GET["query"])."%' OR
			provider_name LIKE '%".mysqli_real_escape_string($db, $_GET["query"])."%')
		";
	} else {
		$additional_query .= "WHERE program_name LIKE '%'";
	}
	
	if($_GET["features_child_care_offered_on_site"] == "on") {
		$additional_query .= " AND features_child_care_offered_on_site = 1";
	}
	
	if($_GET["features_wheelchair_accessible"] == "on") {
		$additional_query .= " AND features_wheelchair_accessible = 1";
	}
	
	if($_GET["features_career_counseling_available"] == "on") {
		$additional_query .= " AND features_career_counseling_available = 1";
	}
	
	if($_GET["info_admission_pre_requisites"] == "on") {
		$additional_query .= " AND info_admission_pre_requisites = 'none'";
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