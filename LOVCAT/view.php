<?php
	session_start();
	if (!isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	error_reporting( E_ALL );
	ini_set( "display_errors", 1 );
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
	$result = mysqli_query($conn, 'select * from post where seq=\''.$_GET['postid'].'\' and (type=1 or type=3 or ((type=11 or type=13) and id=\''.$_SESSION['userinfo'][0].'\'))');
	if ("" != $r = mysqli_fetch_row($result)) {
		/* post contents start */
		//views process
		if ("" == mysqli_fetch_row(mysqli_query($conn, 'select * from views where seq='.$r[0].' and session=\''.session_id().'\'')))
			mysqli_query($conn, 'insert into views (seq, session) values ('.$r[0].', \''.session_id().'\')');
		//get view count
		$v = mysqli_fetch_row(mysqli_query($conn, "select count(seq) from views where seq=$r[0]"))[0];
		//get writer's nickname
		$w = htmlspecialchars(mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[3]'"))[0]);
		//post head start
		echo '<h1>'.htmlspecialchars($r[6]);
		//if user is writer, show edit buttons
		if ($r[3] == $_SESSION['userinfo'][0]) {
			echo '<a class="btn" href="delete.php?id='.$r[0].'" onclick="return confirm(\'Are you sure you want to delete?\');">delete</a>';
			echo "<a class=\"btn\" href=\"edit.php?postid=$r[0]\">edit</a>";
		}
		//if post type is not respond, show respond button
		if ($r[1] % 10 == 1) echo "<a class=\"btn\" href=\"posting.php?postid=$r[0]\">respond</a>";
		//post head end
		echo '</h1>';
		//if post type is respond, show parent post info
		if ($r[1] % 10 == 3) {
			$p = mysqli_fetch_row(mysqli_query($conn, 'select id, title from post where seq='.$r[8].' and (type=1 or type=11)'));
			echo '<h2>Respond to ['.mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$p[0]'"))[0]." - $p[1]]</h2>";
		}
		//show post contents
		echo "<div class=\"writer\">$w</div>";
		echo "<div class=\"info\">$r[4]";
		echo ($r[5]) ? "(updated $r[5])" : "";
		echo " $v views</div>";
		echo '<div id="content">'.nl2br(htmlspecialchars($r[7])).'</div>';
		//file
		if (file_exists('upload/'.$r[0])) {
			$folder = opendir('upload/'.$r[0]);
			while (($file = readdir($folder)) == '.' or $file == '..');
			if ($file) echo '<a class="file '.substr($file, -3, 3).'" href="download.php?id='.$r[0].'">'.$file.'</a>';
			closedir($folder);
		}
		//like
		$lc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=1 and seq='.$r[0]))[0];
		$ulc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=2 and seq='.$r[0]))[0];
		$ls = mysqli_fetch_row(mysqli_query($conn, 'select status from likes where seq='.$r[0].' and id=\''.$_SESSION['userinfo'][0].'\''))[0];
		echo '<div id="like"><a href="like.php?id='.$r[0].'&to='.(($ls == 1) ? 0 : 1).'">'.$lc.'</a><a href="like.php?id='.$r[0].'&to='.(($ls == 2) ? 0 : 2).'">'.$ulc.'</a></div>';
		/* post contents end */
		?>
		<form id="comment_form" method="post" action="view_comment.php" enctype="multipart/form-data">
			<input type="hidden" id="parentid" name="parentid" value="<?php echo $r[0]; ?>">
			<a onclick="write_comment(<?php echo $r[0]; ?>, this)">Write comment</a>
<?php
	function show_comments($conn, $parent_id) { //showing comments function
		$result = mysqli_query($conn, "select * from post where parent=$parent_id and (type=2 or type=12)");
		while ($r = mysqli_fetch_row($result)) {
			/* comment start */
			echo "<div class=\"comment\">";
			//if comment type is secret, replace contents to '(secret comment)'
			if ($r[1] > 10 and $r[3] != $_SESSION['userinfo'][0]) echo '<div class="secret">(secret comment)</div>';
			else {
				//get writer's nickname
				$w = htmlspecialchars(mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[3]'"))[0]);
				//show contents
				echo '<div class="writer">'.$w.(($r[1] > 10) ? '<span class="secret"> (secret comment)</span>' : '').'</div>';
				echo "<div class=\"info\">$r[4]";
				echo ($r[5]) ? "(updated $r[5])" : "";
				echo "</div>";
				echo '<div class="content">'.nl2br(htmlspecialchars($r[7])).'</div>';
				//file
				if (file_exists('upload/'.$r[0])) {
					$folder = opendir('upload/'.$r[0]);
					while (($file = readdir($folder)) == '.' or $file == '..');
					if ($file) echo '<a class="file '.substr($file, -3, 3).'" href="download.php?id='.$r[0].'">'.$file.'</a>';
					closedir($folder);
				}
				//like
				$lc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=1 and seq='.$r[0]))[0];
				$ulc = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from likes where status=2 and seq='.$r[0]))[0];
				$ls = mysqli_fetch_row(mysqli_query($conn, 'select status from likes where seq='.$r[0].' and id=\''.$_SESSION['userinfo'][0].'\''))[0];
				echo '<div class="like"><a href="like.php?id='.$r[0].'&to='.(($ls == 1) ? 0 : 1).'">'.$lc.'</a><a href="like.php?id='.$r[0].'&to='.(($ls == 2) ? 0 : 2).'">'.$ulc.'</a></div>';
			}
			//show reply button
			echo "<a class=\"replybtn\" onclick=\"write_comment($r[0], this)\">reply</a> ";
			//if user is writer, show edit buttons
			if ($r[3] == $_SESSION['userinfo'][0]) {
				echo "<a class=\"editbtn\" onclick=\"edit_comment($r[0], this)\">edit</a> ";
				echo "<a class=\"editbtn\" href=\"delete.php?id=$r[0]\" onclick=\"return confirm('Are you sure you want to delete?');\">delete</a> ";
			}
			//replies
			if (0 < mysqli_fetch_row(mysqli_query($conn, "select count(*) from post where parent=$r[0] and (type=2 or type=12)"))[0]) {
				//show fold button
				echo '<a class="foldbtn" onclick="fold(this)"><img src="css/arrow.png"></a>';
				//reply list
				echo "<ul>";
				show_comments($conn, $r[0]); //recursion
				echo "</ul>";
			}
			/* comment end */
			echo "</div>";
		}
	}
	show_comments($conn, $r[0]);
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
	function fold(a) {
		a.onclick = function(){open(this)};
		a.firstChild.style = "transform:rotate(270deg);";
		a.nextSibling.style = "display: none;";
	}
	function open(a) {
		a.onclick = function(){fold(this)};
		a.firstChild.style = "";
		a.nextSibling.style = "";
	}
</script>
<?php } ?>