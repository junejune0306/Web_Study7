<?php
	session_start();
	if (isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - sign up</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/signup.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center>
		<div class="frame1">
			<form method="post" action="signup_check.php">
					<img id="catonboard" src="catonboard.png">
					<h1>Sign up</h1>

					<label for="nick">NICKNAME</label>
					<input type="text" id="nick" name="nick" <?php
						echo 'value="'.$_SESSION['input'][0].'" ';
						if ($_SESSION['check'][0]) echo 'class="wrong" ';
					?>placeholder="(1~20 length, can't use space)" autocomplete="off">
					
					<label for="id">ID</label>
					<input type="text" id="id" name="id" <?php
						echo 'value="'.$_SESSION['input'][1].'" ';
						if ($_SESSION['check'][1]) echo 'class="wrong" ';
					?>placeholder="(6~20 length, can use alphabet, number, '_')" autocomplete="off">
					
					<label for="pswd">PASSWORD</label>
					<input type="password" id="pswd" name="pswd" <?php
						echo 'value="'.$_SESSION['input'][2].'" ';
						if ($_SESSION['check'][2]) echo 'class="wrong" ';
					?>placeholder="(8~20 length, can use alphabet, number, _,?,!)">
					
					<label for="cpswd">CONFIRM PASSWORD</label>
					<input type="password" id="cpswd" name="cpswd" <?php
						echo 'value="'.$_SESSION['input'][3].'" ';
						if ($_SESSION['check'][3]) echo 'class="wrong" '
					?>placeholder="Please confirm your password">

					<label for="email">Email</label>
					<input type="text" id="email" name="email" <?php
						echo 'value="'.$_SESSION['input'][4].'" ';
						if ($_SESSION['check'][4]) echo 'class="wrong" ';
					?>placeholder="Please enter your email" autocomplete="off">
				
					<input type="submit" value="Sign Up">
			</form>
		</div>
	</center></body>
</html>
<?php } ?>