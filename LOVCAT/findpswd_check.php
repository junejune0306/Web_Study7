<?php
	session_start();
	if (isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	
	$result = mysqli_query($conn, 'select pswd from userlist where email="'.$_POST['email'].'"');
	$_SESSION['findpswd'] = mysqli_fetch_row($result)[0];
	header('Location: ./findpswd.php');
	}
?>