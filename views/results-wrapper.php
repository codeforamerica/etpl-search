<!DOCTYPE HTML>
<html>

	<head>
		<title>Find the Best Training</title>
		<link rel="stylesheet/less" href="../resources/css/style.less?<?php echo rand(0, 99999); ?>">
		<script src="../resources/js/lib/less.js"></script>
		<script src="../resources/js/lib/jquery.js"></script>
		<script src="../resources/js/common.js?<?php echo rand(0, 99999); ?>"></script>
		<script src="../resources/js/results.js?<?php echo rand(0, 99999); ?>"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body ontouchstart="" class="results">

		<div id="wrapper" class="results">
			<div class="header-wrapper">
				<form>
					<a href="search.php" class="back"></a>
					<input type="search" class="search" placeholder="Type a skill area..." name="query" autocomplete="off" value="<?php echo $_GET['query']; ?>">
					<div id="checkboxes-wrapper">
						<div id="checkboxes-scroll-wrapper">
							<div id="checkboxes-container">
								<div class="checkbox-wrapper <?php if($_GET["features_child_care_offered_on_site"] == on ) { echo "checked"; }?>">
									<input type="checkbox" name="features_child_care_offered_on_site" <?php if($_GET["features_child_care_offered_on_site"] == on ) { echo "checked"; }?>><div class="checkbox"></div><label for="features_child_care_offered_on_site">On-Site Child Care Available</label>
								</div>
					
								<div class="checkbox-wrapper <?php if($_GET["features_wheelchair_accessible"] == on ) { echo "checked"; }?>">
									<input type="checkbox" name="features_wheelchair_accessible" <?php if($_GET["features_wheelchair_accessible"] == on ) { echo "checked"; }?>><div class="checkbox"></div><label for="features_wheelchair_accessible">Wheelchair Accessible</label>
								</div>
					
								<div class="checkbox-wrapper <?php if($_GET["features_career_counseling_available"] == on ) { echo "checked"; }?>">
									<input type="checkbox" name="features_career_counseling_available" <?php if($_GET["features_career_counseling_available"] == on ) { echo "checked"; }?>><div class="checkbox"></div><label for="features_career_counseling_available">Career Counseling Available</label>
								</div>
					
								<div class="checkbox-wrapper <?php if($_GET["info_admission_pre_requisites"] == on ) { echo "checked"; }?>">
									<input type="checkbox" name="info_admission_pre_requisites" <?php if($_GET["info_admission_pre_requisites"] == on ) { echo "checked"; }?>><div class="checkbox"></div><label for="info_admission_pre_requisites">No Pre-Requisites</label>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			
			<div id="results-wrapper">
				<?php include("results.php"); ?>
			</div>
		</div>

	</body>
</html>

