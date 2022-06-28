<?php
	session_start();
	if (isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - find</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/login.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center>
		<div class="frame1">
			<form method="post" action="findpswd_check.php">
					<img id="catonboard" src="catonboard.png">
					<h1>Find Password</h1>

					<label for="email">Email</label>
					<input type="text" id="email" name="email" placeholder="Please enter your email to find password" autofocus>
					<?php
						if (!$_SESSION['findpswd']) echo '<br><span>No account associated with the email address</span>';
						else echo 'Password: '.$_SESSION['findpswd'];
					?>
					<input type="submit" value="Find Password">

					<a href="login.php">Back to Login</a>
			</form>
		</div>
	</center></body>
</html>
<?php } ?>