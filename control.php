<?php
	if ($_GET["requestType"] == "login") {
		if ($_GET["username"] == "TEST" && $_GET["password"] == "PASSWORD") {
			session_start();
			$_SESSION["AUTH_USER"] = "TEST";
			echo "ACCESS GRANTED<br />";
		} else {
			var_dump($_GET);
			echo "ACCESS DENIED<br />";
		}
	} else if($_GET["requestType"] == "logout") {
		session_destroy();
		unset($_SESSION["AUTH_USER"]);
		echo "SESSION DESTROYED <br />";
	}
	var_dump($_SESSION);
?>