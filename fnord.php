<?php 

	//lets connect to the DB!
	$db_link = mysql_connect(/*host:*/ "mysql.donaldguy.com" ,/*user*/ , /*password*/) 
	or die("AHHHHHHHHH somethings wrong with the DB!!");

	mysql_select_db(/*db name: */ "olsched",$db_link) 
	or die("AHHHHHHHHH somethings wrong with the DB!!");
?>