<?php
session_start();
function fbdie($e) {
	$title = "Roster";
	$head = "Roster Error";
	include "header.php";
	echo "<div id=\"error\"><h1>$e</h1></div>\n";
	include "footer.php";
	die();
}

if(!isset($_SESSION['sid'])) {header("Location: login.php"); }

//class given
if(!isset($_GET['cid'])) {
fbdie("No class specified");
}
include "fnord.php";
$result = mysql_query("SELECT * FROM `classes` WHERE `id` = '{$_GET['cid']}'");
if (!$result || mysql_num_rows($result) < 1) { fbdie("Either the DB is down or that class doesn't exist"); }

$classinfo = mysql_fetch_assoc($result);

//make sure person is in the class
$result = mysql_query("SELECT `".$classinfo['block']."` FROM `students` WHERE `id` = '{$_SESSION['sid']}'");
$db_cid = mysql_fetch_array($result , MYSQL_NUM);
$db_cid = $db_cid[0];
if($_GET['cid'] != $db_cid) { fbdie("You can only see classes you are in!");}

$title = $head = "Roster for {$classinfo['teacher']}'s " .strtoupper($classinfo['block']). " " . $classinfo['class'];
include "header.php";

echo "<table =\"infotable\">\n";
//get the students
$s_result = mysql_query("SELECT `name` FROM `students` WHERE `".$classinfo['block']."` = '{$_GET['cid']}'");
$students = array();
while ($student = mysql_fetch_row($s_result)) { array_push($students,$student[0]);}
$s_sort = array();
foreach ($students as $student) {
$key = explode(' ', $student);
$key = $key[count($key)-1];
$s_sort[$key] = $student;
}
ksort($s_sort); //sort by last name
foreach ($s_sort as $key => $value ) {
echo "<tr><td>$value</td></tr>\n";
}
echo "</table>\n";
include "footer.php";
?>