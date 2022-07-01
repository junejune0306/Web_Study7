<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	
    if (($comment = mysqli_fetch_row(mysqli_query($conn, 'select * from post where seq='.$_POST['parentid'].' and (type=2 or type=4 or type=12 or type=14)')))) {
        if ($comment[3] == $_SESSION['userinfo'][0]) {
            mysqli_query($conn, "update post set content='".$_POST['comment']."', updt=current_timestamp where seq=".$comment[0]);
        }
        header('Location: ./view.php?postid='.(($comment[1] % 10 == 2) ? $comment[8] : mysqli_fetch_row(mysqli_query($conn, 'select parent from post where seq='.$comment[8]))[0]));
    }
    else echo '<script>alert("Wrong Access");location.replace("./home.php");</script>';
}?>