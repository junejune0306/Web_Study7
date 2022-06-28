<?php
	session_start();
	if (isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	
	$result = mysqli_query($conn, 'select pswd, nick from userlist where id="'.$_POST['id'].'"');
	if ("" != $pswd = mysqli_fetch_row($result) and $pswd[0] == $_POST['pswd']) {
		$_SESSION = array();
		$_SESSION['userinfo'] = array($_POST['id'], $pswd[1]);
		header('Location: ./home.php');
	}
	else {
		$_SESSION['fail'] = 1;
		header('Location: ./login.php');
	}
}?>