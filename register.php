<?php 
	session_start();

	if (isset($_SESSION["AUTH_USER"])) {
		header("Location: index.php");
	}
	
	$captchaUrl = "http://www.opencaptcha.com/img/";
	$captchaD = md5((gmdate('U').rand(0,999999999)));
	$dateToday = gmdate('Y-m-d');
	include('header.php');
?>
<script src="lib/md5.js"></script>
<script type="text/javascript">
	function submitForm(){
		var formVals = new Array();
		var passwordOK = testPassword(formVals['pass']);
		formVals['uName']=document.getElementById("reg_username").value;
		formVals['email']=document.getElementById("reg_email").value;
		formVals['pass']=md5(document.getElementById("reg_password").value);
		formVals['pass_rep']=md5(document.getElementById("reg_password_rep").value);
		formVals['bd']=document.getElementById("reg_bd").value;
		formVals['captcha']=document.getElementById("reg_captcha").value;
		formVals['captchaD']=document.getElementById("reg_cpcd").value;

		var errors = new Array();

		if(formVals['uName'] == "")
			errors.push("Username Cannot Be Empty.");
		if(formVals['email'] == "")
			errors.push("E-Mail Cannot Be Empty.");
		else if(!validateEmail(formVals['email']))
			errors.push("E-Mail Is Not Valid.");
		if(formVals['pass'] == "" || formVals['pass_rep'] == "")
			errors.push("Passwords Cannot Be Empty.");
		else if(passwordOK)
			errors.push("Password Must Contain At Least 1 Lowercase , 1 Uppercase ,1 Number and Must Be Longer Than 12 Characters.");
		else if(formVals['pass'] != formVals['pass_rep'])
			errors.push("Passwords Does Not Match!");
		if(formVals['bd'] == "")
			errors.push("Birthday Cannot Be Empty.");
		if(formVals['captcha'] == "")
			errors.push("Captcha Cannot Be Empty.");
		
		if(errors.length != 0){
			var errorString = "";
			for (var i in errors)
				errorString += errors[i]+"\n";

			window.alert(errorString);
			return false;
		}

		var qString = "";
		for(var i in formVals){
			qString+=  "&"+i+"="+encodeURIComponent(formVals[i]);
		}

		xmlhttp = new XMLHttpRequest();
		xmlhttp.open("POST", "control.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("requestType=register"+qString);
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				if(xmlhttp.responseText == 'DENIED'){
					window.alert("Wrong Captcha Code");
					document.getElementById('captchaImg').src = document.getElementById('captchaImg').src;
				}else if(xmlhttp.responseText == 'GRANTED'){
					mhttp = new XMLHttpRequest();
					mhttp.open("POST","control.php",false);
					mhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					quString = "&rec="+formVals['email'];
					quString += "&sub=Authorization 4 Security Registration";
					quString += ""
					mhttp.send("requestType=sendMail");
					window.alert("Validation Email Has Been Sent.Please Click The Link In E-Mail.");
				}else if(xmlhttp.responseText.substr(0,5) == 'ERROR'){
					window.alert(xmlhttp.responseText.substr(6));
				}
		    }
		}


	}
	function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
	}

	function testPassword(password){
		return (/[a-z]/.test(password) && /[A-Z]/.test(password) && /[0-9]/.test(password) && password.length > 12 );
	}
</script>
<input type="hidden" id="reg_cpcd"  value="<?php echo $captchaD ?>"/>
<table>
	<tr>
		<td>Username:</td>
		<td><input type="text" id="reg_username"/></td>
	</tr>
	<tr>
		<td>E-Mail:</td>
		<td><input type="text" id="reg_email"/></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type="password" id="reg_password"/></td>
	</tr>
	<tr>
		<td>Password(Again):</td>
		<td><input type="password" id="reg_password_rep"/></td>
	</tr>
	<tr>
		<td colspan="2"><img id="captchaImg" height="80" width="272" src="<?php echo $captchaUrl.$captchaD.'.jpgx'?>"></td> 
	</tr>
	<tr>
		<td colspan="2"><a href="#" onclick="document.getElementById('captchaImg').src = document.getElementById('captchaImg').src">Refresh Captcha</a></td> 
	</tr>
	<tr>
		<td colspan="2"> Please Enter Captcha Above.<input type="text" style="width:90px" id="reg_captcha"></td>
	</tr>
	<tr>
		<td >Birthday</td>
		<td><input type="date" max="<?php echo $dateToday ?>" value="<?php echo $dateToday ?>" id="reg_bd"/></td>
	</tr>
	<tr>
		<td colspan="2"><input type="button" value="Register" onclick="submitForm()" /> </td>
	</tr>
</table>