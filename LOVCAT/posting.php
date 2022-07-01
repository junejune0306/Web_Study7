<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	$post = 0;
	if ($r = mysqli_fetch_row(mysqli_query($conn, 'select seq, id, title from post where seq='.$_GET['postid'].' and (type=1 or type=11)'))) $post = $r[0];
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - posting</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/posting.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center>
		<div class="frame1">
			<form method="post" action="posting_process.php<?php echo "?postid=$post"; ?>" enctype="multipart/form-data">
					<h1>Posting</h1>
<?php if ($post) echo '<h2>Respond to ['.mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[1]'"))[0]." - $r[2]]</h2>"; ?>
					<label for="title">Title</label>
					<input type="text" id="title" name="title" placeholder="Write a title..." autofocus>
					
					<label for="content">Content</label>
					<textarea id="content" name="content" placeholder="Write a content..."></textarea>

					<div>add file: <input type='file' name='userfile'></div>
					
					<input type="checkbox" name="secret" value="1">secret
					<input type="submit" value="Post">
			</form>
		</div>
	</center></body>
</html>
<?php } ?>