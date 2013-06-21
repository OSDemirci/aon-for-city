<?php 
	session_start();

	if (!isset($_SESSION["AUTH_USER"])) {
		header("HTTP/1.1 401 Unauthorized");
		header("Location: login.php");
	}
	var_dump($_SESSION);
	session_destroy();

?>