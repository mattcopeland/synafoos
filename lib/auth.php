<?php
$action = $_GET['action'];
$cookie = $_GET['cookie'];
if ($action == "set_cookie")
{
	setcookie("synafoos", $cookie, time()+(60*60*24*365), "/");
	header('Location: /foosball/index.php');
}
else if ($_COOKIE["synafoos"] !== "syn@f00s")
{
	header('Location: /foosball/auth.php');
}
?>