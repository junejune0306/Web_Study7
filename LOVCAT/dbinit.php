<?php
	$db_Host = "localhost";
	$db_Id = "junejune0306";
	$db_Pass = "2022Wnsldj!";
	$db_Name = "junejune0306";

	$conn = mysqli_connect($db_Host, $db_Id, $db_Pass, $db_Name);

	if (mysqli_connect_errno())
		die('Connect Error : '.mysqli_connect_errno());
	else {
		mysqli_query($conn, "set session character_set_connection=utf8;");
		mysqli_query($conn, "set session character_set_results=utf8;");
		mysqli_query($conn, "set session character_set_client=utf8;");
	}
?>