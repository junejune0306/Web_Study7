<?php
	if (isset($_SESSION['alert'])) {
		echo '<script>alert("'.$_SESSION['alert'].'")</script>';
		unset($_SESSION['alert']);
	}
?>