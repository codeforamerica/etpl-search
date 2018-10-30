<?php
	if(getenv("PRODUCTION") == "true") {
		include("google-analytics.php");
		include("mixpanel.php");
		include("full-story.php");
	}
?>