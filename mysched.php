<?php 

$title = $head = "My Schedule";

session_start();
if (!isset($_SESSION['sid'])){header("Location: login.php");}
include "fnord.php";
function getClass($cid) {
$result = mysql_query("SELECT * FROM `classes` WHERE `id` = '$cid'");
if (!$result) {die(mysql_error());}

$classes = mysql_fetch_assoc($result);

return $classes['class'] . " (" . $classes['teacher'].")";

}

$blocks = array( '1a','2a','3a','4a','1b','2b','3b','4b');

$result = mysql_query("SELECT `" . implode("`,`",$blocks) . 
"` FROM `students` WHERE `id` = '{$_SESSION['sid']}'",$db_link);
$myclasses = 0;
include "header.php";
if ($result) {$myclasses = mysql_fetch_assoc($result); }

$allnull = 1;
foreach ($myclasses as $key => $value) {
if ($value != '') { $allnull = 0; break; }
}
if($allnull) {
echo "<div id=\"error\"><h1>You Haven't Entered Your Classes Yet! Go <a href=\"editsched.php\">do</a> that.</h1></div>\n";
include "footer.php";
exit();
}

echo "<table class=\"infotable\">\n";
foreach( $myclasses as $block => $cid) {
if ($cid != '') {
	if($block == '1b') { echo '<tr><td>&nbsp;</td></tr>'; }
	echo '<tr><td class="label">'.strtoupper($block).": </td><td><a href=\"roster.php?cid=$cid\">".getClass($cid)."</a></td></tr>\n";
}
}
echo "</table>\n";

include "footer.php"

?>