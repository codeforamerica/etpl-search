<?php
	include("shared/functions.php");
	include("../config.php");
	include("../helpers/db-config.php");
	include("../functions/generate-rating.php");

	$query = mysqli_prepare($db, "SELECT * FROM programs WHERE program_id = ? LIMIT 1");
    mysqli_stmt_bind_param($query, "s", $_GET["id"]);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $programs);

  	$programs = mysqli_stmt_get_result($query);
    mysqli_stmt_close($query);
	mysqli_close($db);
	
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
	
	$description_character_limit = 350;
	
	if(isset($_GET["force_research_prompt"])) {
		$research_prompt_position = $_GET["force_research_prompt"];
	} else {
  		$research_prompt_position = rand(0, 15);
	}
?>
<!DOCTYPE HTML>
<html>

	<head>
		<title>Find the Best Training</title>
		<link rel="stylesheet/less" href="../resources/css/style.less<?php no_cache(); ?>">
		<script src="../resources/js/lib/less.js"></script>
		<script src="../resources/js/lib/jquery.js"></script>
		<script src="../resources/js/common.js<?php no_cache(); ?>"></script>
		<script src="../resources/js/program.js<?php no_cache(); ?>"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
  <body ontouchstart="" class="program-detail-page" data-program-id="<?php echo $program["program_id"]; ?>">

		<div id="wrapper" class="program-detail-page">
			<a href="javascript: window.history.back();" class="back">
				<div class="icon"></div>
				<label>all results</label>
			</a>
			<div id="content">
				<h1><div class="rating" style="background-color: <?php echo $program["rating"]["color"]; ?>;<?php if($config["show-ratings"] != "true" || $program["rating"]["total"] == "nan") { echo " display: none;"; } ?>"><?php echo $program["rating"]["total"]; ?></div><?php echo $program["program_name"];?></h1>
				<h2><a target="_blank" href="<?php if(strpos($program["provider_website"], "http") === false) { echo "http://"; } echo $program["provider_website"]; ?>"><?php echo $program["provider_name"]; ?></a></h2>
				<?php if($config["show-data"] == "true") { ?>
				<div class="data">
					<?php include("shared/program-data.php"); ?>
				</div>
				<?php } ?>
				
				<?php if($research_prompt_position == 0) { include("../views/shared/research-prompt.php"); } ?>
				
				<section>
					<h3>Description</h3>
					<div class="section-content">
						<p id="description" <?php if(strlen($program["description_short"]) > $description_character_limit) { echo 'class="truncated"'; } ?>><?php echo $program["description_short"]; ?></p>
						<?php if(strlen($program["description_short"]) > $description_character_limit) { ?><div class="show-more" id="description">Continue Reading...</div><?php } ?>
					</div>
				</section>
				
				<?php if($research_prompt_position == 1) { include("../views/shared/research-prompt.php"); } ?>
				
				<section class="contact-info">
					<h3>Contact Info</h3>
					<div class="section-content">
						<p>
							<b><?php echo $program["contact_name"]; ?></b>
							<br>
							<?php if($program["contact_email"] != "") { ?>
							<a class="contact" href="mailto:<?php echo $program["contact_email"]; ?>"><?php echo $program["contact_email"]; ?></a>
							<?php } ?>
							<?php if($program["contact_email"] != "" && $program["contact_phone_number"] != "") { echo " &middot; "; } ?> 
							<?php if($program["contact_phone_number"] != "") { ?>
							<a class="contact" href="tel:<?php echo $program["contact_phone_number"]; ?><?php if($program["contact_phone_number_ext"] != "") { echo ",".$program["contact_phone_number_ext"]; } ?>"><?php echo $program["contact_phone_number"]; if($program["contact_phone_number_ext"] != "") { echo " ext ".$program["contact_phone_number_ext"]; } ?></a>
							<?php } ?>
						</p>
					</div>
					<?php if($program["contact_email"] != "" && $program["contact_phone_number"] != "") { ?>
					<a href="mailto:<?php echo $program["contact_email"]; ?>"><button class="half-width left">Email</button></a><a href="tel:<?php echo $program["contact_phone_number"]; ?><?php if($program["contact_phone_number_ext"] != "") { echo ",".$program["contact_phone_number_ext"]; } ?>"><button class="half-width right">Call</button></a>
					<?php } else { ?>
						<?php if($program["contact_email"] != "") { ?>
							<a href="mailto:<?php echo $program["contact_email"]; ?>"><button class="full-width">Email</button></a>
						<?php } ?>
						<?php if($program["contact_phone_number"] != "") { ?>
							<a href="tel:<?php echo $program["contact_phone_number"]; ?><?php if($program["contact_phone_number_ext"] != "") { echo ",".$program["contact_phone_number_ext"]; } ?>"><button class="full-width">Call</button></a>
						<?php } ?>
					<?php } ?>
							
				</section>
								
				<section>
					<h3>Program Info</h3>
					<div class="section-content">
						<table>
							<tr><td class="label">Cost</td><td class="value"><?php echo $program["cost_total"]; ?></td></tr>
							
							<?php if($program["info_clock_hours"] != "") { ?>
								<tr><td class="label">Hours</td><td class="value"><?php echo $program["info_clock_hours"]; ?></td></tr>
							<?php } ?>
							
							<?php if($program["info_credit_hours"] != "") { ?>
								<tr><td class="label">Credit Hours</td><td class="value"><?php echo $program["info_credit_hours"]; ?></td></tr>
							<?php } ?>
							
							<?php if($program["info_calendar_length"] != "") { ?>
								<tr><td class="label">Length</td><td class="value"><?php echo $program["info_calendar_length"]; ?></td></tr>
							<?php } ?>
														
							<?php if($program["credentials_credential"] != "0" && $program["credentials_credential"] != "") { ?>
								<tr><td class="label">Credential</td><td class="value"><?php echo $program["credentials_credential"]; ?></td></tr>
							<?php } ?>

							<?php if($program["credentials_degree"] != "0" && $program["credentials_degree"] != "") { ?>
								<tr><td class="label">Degree</td><td class="value"><?php echo $program["credentials_degree"]; ?></td></tr>
							<?php } ?>

							<?php if($program["credentials_license"] != "0" && $program["credentials_license"] != "") { ?>
								<tr><td class="label">License</td><td class="value"><?php echo $program["credentials_license"]; ?></td></tr>
							<?php } ?>
						</table>
					</div>
				</section>
				
				<?php if($research_prompt_position == 2) { include("../views/shared/research-prompt.php"); } ?>

				<section>
					<h3>Location</h3>
					<div class="section-content">
						<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode($program["provider_address"]); ?>" class="map">
							<img src="https://maps.googleapis.com/maps/api/staticmap?zoom=13&size=400x200&scale=2&markers=<?php echo urlencode($program["provider_address"]); ?>&key=AIzaSyAdzf6wnfWspcIZ360kRuIiVq1kpePPSGo" class="map">
							<p style="margin-top: 10px;">
								<b><?php echo $program["provider_name"]; ?></b>
								<br>
								<?php echo str_replace(",", "<br>", $program["provider_address"]); ?>
							</p>
						</a>
					</div>
				</section>
				
				<?php if($research_prompt_position == 3) { include("../views/shared/research-prompt.php"); } ?>
				
				<?php if(count($features) != array_count_values($features)[0]) { ?>
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
				<?php } ?>
				
				<?php if($research_prompt_position == 4) { include("../views/shared/research-prompt.php"); } ?>
			</div>
		</div>
		
		<?php include("shared/analytics.php"); ?>
	</body>
</html>

