<?php include("shared/functions.php"); ?>
<!DOCTYPE HTML>
<html>

<head>
	<title>Find the Best Training</title>
	<link rel="stylesheet/less" href="../resources/css/style.less<?php no_cache(); ?>">
	<script src="../resources/js/lib/less.js"></script>
	<script src="../resources/js/lib/jquery.js"></script>
	<script src="../resources/js/common.js<?php no_cache(); ?>"></script>
	<script src="../resources/js/results.js<?php no_cache(); ?>"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body ontouchstart="" class="results">

	<div id="wrapper" class="results">
		<div class="header-wrapper">
			<form>
					<a href="search.php<?php if(isset($_GET["experimental_data"])) { echo "?experimental_data&state=".$_GET["state"]; } ?>" class="back"></a>
					<input type="search" class="search" placeholder="Type a skill..." name="query" autocomplete="off" value="<?php echo $_GET['query']; ?>">
					<?php if(isset($_GET["experimental_data"])) { ?>
					<input type="hidden" name="experimental_data">
					<?php } ?>
					<input type="hidden" name="state" value="<?php echo $_GET['state']; ?>">
					<?php if(!isset($_GET["experimental_data"])) { ?>
						<div id="checkboxes-wrapper">
							<div id="checkboxes-scroll-wrapper">
								<div id="checkboxes-container">
									<?php include("shared/filters.php"); ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</form>
			</div>
			
			<div id="results-wrapper" class="<?php if(isset($_GET["experimental_data"])) { echo "no-filters"; } ?>">
				<?php include("results.php"); ?>
			</div>
		</div>
    
		<?php include("shared/analytics.php"); ?>
	</body>
	</html>

