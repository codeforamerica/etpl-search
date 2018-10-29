<?php
	if(	$_SERVER["REMOTE_ADDR"] == "localhost" ||
		$_SERVER["REMOTE_ADDR"] == "leo.local" ||
		$_SERVER["REMOTE_ADDR"] == "::1"
	) {
		$db = mysqli_connect("localhost", "root", "root", "nj-etpl");
	} else {
		$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

		$server = $url["host"];
		$username = $url["user"];
		$password = $url["pass"];
		$database = substr($url["path"], 1);

		$db = mysqli_connect($server, $username, $password, $database);
	}
?>