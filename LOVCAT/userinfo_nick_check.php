<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');

    if (preg_match('/^[[:graph:]가-힣]{1,20}$/', $_POST['nick'])) {
        if (mysqli_fetch_row(mysqli_query($conn, 'select * from userlist where nick=\''.$_POST['nick'].'\'')))
            echo '<script>alert("Already used nickname!");location.replace("userinfo_nick_edit.php");</script>';
        else {
            mysqli_query($conn, 'update userlist set nick=\''.$_POST['nick'].'\' where nick=\''.$_SESSION['userinfo'][1].'\'');
            $_SESSION['userinfo'][1] = $_POST['nick'];
            echo '<script>alert("Successfully changed");location.replace("userinfo.php");</script>';
        }
    }
    else echo '<script>alert("Wrong nickname");location.replace("userinfo_nick_edit.php");</script>';
}?>