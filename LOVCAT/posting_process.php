<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	
	mysqli_query($conn, 'insert into post(type, nick, id, title, content) values ('.(($_POST['secret'] == 1) ? 11 : 1).', \''.$_SESSION['userinfo'][1].'\', \''.$_SESSION['userinfo'][0].'\', \''.$_POST['title'].'\', \''.$_POST['content'].'\');');
	if (isset($_FILES['userfile'])) {
        $path = './upload/'.mysqli_fetch_row(mysqli_query($conn, "select max(seq) from post"))[0];
        $oldumask = umask(0);
        mkdir($path, 0777, true);
        umask($oldumask);
        move_uploaded_file($_FILES['userfile']['tmp_name'], $path.'/'.$_FILES['userfile']['name']);
    }
    header('Location: ./home.php');
}?>