<?php
	function no_cache() {
		if(getenv("PRODUCTION") != "true") { echo "?".rand(0, 99999); }
	}
?>