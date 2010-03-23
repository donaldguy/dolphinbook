<?php

    include "fnord.php";
     if(isset($_GET['block']) && isset($_GET['str']) && $_GET['str'] != '') {
     echo $_GET['block']."\n";

	foreach ($_GET as $key => $value) {
	$_GET[$key] = addslashes($value);
	}     

     $result = mysql_query("SELECT `class`, `teacher`FROM `classes` WHERE `block` = '{$_GET['block']}' AND `class` LIKE '{$_GET['str']}%' ORDER BY `class` ASC");

	while($row = mysql_fetch_row($result)) { echo $row[0].' - '.$row[1]."\n"; }
    }

?>