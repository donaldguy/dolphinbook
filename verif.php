<?php

$error = '<div id="error"><h1>Invalid Verification, please check your link or <a href="r.php">Register</a></h1></div>';
$title = "Verify";
$head = "Failed Verification";

if (!(isset($_GET['email']) && isset($_GET['code']))) {
	include "header.php";
	echo $error;
	include "footer.php";
	exit();
}
include "fnord.php";
$result  = mysql_query("SELECT * FROM `students` WHERE `email` = '" . $_GET['email'] . "'",$db_link);

$res_hash = mysql_fetch_assoc($result);
if ($res_hash['verif']) {
	include "header.php";
	echo '<div class="status"><h1> Already Validated, please <a href="login.php">Login</a></h1></div>';
	include "footer.php";
	exit();
}

if ($_GET['code'] == md5(strtolower(substr($res_hash['name'],0,5)))) {
	mysql_query("UPDATE `students` SET `verif` = 1 WHERE `email` = '{$_GET['email']}'",$db_link);
	session_start();
	$_SESSION['sid'] = $res_hash['id'];
	header("Location: index.php");
	//echo $_SESSION['sid'];

} else {
	include "header.php";
	echo $error;
	include "footer.php";
	exit();
}


?>