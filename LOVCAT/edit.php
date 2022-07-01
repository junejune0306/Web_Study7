<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	if ($r = mysqli_fetch_row(mysqli_query($conn, 'select seq, id, title, content from post where seq='.$_GET['postid'].' and (type=1 or type=3 or type=11 or type=13)')) and $r[1] == $_SESSION['userinfo'][0]) {
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - post edit</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/posting.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center>
		<div class="frame1">
			<form method="post" action="edit_process.php<?php echo "?postid=$r[0]"; ?>" enctype="multipart/form-data">
					<h1>Editing</h1>
                    <?php echo '<h2>Editing ['.mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[1]'"))[0]." - ".htmlspecialchars($r[2])."]</h2>"; ?>
					<label for="title">Title</label>
					<input type="text" id="title" name="title" placeholder="Edit a title..." autofocus value="<?php echo htmlspecialchars($r[2]); ?>">
					
					<label for="content">Content</label>
					<textarea id="content" name="content" placeholder="Edit a content..."><?php echo htmlspecialchars($r[3]); ?></textarea>

					<input type="submit" value="Edit">
			</form>
		</div>
	</center></body>
</html>
<?php
    } else echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
} ?>