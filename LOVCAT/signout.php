<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
    include('./dbinit.php');
    mysqli_query($conn, 'update userlist set nick=\'(deleted account)\', pswd=\'\' where id=\''.$_SESSION['userinfo'][0].'\'');
    $_SESSION = array();
    header('Location: ./home.php');
    }
?>