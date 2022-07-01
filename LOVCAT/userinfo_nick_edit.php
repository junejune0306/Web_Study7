<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - edit nickname</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/signup.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center>
		<div class="frame1">
			<form method="post" action="userinfo_nick_check.php">
					<img id="catonboard" src="catonboard.png">
					<h1>Edit Nickname</h1>

					<label for="nick">NICKNAME</label>
					<input type="text" id="nick" name="nick" value="<?php echo htmlspecialchars($_SESSION['userinfo'][1]); ?>" placeholder="(1~20 length, can't use space)" autocomplete="off">

					<input type="submit" value="Edit">
			</form>
		</div>
	</center></body>
</html>
<?php } ?>