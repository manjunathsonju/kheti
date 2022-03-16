<?php

if(isset($_POST['username']) AND isset($_POST['password'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	$loginUrl = "https://khet.kheti.farm/index.php?route=account/login&username=$username&password=$password";
	header("Location: $loginUrl");
}

if(isset($_GET['username']) AND isset($_GET['password'])) {
	$username = $_GET['username'];
	$password = $_GET['password'];

	$loginUrl = "https://khet.kheti.farm/index.php?route=account/login&username=$username&password=$password";
	header("Location: $loginUrl");
}

?>