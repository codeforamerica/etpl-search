<?php
	if(	$_SERVER["HTTP_HOST"] == "localhost" ||
		$_SERVER["HTTP_HOST"] == "leo.local" ||
		$_SERVER["HTTP_HOST"] == "::1" ||
		strpos($_SERVER["HTTP_HOST"], '192.168.1') !== false
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
	
	mysqli_set_charset($db, "utf8")
?>