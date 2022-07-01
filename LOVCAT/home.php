<?php
	session_start();
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>LOVCAT - home</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/home.css">
		<?php include('navigation_bar.php'); ?>
	</head>
	<body><center><?php 
		if (!isset($_SESSION['userinfo'])) { ?>
		<iframe width="560" height="315" src="https://www.youtube.com/embed/a6Tvy1tubRs?autoplay=1&loop=1&playlist=a6Tvy1tubRs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		<?php } else { 
			if (isset($_POST['search'])) $_SESSION['search'] = $_POST['search'];
			if (isset($_POST['pagesize'])) $_SESSION['pagesize'] = $_POST['pagesize'];
		?>
		<div class="frame1">
			<div class="postbtn"><a href="posting.php" class="btn">add post</a></div>
			<form id="search_bar" method="post">
				page size: <select name="pagesize">
					<option value="5"<?php if ($_SESSION['pagesize'] == 5) echo 'selected'; ?>>5
					<option value="10"<?php if ($_SESSION['pagesize'] == 10) echo 'selected'; ?>>10
					<option value="15"<?php if ($_SESSION['pagesize'] == 15) echo 'selected'; ?>>15
					<option value="20"<?php if ($_SESSION['pagesize'] == 20) echo 'selected'; ?>>20
				</select>
				<input type="text" name="search" placeholder="Search..." value="<?php echo $_SESSION['search']; ?>">
				<input type=submit value="Search">
			</form>
			<table class="list">
				<tr class="header">
					<td>title</td>
					<td>writer</td>
					<td>date</td>
					<td>view</td>
				</tr>
<?php
	include('./dbinit.php');
	$p = (round($_GET['page']) > 1) ? $_GET['page'] - 1 : 0;
	$ps = ($_SESSION['pagesize'] > 0) ? $_SESSION['pagesize'] : 10;
	$result = mysqli_query($conn, 'select seq, title, id, dt from post where '.(($_SESSION['search']) ? 'title like \'%'.$_SESSION['search'].'%\' and ' : '').'(type=1 or (type=11 and id=\''.$_SESSION['userinfo'][0]."')) order by seq desc limit ".($p++*$ps).", $ps");
	while ($r = mysqli_fetch_row($result)) {
		$comment = mysqli_query($conn, "select seq from post where parent=$r[0] and (type=2 or type=12)");
		$c = $comment->num_rows;
		while ($co = mysqli_fetch_row($comment)[0]) $c += mysqli_fetch_row(mysqli_query($conn, "select count(*) from post where parent=$co and (type=4 or type=14)"))[0];
		$v = mysqli_fetch_row(mysqli_query($conn, "select count(*) from views where seq=$r[0]"))[0];
		$w = htmlspecialchars(mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[2]'"))[0]);
		echo "<tr><td><a href=\"view.php?postid=$r[0]\">".htmlspecialchars($r[1])."</a>".(($c) ? " <span>[$c]</span>" : '')."</td><td>$w</td><td>".htmlspecialchars($r[3])."</td><td>$v</td></tr>";
		$respond = mysqli_query($conn, "select seq, title, id, dt from post where parent=$r[0] and (type=3 or (type=13 and id='".$_SESSION['userinfo'][0]."')) order by seq desc");
		if ($respond->num_rows) {
			while ($re = mysqli_fetch_row($respond)) {
				$comment = mysqli_query($conn, "select seq from post where parent=$re[0] and (type=2 or type=12)");
				$c = $comment->num_rows;
				while ($co = mysqli_fetch_row($comment)[0]) $c += mysqli_fetch_row(mysqli_query($conn, "select count(*) from post where parent=$co and (type=4 or type=14)"))[0];
				$v = mysqli_fetch_row(mysqli_query($conn, "select count(*) from views where seq=$re[0]"))[0];
				$w = htmlspecialchars(mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$re[2]'"))[0]);
				echo "<tr><td><a href=\"view.php?postid=$re[0]\"> └ ".htmlspecialchars($re[1])."</a>".(($c) ? " <span>[$c]</span>" : '')."</td><td>$w</td><td>".htmlspecialchars($re[3])."</td><td>$v</td></tr>";
			}
		}
	}
?>
			</table><br>
			<div id="paging">
<?php
	$c = mysqli_fetch_row(mysqli_query($conn, 'select count(*) from post where '.(($_SESSION['search']) ? 'title like \'%'.$_SESSION['search'].'%\' and ' : '').'(type=1 or (type=11 and id=\''.$_SESSION['userinfo'][0].'\'))'))[0];
	$pc = ceil($c / $ps);
	$pe = ($p - 5 > 0) ? $p + 5 : 11;
	if ($pe > 11) echo '<a href="?page=1">&lt;&lt;</a>';
	if ($p > 1) echo '<a id="left" href="?page='.($p - 1).'">&lt;</a>';
	$i = ($p + 5 < $pc) ? $p - 6 : $pc - 11;
	while (++$i <= $pe and $i <= $pc) {
		if ($i > 0) echo '<a'.(($i == $p) ? ' class="page_now"' : '').' href="?page='.$i.'">'.$i.'</a>';
	}
	if ($p < $pc) echo '<a id="right" href="?page='.($p + 1).'">&gt;</a>';
	if ($pe < $pc) echo '<a href="?page='.$pc.'">&gt;&gt;</a>';
?>
			</div>
		</div>
	<?php } ?></center></body>
</html>