<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	
    if ($post = mysqli_fetch_row(mysqli_query($conn, 'select * from post where seq='.$_GET['postid'].' and (type=1 or type=3 or type=11 or type=13)')) and $post[3] == $_SESSION['userinfo'][0]) {
        mysqli_query($conn, "update post set title='".$_POST['title']."', content='".$_POST['content']."', updt=current_timestamp where seq=".$post[0]);
        header('Location: ./view.php?postid='.$post[0]);
    }
    else echo '<script>alert("Wrong Access");location.replace("./home.php");</script>';
}?>