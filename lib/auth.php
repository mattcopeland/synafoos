<?php
/*
 * Verify password or cookie existance
 *
 */
require_once('dbconnect.php');
$series_id = $_POST['series_id'];
$password = $_POST['password'];
$query = "SELECT password FROM series WHERE series_id = $series_id";
$result = mysql_query($query);
$series = mysql_fetch_array($result);
if ($series['password'] == $password || $_COOKIE["synafoos"] == $series['password'])
{
	/*if (!isset($_COOKIE["synafoos"]) || $_COOKIE["synafoos"] !== $series['password'])
	{*/
		setcookie("synafoos", $series['password'], time()+(60*60*24*365), "/");
	//}
	echo 'allow';
}
else
{
	echo 'deny';
}
mysql_close($link);
?>