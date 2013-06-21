<?php 
	session_start();

	if (isset($_SESSION["AUTH_USER"])) {
		header("Location: index.php");
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title> Authorization For Security </title>
		<script type="text/javascript">
			function submitForm() {
				xmlhttp = new XMLHttpRequest();
				xmlhttp.open("POST", "control.php", true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				var username = document.getElementById("txt_username").value;
				var password = document.getElementById("txt_password").value;
				xmlhttp.send("requestType=login&username=" + username + "&password=" + password);
			}
		</script>
	</head>

	<body>
		<form name="authorization" id="testid" >
			USERNAME :
			<input type="text" name="username" id = "txt_username"/>
			<br />
			PASSWORD :
			<input type="password" name="password" id="txt_password" />
			<br />
			<input type="button" onclick="submitForm();" value="Submit"/>
			<input type="reset" />
		</form>
		<?php
			var_dump($_SESSION);
		?>
	</body>

</html>