<!DOCTYPE HTML>
<html>

	<head>
		<title>Find the Best Training</title>
		<link rel="stylesheet/less" href="../resources/css/style.less?<?php echo rand(0, 99999); ?>">
		<script src="../resources/js/lib/less.js"></script>
		<script src="../resources/js/lib/jquery.js"></script>
		<script src="../resources/js/common.js?<?php echo rand(0, 99999); ?>"></script>
		<script src="../resources/js/search.js?<?php echo rand(0, 99999); ?>"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body ontouchstart="" class="search">

		<div id="wrapper" class="search">
			<div id="people-wrapper">
				<div id="people"></div>
			</div>
			<h1>Find the best training for your next job.</h1>
			<br>
			<form method="GET" action="results-wrapper.php">
				<input type="text" class="location" value="New Jersey" disabled>
				<br>
				<input type="search" class="search" placeholder="Type a skill area..." name="query" autocomplete="off">
				<input type="submit" value="" class="circle">
				<br>
				<h3>Filters</h2>
				<div id="checkboxes">
					<div class="checkbox-wrapper">
						<input type="checkbox" name="features_child_care_offered_on_site"><div class="checkbox"></div><label for="features_child_care_offered_on_site">On-Site Child Care Available</label>
					</div>
					<div class="checkbox-wrapper">
						<input type="checkbox" name="features_wheelchair_accessible"><div class="checkbox"></div><label for="features_wheelchair_accessible">Wheelchair Accessible</label>
					</div>
					<div class="checkbox-wrapper">
						<input type="checkbox" name="features_career_counseling_available"><div class="checkbox"></div><label for="features_career_counseling_available">Career Counseling Available</label>
					</div>
					<div class="checkbox-wrapper">
						<input type="checkbox" name="info_admission_pre_requisites"><div class="checkbox"></div><label for="info_admission_pre_requisites">No Pre-Requisites</label>
					</div>
				</div>

				<input type="submit" value="Search" class="fake">
			</form>
		</div>
	</body>
</html>