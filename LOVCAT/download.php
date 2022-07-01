<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
    include('./dbinit.php');
    if (file_exists('upload/'.$_GET['id'])) {
        $r = mysqli_fetch_row(mysqli_query($conn, 'select type, id from post where seq='.$_GET['id']));
        if ($r[0] < 10 or $r[1] == $_SESSION['userinfo'][0]) {
			$folder = opendir('upload/'.$_GET['id']);
			while (($file = readdir($folder)) == '.' or $file == '..');
			closedir($folder);
            if ($file) {
                $path = "./upload/".$_GET['id']."/$file";
                $file_size = filesize($path);

                header("Pragma: public");
                header("expires: 0");
                header("Content-Type: application/octet-stream");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: ".$file_size);
                header("Content-Disposition: attachment; filename=\"$file\"");

                ob_clean();
                flush();
                readfile($path);
            }
            else echo '<script>alert("Can not find the file");</script>';
        }
        else echo '<script>alert("Wrong Access\nAccess is denied");</script>';
    }
    echo '<script>location.replace(document.referrer);</script>';
}?>
