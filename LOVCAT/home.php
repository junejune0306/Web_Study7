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
	<body><center><?php if (!isset($_SESSION['userinfo'])) echo '<h1>LOV CAT</h1>'; else { ?>
		<div class="frame1">
			<h1>search bar place</h1>
			<table class="list">
				<tr class="header">
					<td>title</td>
					<td>writer</td>
					<td>date</td>
					<td>view</td>
				</tr>
<?php
	include('./dbinit.php');
	$result = mysqli_query($conn, 'select seq, title, id, dt from post where type=1 or (type=11 and id=\''.$_SESSION['userinfo'][0].'\') order by seq desc');
	while ($r = mysqli_fetch_row($result)) {
		$c = mysqli_fetch_row(mysqli_query($conn, "select count(title) from post where title='$r[0]' and (type=2 or type=12)"))[0];
		$v = mysqli_fetch_row(mysqli_query($conn, "select count(seq) from views where seq=$r[0]"))[0];
		$w = mysqli_fetch_row(mysqli_query($conn, "select nick from userlist where id='$r[2]'"))[0];
		echo "<tr><td><a href=\"view.php?postid=$r[0]\">$r[1]</a></td><td>$w</td><td>$r[3]</td><td>$v</td></tr>";
	}
?>
			</table>
			<a href="posting.php" class="btn">add post</a><br>
			<h1>paging place</h1>
		</div>
	<?php } ?></center></body>
</html>