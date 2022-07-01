<?php
    session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
    if ($l = mysqli_fetch_row(mysqli_query($conn, 'select status from likes where seq='.$_GET['id'].' and id=\''.$_SESSION['userinfo'][0].'\'')))
        mysqli_query($conn, 'update likes set status='.$_GET['to'].' where seq='.$_GET['id'].' and id=\''.$_SESSION['userinfo'][0].'\'');
    else mysqli_query($conn, 'insert into likes (seq, id, status) values ('.$_GET['id'].', \''.$_SESSION['userinfo'][0].'\', '.$_GET['to'].')');
    echo '<script>location.replace(document.referrer);</script>';
}?>