<?php 
	session_start();

	if (isset($_SESSION["AUTH_USER"])) {
		header("Location: index.php");
	}
	include('header.php');
?>
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
		<div class="row-fluid">
			<div class="span4 offset3" style="background: #B3B3B3">
				<center style="font-size: 18;font-weight: bold">LOGIN</center>
				<div class="row-fluid">
					<div class="span4" >
						<input type="text" id="login_username"  name="username" title="Username" autocomplete="on" tabindex="1" placeholder="Username"/>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span4">
						<input type="password" id="login_password"  name="password" title="Password" autocomplete="on" tabindex="1" placeholder="Password"/>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row" >
			<div class="span 4 offset 3">
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
			</div>
		</div>
		<?php
			var_dump($_SESSION);
		?>
	</body>

</html>