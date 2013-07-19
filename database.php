<?php

$host="localhost";
$user="afcweb";
$password="";
$db = "Aon4City";



echo '<br>';
echo '<br>';
echo 'test';
echo '<br>';

$mysqli = new mysqli($host, $user, $password, $db);
echo "test";
$result = $mysqli->query('SELECT * FROM Test');
var_dump($result);
$row = $result->fetch_assoc();
var_dump($row);
$mysqli->close();

function generateSalt(){
	$c = "";
	for ($i=0; $i < 16; $i++) { 
		$c = $c . array_rand($charset,1);
	}
	return $c;
}

echo '<br>';
echo '<br>';
echo 'test123';
echo '<br>';
function checkLogin($loginName,$loginPassword){
	
}

?>