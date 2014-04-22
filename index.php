<?php 
	session_start();

	if (!isset($_SESSION["AUTH_USER"])) {
		header("HTTP/1.1 401 Unauthorized");
		header("Location: login.php");
	}
	include('header.php');
?>
<script type="text/javascript" src="/lib/jquery.js"></script>
<script type="text/javascript" src="/lib/jQuery.dPassword.js"></script>
<script src="lib/md5.js"></script>
<script>
	$(document).ready( function() {     
			$('input:password').dPassword({       
				duration: 700,       
				prefix: 'myPass_'  
			});   
	}); 
	function logout(){
		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("POST", "control.php", false);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("requestType=logout");
		location.reload();
	}
	function showControls1(e){
		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("POST", "control.php", false);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("requestType=unlock");
    	e.style.display='none';
		document.getElementById('c1').style.display='';
		window.alert('An E-Mail Has Been Sent.Please Enter Password Here.');
		    
	}
	function showControls2(){
		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("POST", "control.php", false);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("requestType=unlock&PASS="+document.getElementById('txtVal').value);
    	if(xmlhttp.responseText == "OK"){
    		document.getElementById('c1').style.display='none';
    		document.getElementById('c2').style.display='';
    	}
	}

	function changePassword(){
		if(!testPassword(document.getElementById('newPass').value)){
			window.alert("Password Must Contain At Least 1 Lowercase , 1 Uppercase ,1 Number and Must Be Longer Than 12 Characters.");
			return -1;
		}
		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("POST", "control.php", false);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("requestType=changePassword&pass="+md5(document.getElementById('pass').value)+"&pass_rep="+
			md5(document.getElementById('pass_rep').value)+"&newPass="+md5(document.getElementById('newPass').value));
	}
	
	function deleteAccount(){
		if(confirm("You are about to delete your User.Delete means its gone forever(A very long time)!!.Are you sure? By clicking this button you aggree to ... Whatever in short \nPRESS ENTER TO DELETE")){
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("POST", "control.php", false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("requestType=deleteUser&pass="+md5(document.getElementById('pass').value)+"&pass_rep="+
			md5(document.getElementById('pass_rep').value));
		}
	}
	function testPassword(password){
		return (/[a-z]/.test(password) && /[A-Z]/.test(password) && /[0-9]/.test(password) && password.length >= 12 );
	}
</script>
WELCOME <?php echo $_SESSION["AUTH_USER"] ?> <br>
<input type="button" value="LOGOUT" onclick="logout()"/>
<div style="border-top:1px solid black;"/>
<h2>SETTINGS</h2>
<input type="button" id="c" value="Unlock Settings" onclick="showControls1(this)" />
<div style="display:none;" id="c1">
	Validation Code : <input type="text" id="txtVal" onkeydown="if(event.keyCode == 13) showControls2();" />
</div>
<div id ="c2" style="display:none;">
	<table>
		<tr>
			<td>PASSWORD :</td>
			<td> <input type="password" id="pass" /></td>
		</tr>
		<tr>
			<td>REPEAT : </td>
			<td><input type="password" id="pass_rep" /></td>
		</tr>
		<tr>
			<td>NEW PASSWORD : </td>
			<td><input type="password" id="newPass" /></td>
		</tr>
		<tr>
			<td><input type="button" value="Change Password" onclick="changePassword()" /></td>
			<td><input type="button" value="Delete Account"  onclick="deleteAccount()" /></td>
		</tr>
	</table>
	<br>
	

	
	
</div>
</body>
</html>
<?php
?>