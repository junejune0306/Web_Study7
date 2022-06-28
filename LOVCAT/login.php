<?php
	session_start();
	if (isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - login</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/login.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center>
		<div class="frame1">
			<form method="post" action="login_check.php">
					<img id="catonboard" src="catonboard.png">
					<h1>Login</h1><?php if ($_SESSION['fail']) echo '<br><span>Wrong ID or password</span>'; ?>

					<label for="id">ID</label>
					<input type="text" id="id" name="id" placeholder="Please enter your ID" autofocus>
					
					<label for="pswd">PASSWORD</label>
					<input type="password" id="pswd" name="pswd" placeholder="Please enter your password">
					
					<input type="submit" value="Login">

					<a href="signup.php">Sign Up</a>  |  <a href="findpswd.php">Find password</a>
			</form>
		</div>
	</center></body>
</html>
<?php } ?>