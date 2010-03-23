<?php 
session_start();
if (!isset($_SESSION['sid'])){header("Location: login.php");}

$title = "Schedule Edit";
$head = "My Schedule";

include "fnord.php";
function getClass($cid) {
$result = mysql_query("SELECT * FROM `classes` WHERE `id` = '$cid'");
if (!$result) {die(mysql_error());}

$class = mysql_fetch_assoc($result);

return $class;

}

function setClass($block,$class,$teacher) {
//see if class exists
if ($class == '' || $teacher =='') {
mysql_unbuffered_query("UPDATE `students` SET `$block` = NULL WHERE `id` = '{$_SESSION['sid']}'");
return; 
}

$result = mysql_query("SELECT `id` FROM `classes` WHERE `block` = '$block' AND `class` LIKE '$class' AND `teacher` LIKE '$teacher' ");

if(!$result) {echo mysql_error(); }

$cid = 0;

if($result && mysql_num_rows($result) > 0) {
	$cid = mysql_fetch_row($result);
	$cid = $cid[0];
} else {
	$class = ucwords($class);
	$class = ereg_replace("Ap","AP",$class);
	$class = ereg_replace("Mg","MG",$class);
	$teacher = ucwords($teacher);
	$teacher = ereg_replace("Ol(hs)?","OL",$teacher); //special fix for "ol staff"
	$res = mysql_unbuffered_query("INSERT INTO `classes` (`block`,`class`,`teacher`) VALUES ('$block', '$class' , '$teacher' )");

	$result = mysql_query("SELECT `id` FROM `classes` WHERE `block` = '$block' AND `class` LIKE '$class' AND `teacher` LIKE '$teacher'");
	$cid = mysql_fetch_row($result);
	$cid = $cid[0];
}
	mysql_unbuffered_query("UPDATE `students` SET `$block` = '$cid' WHERE `id` = '{$_SESSION['sid']}'");
}


$blocks = array( '1a','2a','3a','4a','1b','2b','3b','4b');


do{
if (isset($_POST['doedit'])) {
	
	foreach($blocks as $block) {
	
		if(!ereg("(^[A-Za-z-]+$)|^$",$_POST[$block."_teacher"]) && !eregi("^ol staff$",$_POST[$block."_teacher"])) {
			  $errormess = "TEACHER LAST NAME ONLY!! (I'm getting angry about this)";
	  		break 2;
		}
	
		setClass($block,$_POST[$block."_class"],$_POST[$block."_teacher"]);
	}

header("Location: mysched.php");
}}while(false);




$result = mysql_query("SELECT `" . implode("`,`",$blocks) . 
"` FROM `students` WHERE `id` = '{$_SESSION['sid']}'",$db_link);
$myclasses = 0;
include "header.php";
if ($result) {$myclasses = mysql_fetch_assoc($result); }
echo "<script src=\"suggest.js\"></script>\n";
echo "<h1>Heres where you make changes or enter your schedule</h1>\n<p>Please mess with this as little as possible, to minimize strain on the DB. <span style=\"font-weight: bold;\">If you have semester classes enter FIRST SEMESTER ONLY!</span>ENTER TEACHERS LAST NAMES ONLY. Suggests to your advantage.. if you click them they will fill in the class and teacher for you. If people don't enter classes the same the DB is useless. </p>\n";

if (isset($errormess)) {
echo "<div id=\"error\"><h1>$errormess</h1></div>";
} else {
echo "<div class=\"status\"><h1>Seriously guys:</h1><h2><ul><li>LAST NAMES ONLY!!! </li><li>USE THE DROP DOWNS!!!</li><li>Start it like on the schedule, but use what comes up. It doesn't and probably shouldn't be like on your schedule, used mixed case and actual words</li></ul><p>I'm trying to fix your many mistakes, but, suprisingly, I do have a life</p></h2></div>\n";
}
echo "<form action=\"editsched.php\" method=\"post\">\n";
echo "<table class=\"formtable\">\n";



foreach ($myclasses as $key => $value) {
	
	$class = array();
	
	if (isset( $_POST[$key."_class"]) && isset( $_POST[$key."_teacher"] ) ) {
		$class['class'] = $_POST[$key."_class"];
		$class['teacher'] = $_POST[$key."_teacher"];
	} else {
		$class = getClass($value);
	}
	echo "<tr><td class=\"label\">".strtoupper($key)."</td></tr>\n<tr><td class=\"label\">Class:</td><td><input type=\"text\" class=\"inputtext\" name=\"{$key}_class\" id=\"{$key}_class\"  value=\"{$class['class']}\" size=\"20\" onkeyup=\"suggest('$key')\" onblur=\"blur('$key')\" autocomplete=\"off\" /><div class=\"suggest\" id=\"{$key}_suggest\"></div></td><td class=\"label\">Teacher:</td><td><input type=\"text\" class=\"inputtext\" value=\"{$class['teacher']}\" name=\"{$key}_teacher\" id=\"{$key}_teacher\"/></td></tr>";
}
echo "</table>\n";
echo "<input type=\"hidden\" name=\"doedit\" value=\"1\">\n";
echo "<table class=\"formbuttons\"><tr><td><input type=\"submit\" class=\"inputsubmit\" value=\" Submit\" /></td></tr></table>\n";
echo "</form>\n";

include "footer.php"


?>
