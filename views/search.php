<?php
	include("shared/functions.php");
	include("../config.php");

	$suggestions = Array(
		Array(
			"icon" => 6,
			"label" => "Computer",
			"search-terms" => "computer software data it technology",
		),
		Array(
			"icon" => 1,
			"label" => "Health Care",
			"search-terms" => "medical health healthcare nursing nurse dental",
		),
		Array(
			"icon" => 2,
			"label" => "Sales",
			"search-terms" => "sales marketing",
		),
		Array(
			"icon" => 5,
			"label" => "Business",
			"search-terms" => "business management international entrepreneurship",
		),
		Array(
			"icon" => 11,
			"label" => "Education",
			"search-terms" => "education teaching",
		),
		Array(
			"icon" => 8,
			"label" => "Science/Math",
			"search-terms" => "science math",
		)
	);
?>

<!DOCTYPE HTML>
<html>

  <head>
    <title>Find the Best Training</title>
    <link rel="stylesheet/less" href="../resources/css/style.less<?php no_cache(); ?>">
    <script src="../resources/js/lib/less.js"></script>
    <script src="../resources/js/lib/jquery.js"></script>
    <script src="../resources/js/common.js<?php no_cache(); ?>"></script>
    <script src="../resources/js/search.js<?php no_cache(); ?>"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body ontouchstart="" class="search">

    <div id="wrapper" class="search">
      <div id="people-wrapper">
        <div id="people"></div>
      </div>
      <h1>Find the best training for your next job.</h1>
      <!--<h2>Compare training programs that are eligible for tuition assistance from the government.</h2>-->
	  <h2>Type a skill or job you want and see relevant training programs eligible for tuition assistance.</h2>
      <form method="GET" action="results-wrapper.php">
		<?php if(isset($_GET["experimental_data"])) { ?>
	        <input type="text" name="state" class="location" value="<?php echo ucwords($_GET["state"]); ?>" placeholder="Type a state..." autocomplete="off">
	        <input type="hidden" name="experimental_data">
		<?php } else { ?>
	        <input type="text" class="location" value="New Jersey" disabled>
		<?php } ?>
        <input type="search" class="search" placeholder="Type a skill or job..." name="query" autocomplete="off">
        <input type="submit" value="" class="circle">
		<?php if($config["show-search-suggestions"] == "true") { ?>
		<div id="suggestions-wrapper">
			<div id="suggestions-scroll-wrapper">
				<div id="suggestions-container">
					<?php foreach($suggestions as $suggestion_key => $suggestion) { ?>
						<div id="<?php echo $suggestion["label"]; ?>" data-search-terms="<?php echo str_replace(" ", "/", $suggestion["search-terms"]); ?>" class="suggestion-wrapper">
							<div class="icon" style="background-position-y: -<?php echo $suggestion["icon"]*32; ?>px;"></div>
							<label><?php echo $suggestion["label"]; ?></label>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if(!isset($_GET["experimental_data"])) { ?>
        <h3>Filters</h2>
        <div id="checkboxes">
          <?php include("shared/filters.php"); ?>
        </div>
		<?php } ?>

        <input type="submit" value="Search" class="fake">
      </form>
    </div>

    <?php include("shared/analytics.php"); ?>
  </body>
</html>
