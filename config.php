<?php
	if(	$_SERVER["REMOTE_ADDR"] == "localhost" ||
		$_SERVER["REMOTE_ADDR"] == "leo.local" ||
		$_SERVER["REMOTE_ADDR"] == "::1" ||
		strpos($_SERVER["REMOTE_ADDR"], '192.168.1') !== false
	) {
		$config = Array(
			"show-suggestions" => 1,
			"show-data" => 1,
			"show-ratings" => 1,
			"show-research-prompt" => 0
		);
	} else {
		$config = Array(
			"show-suggestions" => getenv("SHOW_SUGGESTIONS"),
			"show-data" => getenv("SHOW_DATA"),
			"show-ratings" => getenv("SHOW_RATINGS"),
			"show-research-prompt" => getenv("SHOW_RESEARCH_PROMPT")
		);
	}
?>