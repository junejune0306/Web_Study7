<?php
	session_start();
	if (isset($_SESSION['userinfo'])) echo '<script>alert("Wrong Access\nAccess is denied");location.replace("./home.php");</script>';
	else {
	include('./dbinit.php');
	$_SESSION['input'] = array($_POST['nick'], $_POST['id'], $_POST['pswd'], $_POST['cpswd'], $_POST['email']);

	$check = 0;
	$_SESSION['check'] = array(0,0,0,0,0);
	$check += $_SESSION['check'][0] += 1 - preg_match('/^[[:graph:]가-힣]{1,20}$/', $_POST['nick']);
	$check += $_SESSION['check'][1] += 1 - preg_match('/^[[:alnum:]\_]{6,20}$/', $_POST['id']);
	$check += $_SESSION['check'][2] += 1 - preg_match('/^[[:alnum:]\_\?\!]{8,20}$/', $_POST['pswd']);
	if ($_POST['pswd'] != $_POST['cpswd']) $check += $_SESSION['check'][3] += 1;
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $check += $_SESSION['check'][4] += 1;

	if ($check) header('Location: ./signup.php');
	else {
		$result = mysqli_query($conn, 'select nick, id, email from userlist');
		while ($row = mysqli_fetch_row($result)) {
			if ($_POST['nick'] == $row[0]) {
				echo '<script>alert("This nickname is already used");';
				$_SESSION['check'][0]++;
				$check++;
				break;
			}
			else if ($_POST['id'] == $row[1]) {
				echo '<script>alert("This ID is already used");';
				$_SESSION['check'][1]++;
				$check++;
				break;
			}
			else if ($_POST['email'] == $row[2]) {
				echo '<script>alert("This e-mail is already used");';
				$_SESSION['check'][4]++;
				$check++;
				break;
			}
		}
		if ($check) echo 'location.replace("./signup.php");</script>';
		else {
			mysqli_query($conn, 'insert into userlist (nick, id, pswd, email) values ("'.$_POST['nick'].'", "'.$_POST['id'].'", "'.$_POST['pswd'].'", "'.$_POST['email'].'")');
			echo '<script>alert("You have signed up successfully!");location.replace("./login.php");</script>';
		}
	}
	}
?>