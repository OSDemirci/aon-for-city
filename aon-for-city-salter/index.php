<?php 

$host="localhost";
$user="afcweb";
$password="";
$db = "Aon4CitySalter";
$trustedServers = array('127.0.0.1');



if (in_array($_SERVER['REMOTE_ADDR'],$trustedServers)){
	header('HTTP/1.0 404 Not Found');
}
else if($_GET["TYPE"] == "GET"){
	echo getHash($_GET["UID"],$_GET["PHRASE"]);
}
else if($_GET["TYPE"] == "ADD"){
	echo addSalt($_GET["UID"]);
}

function getHash($uid,$phrase){
	global $host,$user,$password,$db;
	$rtrn = "";
	if(is_numeric($uid)){
	$mysqli = new mysqli($host, $user, $password, $db);
	$mysqli->select_db($db);
		$result = $mysqli->query("CALL getSalt($uid)");
		$row = $result->fetch_assoc();
		$slt = $row["salt"];
		$mysqli->close();
	}
	if(empty($slt)){
		return "";
	}
	return iterativeSaltedHash($phrase,$slt);
}
function addSalt($uid){
	global $host,$user,$password,$db;
	$slt = generateSalt();
	if(is_numeric($uid)){
		$mysqli = new mysqli($host, $user, $password, $db);
		$mysqli->query("CALL addSalt($uid,'$slt')");
		$mid = $mysqli->error;
		$mysqli->close();
		if(strlen($mid)==0){
			return "SUCCESS";
		}
		else{
			return "FAILURE";
		}
	}
}

function generateSalt(){
	$charset = array('!','#','%','&','(',')','*','+',',','-','.','/','0','1','2','3','4','5',
			'6','7','8','9',':',';','<','=','>','?','@','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P',
			'Q','R','S','T','U','V','W','X','Y','Z','[',']','^','_','`','a','b','c','d','e','f','g','h','i','j','k','l',
			'm','n','o','p','q','r','s','t','u','v','w','x','y','z','{','|','}','~');
	$c = "";
	for ($i=0; $i < 32; $i++) { 
		$c = $c . $charset[array_rand($charset,1)];
	}
	return $c;
}
/*
	Hashes password 1 000 000 times with salt
	salt applies every 20th iteration
*/
function iterativeSaltedHash($phrase,$salt){
	$hashed = $phrase;
	for ($i=0; $i < 1000000; $i++) { 
		if($i%20 == 0){
			$hashed = $hashed ^ $salt;
		}
		$hashed = md5($hashed);
	}
	return $hashed;
}
?>