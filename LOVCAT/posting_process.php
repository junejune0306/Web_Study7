<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	
    if ($r = mysqli_fetch_row(mysqli_query($conn, 'select seq from post where seq='.$_GET['postid'].' and (type=1 or type=11)'))[0]) {
        $t = 3;
    }
    else {
        $r = 0;
        $t = 1;
    }
	mysqli_query($conn, 'insert into post(type, nick, id, title, content, parent) values ('.(($_POST['secret'] == 1) ? $t + 10 : $t).', \''.$_SESSION['userinfo'][1].'\', \''.$_SESSION['userinfo'][0].'\', \''.$_POST['title'].'\', \''.$_POST['content'].'\', '.$r.');');
	if (isset($_FILES['userfile']) and !$_FILES['userfile']['error']) {
        $path = './upload/'.mysqli_fetch_row(mysqli_query($conn, 'select max(seq) from post'))[0];
        $oldumask = umask(0);
        mkdir($path, 0777, true);
        umask($oldumask);
        move_uploaded_file($_FILES['userfile']['tmp_name'], $path.'/'.$_FILES['userfile']['name']);
    }
    header('Location: ./home.php');
}?>