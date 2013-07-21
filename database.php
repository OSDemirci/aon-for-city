<?php

$host="localhost";
$user="afcweb";
$dbPassword="";
$db = "Aon4City";

//echo getHashedPassword(1,'123');
/*
$mysqli = new mysqli($host, $user, $dbPassword, $db);
echo "test";
$result = $mysqli->query('CALL checkLogin(1,2);');
var_dump($result);
$row = $result->fetch_assoc();
var_dump($row);
$mysqli->close();
*/
//registerUser("Hande","8d82c6a05caa5992d7cc9989931bd978","st101101006@gmail.com","1992-07-07");
function getHashedPassword($uid,$phrase){
	$url = "https://aon4citysalter.com/?TYPE=GET&UID=$uid&PHRASE=$phrase";
	return file_get_contents($url);	
}
function addSalt($uid){
	$url = "https://aon4citysalter.com/?TYPE=ADD&UID=".$uid;
	return file_get_contents($url);
}
function checkLogin($loginName,$loginPassword){
	global $host,$user,$dbPassword,$db;
	$mysqli = new mysqli($host, $user, $dbPassword, $db);
	$uID = getUID(addslashes($loginName));
	$hashPass = getHashedPassword($uID,addslashes($loginPassword));
	$query = sprintf("CALL checkLogin('%s','%s');",addslashes($loginName),$hashPass);
	//var_dump($query);
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	return $row['Count'] == 1;
}

function getUID($loginName){
	global $host,$user,$dbPassword,$db;
	$mysqli = new mysqli($host, $user, $dbPassword, $db);
	$query = sprintf("CALL getUID('%s');",addslashes($loginName));
	$result = $mysqli->query($query);
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		return $row['uID'];	
	}
	return -1;
	
}

function activateAccount($userName){
	global $host,$user,$dbPassword,$db;
	$mysqli = new mysqli($host, $user, $dbPassword, $db);
	$query = sprintf("CALL validateUser('%s');",addslashes($userName));
	$mysqli->query($query);
}
function registerUser($uName,$uPassword,$uMail,$uBDay){
	if(getUID($uName) != -1){
		echo "User Exists";
		return -1;
	}
	global $host,$user,$dbPassword,$db;
	$mysqli = new mysqli($host, $user, $dbPassword, $db);
	$uName = addslashes($uName);
	$uPassword = addslashes($uPassword);
	$uMail = addslashes($uMail);
	$uBDay = addslashes($uBDay);
	$query = sprintf("CALL addUser('%s','%s','%s','%s')",$uName,$uMail,$uPassword,$uBDay);
	$result = $mysqli->query($query);
	$last_id = $result->fetch_assoc();
	$last_id = $last_id['uID'];
	addSalt($last_id);
	$hashPass = getHashedPassword($last_id,$uPassword);
	$query = sprintf("CALL updatePassword('%s','%s')",$uName,$hashPass);
	$mysqli2 = new mysqli($host, $user, $upassword, $db);
	$mysqli2 -> query($query);
}
function getEmail($uName){
	global $host,$user,$dbPassword,$db;
	$mysqli = new mysqli($host, $user, $dbPassword, $db);
	$uName = addslashes($uName);
	$query = sprintf("CALL getMail('%s')",addslashes($uName));
	$result = $mysqli -> query($query);
	$r = $result->fetch_assoc();
	return $r['email'];
}
function updatePassword($uName,$hashPass){
	global $host,$user,$dbPassword,$db;
	$query = sprintf("CALL updatePassword('%s','%s')",$uName,$hashPass);
	$mysqli = new mysqli($host, $user, $dbPassword, $db);
	$mysqli -> query($query);
}
function deleteUser($uName){
	global $host,$user,$dbPassword,$db;
	$query = sprintf("CALL deleteUser('%s')",$uName);
	$mysqli = new mysqli($host, $user, $dbPassword, $db);
	$mysqli -> query($query);
}

?>