<link rel="stylesheet" href="css/navigation_bar.css">
<div id="navigation_bar">
	<a href="home.php" id="nblogo">LOVCAT</a>
	<?php
    if (isset($_SESSION['userinfo'])) echo '<a href="logout.php" id="nblog">Log Out</a><a href="userinfo.php" id="nbid">'.htmlspecialchars($_SESSION['userinfo'][1]).'</a>';
    else echo '<a href="login.php" id="nblog">Log In</a>';
    ?>
</div>