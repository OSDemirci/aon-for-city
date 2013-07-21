<?php 
	session_start();

	if (isset($_SESSION["AUTH_USER"])) {
		header("Location: index.php");
	}
	include('header.php');
?>
		<script type="text/javascript" src="/lib/jquery.js"></script>
		<script src="lib/md5.js"></script>
		
		<script type="text/javascript" src="/lib/jQuery.dPassword.js"></script>
		<script type="text/javascript">   
		$(document).ready( function() {     
			$('input:password').dPassword({       
				duration: 700,       
				prefix: 'myPass_'  
			});   
		}); 
		</script>
		<script type="text/javascript">
			function checkEnter() {
				if (event.keyCode == 13) 
					submitForm();
			}
			function submitForm() {
				xmlhttp = new XMLHttpRequest();
				xmlhttp.open("POST", "control.php", true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				var username = document.getElementById("txt_username").value;
				var password = md5(document.getElementById("txt_password").value);
				xmlhttp.send("requestType=login&username=" + username + "&password=" + password);
				xmlhttp.onreadystatechange=function(){
					if (xmlhttp.readyState==4 && xmlhttp.status==200){
				    	if(xmlhttp.responseText == "ACCESS GRANTED")
				    		location.reload();
				    	else if(xmlhttp.responseText == "ACCESS DENIED")
				    		window.location = '?wrong=1';
				    }
				}
			}
		</script>
	</head>
	<body>
		<?php
			if(isset($_GET['wrong'])){
				echo '<span style="color:white;background-color:red;width:auto;padding:20px 50px 20px 50px;display: inline-block;border-radius:10px;margin-left:50px;">Incorrect Login Credentials</span>';
			}
		 ?>


		USERNAME :
		<input type="text" name="username" id = "txt_username" style="width:250px;" onkeydown="checkEnter();" />
		<br />
		PASSWORD :
		<input type="password" name="password" id="txt_password" onkeydown="checkEnter();" />
		<input type="button" onclick="submitForm();" value="Submit" style="width:95px" />
		<br>
		<a href ="register.php" style="margin-left:100px;margin-right:20px;">Register<a/>
		<a href="recover.php">Forgot Password<a/>
		<?php

		?>
	</body>

</html>