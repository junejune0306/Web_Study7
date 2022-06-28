<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	
    if (($parent = mysqli_fetch_row(mysqli_query($conn, 'select * from post where seq='.$_POST['parentid'])))) {
        mysqli_query($conn, 'insert into post(type, nick, id, title, content) values ('.(($_POST['secret'] == 1) ? 12 : 2).', \''.$_SESSION['userinfo'][1].'\', \''.$_SESSION['userinfo'][0].'\', \''.$parent[0].'\', \''.$_POST['comment'].'\');');
        if (isset($_FILES['userfile'])) {
            $path = './upload/'.mysqli_fetch_row(mysqli_query($conn, "select max(seq) from post"))[0];
            $oldumask = umask(0);
            mkdir($path, 0777, true);
            umask($oldumask);
            move_uploaded_file($_FILES['userfile']['tmp_name'], $path.'/'.$_FILES['userfile']['name']);
        }
        $seq = ($parent[1] % 10 == 1) ? $parent[0] : $parent[6];
        header("Location: ./view.php?postid=$seq");
    }
    else echo '<script>alert("Wrong Access");location.replace("./home.php");</script>';
}?>