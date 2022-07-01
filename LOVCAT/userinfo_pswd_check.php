<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
    if ($_POST['pswd'] != mysqli_fetch_row(mysqli_query($conn, 'select pswd from userlist where id=\''.$_SESSION['userinfo'][0].'\''))[0])
        echo '<script>alert("Wrong password");location.replace("userinfo_pswd_edit.php");</script>';
    else if (!preg_match('/^[[:alnum:]\_\?\!]{8,20}$/', $_POST['npswd']))
        echo '<script>alert("Please make sure your password is correct in the form");location.replace("userinfo_pswd_edit.php");</script>';
    else if ($_POST['npswd'] != $_POST['cpswd'])
        echo '<script>alert("Password confirm is incorrect");location.replace("userinfo_pswd_edit.php");</script>';
    else {
        mysqli_query($conn, 'update userlist set pswd=\''.$_POST['npswd'].'\' where id=\''.$_SESSION['userinfo'][0].'\'');
        echo '<script>alert("Successfully changed");location.replace("userinfo.php");</script>';
    }
}?>