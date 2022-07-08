<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	
    if (($parent = mysqli_fetch_row(mysqli_query($conn, 'select * from post where seq='.$_POST['parentid'])))) {
        $type = ($_POST['secret'] == 1) ? 12 : 2;
        mysqli_query($conn, 'insert into post(type, nick, id, content, parent) values ('.$type.', \''.$_SESSION['userinfo'][1].'\', \''.$_SESSION['userinfo'][0].'\', \''.$_POST['comment'].'\', '.$parent[0].');');
        if (isset($_FILES['userfile']) and !$_FILES['userfile']['error']) {
            $path = './upload/'.mysqli_fetch_row(mysqli_query($conn, "select max(seq) from post"))[0];
            $oldumask = umask(0);
            mkdir($path, 0777, true);
            umask($oldumask);
            move_uploaded_file($_FILES['userfile']['tmp_name'], $path.'/'.$_FILES['userfile']['name']);
        }
        echo '<script>location.replace(document.referrer);</script>';
    }
    else echo '<script>alert("Wrong Access");location.replace("./home.php");</script>';
}?>