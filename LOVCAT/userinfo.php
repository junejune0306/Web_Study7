<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
    include('./dbinit.php');
    $user = mysqli_fetch_row(mysqli_query($conn, 'select * from userlist where id=\''.$_SESSION['userinfo'][0].'\''));
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - user info</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/userinfo.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center>
		<div class="frame1">
            <h1>User Information</h1>
<?php
    echo '<h1>'.htmlspecialchars($user[1]).' <a class="btn" href="userinfo_nick_edit.php">Edit Nickname</a></h1>';
    echo '<div>signup date: '.$user[5].'</div>';
    echo '<div>ID: '.$user[2].' <a class="btn" href="userinfo_pswd_edit.php">Edit Password</a></div>';
    echo '<div>Email: '.htmlspecialchars($user[4]).'</div>';
?>
            <br><br><br>
            <a class="btn" id="signout" href="./signout.php" onclick="return confirm('Are you sure you want to Sign Out?');">Sign Out</a>
        </div>
    </center></body>
</html>
<?php } ?>