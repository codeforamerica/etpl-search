<?php
	if(	$_SERVER["REMOTE_ADDR"] == "localhost" ||
		$_SERVER["REMOTE_ADDR"] == "leo.local" ||
		$_SERVER["REMOTE_ADDR"] == "::1" ||
		strpos($_SERVER["REMOTE_ADDR"], '192.168.1') !== false
	) {
		$config = Array(
			"show-search-suggestions" => "true",
			"show-data" => "true",
			"show-ratings" => "true",
			"show-research-prompt" => "true"
		);
	} else {
		$config = Array(
			"show-search-suggestions" => getenv("SHOW_SEARCH_SUGGESTIONS"),
			"show-data" => getenv("SHOW_DATA"),
			"show-ratings" => getenv("SHOW_RATINGS"),
			"show-research-prompt" => getenv("SHOW_RESEARCH_PROMPT")
		);
	}
	
	if($_GET["config_override"] == 1) {
		if($_GET["show_ratings"] == 1) {
			$config["show-ratings"] = "true";
		}
		if($_GET["show_data"] == 1) {
			$config["show-data"] = "true";
		}
	}
?>