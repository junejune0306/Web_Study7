<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	if ($r = mysqli_fetch_row(mysqli_query($conn, 'select seq, id, type, parent from post where seq='.$_GET['id'])) and $r[1] == $_SESSION['userinfo'][0]) {
        mysqli_query($conn, 'delete from post where seq='.$_GET['id']);
        if ($r[2] % 10 == 1 or $r[2] % 10 == 3) header('Location: ./home.php');
        else header('Location: ./view.php?postid='.(($r[2] % 10 == 2) ? $r[3] : mysqli_fetch_row(mysqli_query($conn, 'select parent from post where seq='.$r[3]))[0]));
    }
    else echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
}?>