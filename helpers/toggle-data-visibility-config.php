<?php
	if($_GET["visibility"] == "show") {
		putenv("SHOW_RATINGS=true");
		putenv("SHOW_DATA=true");
	} elseif($_GET["visibility"] == "false") {
		putenv("SHOW_RATINGS=false");
		putenv("SHOW_DATA=false");
	}
?>