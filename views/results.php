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
			$sort = $rating["total"]."-".$program_name;
			$program_list[$sort] = $program;
			$program_list[$sort]["rating"] = $rating;
		}
	} else {
		echo '<div class="no-results">Sorry, no matches...</div>';
	}
	
	krsort($program_list);
	
	foreach($program_list as $sort => $program) {
		?>
			<div class="program">
				<h1><div class="rating" style="background-color: <?php echo $program["rating"]["color"]; ?>;"><?php echo $program["rating"]["total"]; ?></div><?php echo $program["program_name"];?></h1>
				<h2><?php echo $program["provider_name"];?></h2>
				<div class="data">
					<?php if($program["outcomes_data_6_months_this_program_employment_rate_value"] != "N/A") { ?>
						<h3 class="success"><div class="icon"></div><div class="number"><?php echo $program["outcomes_data_6_months_this_program_employment_rate_value"]; ?></div> employed after 6 months</h3>
					<?php } ?>
					
					<?php if($program["outcomes_data_6_months_this_program_wage_yearly_value"] != "N/A") { ?>
						<h3 class="wages"><div class="icon"></div><div class="number">$<?php echo number_format(round(int(str_replace("$", "", $program["outcomes_data_6_months_this_program_wage_yearly_value"])), 1)); ?></div> salary after 6 months</h3>
					<?php } ?>
					
					<?php if($program["outcomes_data_2_years_this_program_wage_yearly_value"] != "N/A") { ?>
						<h3 class="wages"><div class="icon"></div><div class="number">$<?php echo number_format(round(int(str_replace("$", "", $program["outcomes_data_2_years_this_program_wage_yearly_value"])), 1)); ?></div> salary after 2 years</h3>
					<?php } ?>
				</div>
				<div class="debug">
					<pre><?php print_r($program["rating"]["components"]); ?></pre>
				</div>
			</div>
		<?php
	}

	mysqli_close($db);
?>