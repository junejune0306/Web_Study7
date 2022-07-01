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
	$result = mysqli_query($conn, 'select * from post where seq=\''.$_GET['postid'].'\' and (type=1 or type=3 or ((type=11 or type=13) and id=\''.$_SESSION['userinfo'][0].'\'))');
	if ("" != $r = mysqli_fetch_row($result)) {
		if ("" == mysqli_fetch_row(mysqli_query($conn, 'select * from views where seq='.$r[0].' and session=\''.session_id().'\'')))
			mysqli_query($conn, 'insert into views (seq, session) values ('.$r[0].', \''.session_id().'\')');
		$v = mysqli_fetch_row(mysqli_query($conn, "select count(seq) from views where seq=$r[0]"))[0];
		$w = htmlspecialchars(mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[3]'"))[0]);
		echo '<h1>'.htmlspecialchars($r[6]);
		if ($r[3] == $_SESSION['userinfo'][0]) {
			echo '<a class="btn" href="delete.php?id='.$r[0].'" onclick="return confirm(\'Are you sure you want to delete?\');">delete</a>';
			echo "<a class=\"btn\" href=\"edit.php?postid=$r[0]\">edit</a>";
		}
		if ($r[1] % 10 == 1) echo "<a class=\"btn\" href=\"posting.php?postid=$r[0]\">respond</a>";
		echo '</h1>';
		if ($r[1] % 10 == 3) {
			$p = mysqli_fetch_row(mysqli_query($conn, 'select id, title from post where seq='.$r[8].' and (type=1 or type=11)'));
			echo '<h2>Respond to ['.mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$p[0]'"))[0]." - $p[1]]</h2>";
		}
		echo "<div class=\"writer\">$w</div>";
		echo "<div class=\"info\">$r[4]";
		echo ($r[5]) ? "(updated $r[5])" : "";
		echo " $v views</div>";
		echo '<div id="content">'.nl2br(htmlspecialchars($r[7])).'</div>';
		if (file_exists('upload/'.$r[0])) {
			$folder = opendir('upload/'.$r[0]);
			while (($file = readdir($folder)) == '.' or $file == '..');
			if ($file) echo '<a class="file '.substr($file, -3, 3).'" href="download.php?id='.$r[0].'">'.$file.'</a>';
			closedir($folder);
		}
		$lc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=1 and seq='.$r[0]))[0];
		$ulc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=2 and seq='.$r[0]))[0];
		$ls = mysqli_fetch_row(mysqli_query($conn, 'select status from likes where seq='.$r[0].' and id=\''.$_SESSION['userinfo'][0].'\''))[0];
		echo '<div id="like"><a href="like.php?id='.$r[0].'&to='.(($ls == 1) ? 0 : 1).'">'.$lc.'</a><a href="like.php?id='.$r[0].'&to='.(($ls == 2) ? 0 : 2).'">'.$ulc.'</a></div>';
		?>
		<form id="comment_form" method="post" action="view_comment.php" enctype="multipart/form-data">
			<input type="hidden" id="parentid" name="parentid" value="<?php echo $r[0]; ?>">
			<a onclick="write_comment(<?php echo $r[0]; ?>, this)">Write comment</a>
<?php
		$result = mysqli_query($conn, "select * from post where parent=$r[0] and (type=2 or type=12)");
		while ($r = mysqli_fetch_row($result)) {
			echo "<div class=\"comment\">";
			if ($r[1] > 10 and $r[3] != $_SESSION['userinfo'][0]) echo '<div class="secret">(secret comment)</div>';
			else {
				$w = htmlspecialchars(mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[3]'"))[0]);
				echo '<div class="writer">'.$w.(($r[1] > 10) ? '<span class="secret"> (secret comment)</span>' : '').'</div>';
				echo "<div class=\"info\">$r[4]";
				echo ($r[5]) ? "(updated $r[5])" : "";
				echo "</div>";
				echo '<div class="content">'.nl2br(htmlspecialchars($r[7])).'</div>';
				if (file_exists('upload/'.$r[0])) {
					$folder = opendir('upload/'.$r[0]);
					while (($file = readdir($folder)) == '.' or $file == '..');
					if ($file) echo '<a class="file '.substr($file, -3, 3).'" href="download.php?id='.$r[0].'">'.$file.'</a>';
					closedir($folder);
				}
				$lc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=1 and seq='.$r[0]))[0];
				$ulc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=2 and seq='.$r[0]))[0];
				$ls = mysqli_fetch_row(mysqli_query($conn, 'select status from likes where seq='.$r[0].' and id=\''.$_SESSION['userinfo'][0].'\''))[0];
				echo '<div class="like"><a href="like.php?id='.$r[0].'&to='.(($ls == 1) ? 0 : 1).'">'.$lc.'</a><a href="like.php?id='.$r[0].'&to='.(($ls == 2) ? 0 : 2).'">'.$ulc.'</a></div>';
			}
			echo "<a class=\"replybtn\" onclick=\"write_comment($r[0], this)\">reply</a> ";
			if ($r[3] == $_SESSION['userinfo'][0]) {
				echo "<a class=\"editbtn\" onclick=\"edit_comment($r[0], this)\">edit</a> ";
				echo "<a class=\"editbtn\" href=\"delete.php?id=$r[0]\" onclick=\"return confirm('Are you sure you want to delete?');\">delete</a> ";
			}
			$reply = mysqli_query($conn, "select * from post where parent=$r[0] and (type=4 or type=14)");
			if ($reply->num_rows) {
				echo "<ul>";
				while ($re = mysqli_fetch_row($reply)) {
					echo "<li><div class=\"reply\">";
					if ($re[1] > 10 and $re[3] != $_SESSION['userinfo'][0]) echo '<div class="secret">(secret comment)</div>';
					else {
						$w = htmlspecialchars(mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$re[3]'"))[0]);
						echo '<div class="writer">'.$w.(($re[1] > 10) ? '<span class="secret"> (secret comment)</span>' : '').'</div>';
						echo "<div class=\"info\">$re[4]";
						echo ($re[5]) ? "(updated $re[5])" : "";
						echo "</div>";
						echo '<div class="content">'.nl2br(htmlspecialchars($re[7])).'</div>';
						if (file_exists('upload/'.$re[0])) {
							$folder = opendir('upload/'.$re[0]);
							while (($file = readdir($folder)) == '.' or $file == '..');
							if ($file) echo '<a class="file '.substr($file, -3, 3).'" href="download.php?id='.$re[0].'">'.$file.'</a>';
							closedir($folder);
						}
						$lc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=1 and seq='.$re[0]))[0];
						$ulc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=2 and seq='.$re[0]))[0];
						$ls = mysqli_fetch_row(mysqli_query($conn, 'select status from likes where seq='.$re[0].' and id=\''.$_SESSION['userinfo'][0].'\''))[0];
						echo '<div class="like"><a href="like.php?id='.$re[0].'&to='.(($ls == 1) ? 0 : 1).'">'.$lc.'</a><a href="like.php?id='.$re[0].'&to='.(($ls == 2) ? 0 : 2).'">'.$ulc.'</a></div>';
					}
					echo "<a class=\"replybtn\" onclick=\"write_comment($r[0], this)\">reply</a> ";
					if ($re[3] == $_SESSION['userinfo'][0]) {
						echo "<a class=\"editbtn\" onclick=\"edit_comment($re[0], this)\">edit</a> ";
						echo "<a class=\"editbtn\" href=\"delete.php?id=$re[0]\" onclick=\"return confirm('Are you sure you want to delete?');\">delete</a> ";
					}
					echo "</div></li>";
				}
				echo "</ul>";
			}
			echo "</div>";
		}
?>
			<div id="comment_box">
				<textarea id="comment" name="comment" placeholder="Write a comment..."></textarea>
				<div id="file">add file: <input type='file' name='userfile'></div><br>
				<input type="submit" id="box_btn" value="Comment">
				<span id="secret"><input type="checkbox" name="secret" value="1">secret</span>
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
		comment_form.action = "view_comment.php";
		a.parentNode.appendChild(comment_box);
		secret.style = file.style = "";
		box_btn.value = "Comment";
		comment.value = "";
		comment.placeholder = "Write a comment...";
		comment.focus();
	}
	function edit_comment(seq, a) {
		parentid.value = seq;
		comment_form.action = "view_edit.php";
		a.parentNode.appendChild(comment_box);
		secret.style = file.style = "display: none;";
		box_btn.value = "Edit";
		comment.value = a.parentNode.childNodes[2].innerHTML;
		comment.placeholder = "Edit a comment...";
		comment.focus();
	}
</script>
<?php } ?>