<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
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
			<form method="post" action="posting_process.php" enctype="multipart/form-data">
					<h1>Posting</h1>

					<label for="title">Title</label>
					<input type="text" id="title" name="title" placeholder="Write a title..." autofocus>
					
					<label for="content">Content</label>
					<textarea id="content" name="content" placeholder="Write a content..."></textarea>

					add file: <input type='file' name='userfile'><br>
					
					<input type="checkbox" name="secret" value="1">secret
					<input type="submit" value="Post">
			</form>
		</div>
	</center></body>
</html>
<?php } ?>