<?php
	include("../helpers/db-config.php");
	include("../functions/generate-rating.php");

	$query = "SELECT * FROM programs WHERE program_id = '".mysqli_real_escape_string($db, $_GET["id"])."' LIMIT 1";
	// echo $query;
	$programs = mysqli_query($db, $query);

	if(mysqli_num_rows($programs) > 0) {
		$program = mysqli_fetch_assoc($programs);
		
		$rating = generate_rating($program);
		$program["rating"] = $rating;
		
		$features = Array(
			"WIA Eligible" => $program["features_wia_eligible"],
			"On-Site Child Care Available" => $program["features_child_care_offered_on_site"],
	    	"Assistance in Obtaining Child Care" => $program["features_assistance_in_obtaining_child_care"],
	    	"Wheelchair Accessible" => $program["features_wheelchair_accessible"],
	    	"Career Counseling Available" => $program["features_career_counseling_available"],
	    	"Customized Training Services" => $program["features_customized_training_services_provider"], 
	    	"Distance Learning Services" => $program["features_distance_learning_services_provider"],
	    	"Spanish Spoken by Staff" => $program["features_spanish_spoken_by_staff"],
	    	"Other Languages Spoken by Staff" => $program["features_other_languages_spoken_by_staff"]
		);
	} else {
		echo '<div class="no-results">Sorry, no matches...</div>';
	}
?>
<!DOCTYPE HTML>
<html>

	<head>
		<title>Find the Best Training</title>
		<link rel="stylesheet/less" href="../resources/css/style.less?<?php echo rand(0, 99999); ?>">
		<script src="../resources/js/lib/less.js"></script>
		<script src="../resources/js/lib/jquery.js"></script>
		<script src="../resources/js/common.js?<?php echo rand(0, 99999); ?>"></script>
		<script src="../resources/js/program.js?<?php echo rand(0, 99999); ?>"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body ontouchstart="" class="program-detail-page">

		<div id="wrapper" class="program-detail-page">
			<a href="javascript: window.history.back();" class="back">
				<div class="icon"></div>
				<label>all results</label>
			</a>
			<div id="content">
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
				
				<section>
					<h3>Description</h3>
					<div class="section-content">
						<p id="description" <?php if(strlen($program["description_short"]) > 5) { echo 'class="truncated"'; } ?>><?php echo $program["description_short"]; ?></p>
						<div class="show-more" id="description">Continue Reading...</div>
					</div>
				</section>
				
				<section>
					<h3>Contact Info</h3>
					<div class="section-content">
						<p>
							<b><?php echo $program["contact_name"]; ?></b>
							<br>
							<a class="contact" href="mailto:<?php echo $program["contact_email"]; ?>"><?php echo $program["contact_email"]; ?></a> &middot;
							<a class="contact" href="tel:<?php echo $program["contact_phone_number"]; ?><?php if($program["contact_phone_number_ext"] != "") { echo ",".$program["contact_phone_number_ext"]; } ?>"><?php echo $program["contact_phone_number"]; if($program["contact_phone_number_ext"] != "") { echo " ext ".$program["contact_phone_number_ext"]; } ?></a>
						</p>
					</div>
				</section>
				
				<a href="mailto:<?php echo $program["contact_email"]; ?>"><button class="half-width left">Email</button></a><a href="tel:<?php echo $program["contact_phone_number"]; ?><?php if($program["contact_phone_number_ext"] != "") { echo ",".$program["contact_phone_number_ext"]; } ?>"><button class="half-width right">Call</button></a>
				

				<section>
					<h3>Location</h3>
					<div class="section-content">
						<img src="https://maps.googleapis.com/maps/api/staticmap?zoom=13&size=400x250&markers=<?php echo urlencode($program["provider_address"]); ?>&key=AIzaSyAdzf6wnfWspcIZ360kRuIiVq1kpePPSGo" class="map">
						<p style="margin-top: 10px;">
							<b><?php echo $program["provider_name"]; ?></b>
							<br>
							<?php echo str_replace(",", "<br>", $program["provider_address"]); ?>
						</p>
					</div>
				</section>
				
				<section>
					<h3>Features</h3>
					<div class="section-content">
						<?php foreach($features as $label => $value) { ?>
							<?php if($value == 1) { ?>
								<div class="feature"><input type="checkbox" checked><div class="checkbox"></div><label><?php echo $label; ?></label></div>
							<?php } ?>
						<?php } ?>
					</div>
				</section>
			</div>
		</div>
		
		<?php include("shared/analytics.php"); ?>
	</body>
</html>

