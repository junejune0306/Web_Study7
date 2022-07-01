<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - edit password</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/signup.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center>
		<div class="frame1">
			<form method="post" action="userinfo_pswd_check.php">
					<img id="catonboard" src="catonboard.png">
					<h1>Edit Password</h1>

					<label for="pswd">OLD PASSWORD</label>
					<input type="password" id="pswd" name="pswd" placeholder="Enter your old password">
                    
                    <label for="npswd">NEW PASSWORD</label>
					<input type="password" id="npswd" name="npswd" placeholder="(8~20 length, can use alphabet, number, _,?,!)">
					
					<label for="cpswd">CONFIRM PASSWORD</label>
					<input type="password" id="cpswd" name="cpswd" placeholder="Please confirm your password">
					
                    <input type="submit" value="Edit">
			</form>
		</div>
	</center></body>
</html>
<?php } ?>