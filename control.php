<?php
	require_once('lib/mailer.php');
	require_once('database.php');
	#DBUSER webuser
	#DBPASSWORD DNM6NPBwjRAdRP

	if (isset($_POST["requestType"]) && $_POST["requestType"] == "login") {
		if (isset($_POST['username']) && isset($_POST['password']) && checkLogin($_POST['username'],$_POST['password'])==true) {
			session_start();
			$_SESSION["AUTH_USER"] = $_POST['username'];
			echo "ACCESS GRANTED";
		} else {
			echo "ACCESS DENIED";
		}
	} else if(isset($_POST["requestType"]) && $_POST["requestType"] == "logout") {
		session_start();
		unset($_SESSION["AUTH_USER"]);
		session_unset();
		session_destroy();
		echo "SESSION DESTROYED <br />";
	}else if(isset($_POST["requestType"]) && $_POST["requestType"] == "register"){
		session_start();
		$flink = "http://www.opencaptcha.com/validate.php?ans=".$_POST['captcha']."&img=".$_POST['captchaD'].".jpgx";
		$fcontent = file_get_contents($flink);
		if($fcontent=='pass') {
			echo "GRANTED";
			if(registerUser($_POST['uName'],$_POST['pass'],$_POST['email'],$_POST['bd']) == -1){
				echo "USER EXISTS";
				return -1;
			}
			$_SESSION['VAL_PASS'] = getValPass();
			$Mail['Receiver'] = $_POST['email'];
			$Mail['Subject'] = "Welcome To Authorization 4 Security";
			$Mail['Body'] = "Hello ".$_POST['uName']." ,<br>Welcome To Authorization 4 Security<br> Please Click Following".
			"Link To Activate Your Account: <br> <a href='https://aon4city.com/control.php?requestType=validate&pass=".
			$_SESSION['VAL_PASS']."&uName=".$_POST['uName']."'>https://aon4city.com/control.php?requestType=validate&pass=".
			$_SESSION['VAL_PASS']."&uName=".$_POST['uName']."</a>";
			sendMail($Mail['Receiver'],$Mail['Subject'],$Mail['Body']);
		}else if($fcontent == 'fail'){
			echo "DENIED";
		}
		

	}else if(isset($_GET["requestType"]) && $_GET["requestType"] == "validate"){
		session_start();
		if($_GET['pass'] == $_SESSION['VAL_PASS']){
			activateAccount($_GET['uName']);
			echo "Your Account Has Been Activated<br>You Can Login From <a href='login.php'> Here </a>";
			unset($_SESSION['VAL_PASS']);
		}
	}else if(isset($_POST["requestType"]) && $_POST["requestType"] == "changePassword"){
		session_start();
		if(isset($_SESSION['unlocked'])){
			if($_POST['pass'] == $_POST['pass_rep']){
			$newPass = $_POST["newPass"];
			$uName = $_SESSION["AUTH_USER"];
			$uID = getUID($uName);
			$hashPass = getHashedPassword($uID,$newPass);
			updatePassword($uName,$hashPass);
			echo "OK";
			unset($_SESSION["unlocked"]);
			}
		}else
			echo "DN";
	}else if(isset($_POST["requestType"]) && $_POST["requestType"] == "deleteUser"){
		session_start();
		if(isset($_SESSION['unlocked'])){
			if($_POST['pass'] == $_POST['pass_rep']){
			$newPass = $_POST["newPass"];
			$uName = $_SESSION["AUTH_USER"];
			deleteUser($uName);
			unset($_SESSION["unlocked"]);
			session_unset();
			session_destroy();
			//¡Adiós! Amigo.
			}
		}
	}else if(isset($_POST["requestType"]) && $_POST["requestType"] == "unlock"){
		session_start();
		if(!isset($_SESSION["VAL_PASS"]) || ( isset($_SESSION['VAL_PASS']) && !isset($_POST['PASS']))) {
			$_SESSION['VAL_PASS'] = getValPass();
			$Mail['Receiver'] = getEmail($_SESSION['AUTH_USER']);
			$Mail['Subject'] = "Validation Password";
			$Mail['Body'] = "Hello ".$_SESSION['AUTH_USER']." ,<br>Please Enter Validation Password To Website<br>".
			"Validation Password Is: <p><h2>".$_SESSION['VAL_PASS']."</h2></p>";
			sendMail($Mail['Receiver'],$Mail['Subject'],$Mail['Body']);
		}
	else if($_POST['PASS'] == $_SESSION['VAL_PASS']){
			$_SESSION["unlocked"] = 1;
			unset($_SESSION['VAL_PASS']);
			echo "OK";
		}
	else{
			echo "DN";
		}
	}

	function getValPass(){
	$charset = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H',
			'I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$c = "";
	for ($i=0; $i < 8; $i++) { 
		$c = $c . $charset[array_rand($charset,1)];
	}
	return $c;
}
?>