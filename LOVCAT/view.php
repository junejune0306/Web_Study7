<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - view</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/view.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center>
		<div class="frame1">
<?php
	include('./dbinit.php');
	$result = mysqli_query($conn, 'select * from post where seq=\''.$_GET['postid'].'\' and (type=1 or (type=11 and id=\''.$_SESSION['userinfo'][0].'\'))');
	if ("" != $r = mysqli_fetch_row($result)) {
		if ("" == mysqli_fetch_row(mysqli_query($conn, 'select * from views where seq='.$r[0].' and session=\''.session_id().'\'')))
			mysqli_query($conn, 'insert into views (seq, session) values ('.$r[0].', \''.session_id().'\')');
		$v = mysqli_fetch_row(mysqli_query($conn, "select count(seq) from views where seq=$r[0]"))[0];
		$w = mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[3]'"))[0];
		echo "<h1>$r[6]</h1>";
		echo "<div class=\"writer\">$w</div>";
		echo "<div class=\"info\">$r[4]";
		echo ($r[5]) ? "(updated $r[5])" : "";
		echo " $v views</div>";
		echo "<div id=\"content\">$r[7]</div>";
		?>
		<form method="post" action="view_comment.php">
			<input type="hidden" id="parentid" name="parentid" value="<?php echo $r[0]; ?>">
			<a onclick="write_comment(<?php echo $r[0]; ?>, this)">Write comment</a>
<?php
		$result = mysqli_query($conn, "select * from post where title='$r[0]' and (type=2 or type=12)");
		while ($r = mysqli_fetch_row($result)) {
			echo "<div class=\"comment\">";
			if ($r[1] < 10) {
				$w = mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[3]'"))[0];
				echo "<div class=\"writer\">$w</div>";
				echo "<div class=\"info\">$r[4]";
				echo ($r[5]) ? "(updated $r[5])" : "";
				echo "</div>";
				echo "<div class=\"content\">$r[7]</div>";
				echo "<a class=\"replybtn\" onclick=\"write_comment($r[0], this)\">reply</a> ";
				if ($r[3] == $_SESSION['userinfo'][0]) {
					echo "<a class=\"editbtn\" onclick=\"edit_comment($r[0], this)\">edit</a> ";
					echo "<a class=\"editbtn\" href=\"delete.php?id=$r[0]\" onclick=\"return confirm('Are you sure you want to delete?');\">delete</a> ";
				}
				$reply = mysqli_query($conn, "select * from post where title='$r[0]' and (type=2 or type=12)");
				if ($reply->num_rows) {
					echo "<ul>";
					while ($re = mysqli_fetch_row($reply)) {
						echo "<li><div class=\"reply\">";
						if ($re[1] < 10) {
							$w = mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$re[3]'"))[0];
							echo "<div class=\"writer\">$w</div>";
							echo "<div class=\"info\">$re[4]";
							echo ($re[5]) ? "(updated $re[5])" : "";
							echo "</div>";
							echo "<div class=\"content\">$re[7]</div>";
							echo "<a class=\"replybtn\" onclick=\"write_comment($r[0], this)\">reply</a> ";
							if ($re[3] == $_SESSION['userinfo'][0]) {
								echo "<a class=\"editbtn\" onclick=\"edit_comment($re[0], this)\">edit</a> ";
								echo "<a class=\"editbtn\" href=\"delete.php?id=$re[0]\" onclick=\"return confirm('Are you sure you want to delete?');\">delete</a> ";
							}
						}
						else echo '<div class="secret">secret comment</div>';
						echo "</div></li>";
					}
					echo "</ul>";
				}
			}
			else echo '<div class="secret">secret comment</div>';
			echo "</div>";
		}
?>
			<div id="comment_box">
				<textarea id="comment" name="comment" placeholder="Write a comment..."></textarea>
				add file: <input type='file' name='userfile'><br>
				<input type="submit" value="Comment">
				<input type="checkbox" name="secret" value="1">secret
			</div>
		</form>
<?php
	} else echo '<h1>Post doesn\'t exist.</h1>';
?>
		</div>
	</center></body>
</html>
<script>
	function write_comment(seq, a) {
		parentid.value = seq;
		a.parentNode.appendChild(comment_box);
		comment_box.childNodes[1].focus();
	}
</script>
<?php } ?>