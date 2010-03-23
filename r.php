<?php /* The register page */	
	$title = "Register";
	$head = "Registration Page";	
	$slog = 1;
	
	

	require("fnord.php");
	do {
		if (isset($_POST['doreg'])) {
		  foreach ( $_POST as $key => $value ) {
		    if (strlen($value) < 1) {
		      $errormess = ucfirst($key)." was left blank!";
		      $errormess = ereg_replace("fname", "First name", $errormess);
		      $errormess = ereg_replace("lname", "Last name", $errormess);
		      break;
		    }
		    $_POST[$key] = strip_tags($value);
		  } if(isset($errormess)){break;}
		  if (!eregi("^[a-z0-9_-]+$",$_POST['fname'])) { $errormess = "First name contains illegal characters (this includes spaces).";  break;}
		  if (!eregi("^[a-z0-9_-]+$",$_POST['lname'])) { $errormess = "Last name contains illegal characters (this includes spaces).";  break;}
		  if (!eregi("^[a-z0-9_ -]+$",$_POST['password'])) { $errormess = "Password contains illegal characters.";  break;}


		    if (!eregi("^[a-z0-9_-]+\@[a-z0-9_.-]+.[a-z]+$",$_POST['email'])) { $errormess = "E-mail adress improperly formatted.";  break;}

		    if( $_POST['password'] != $_POST['confirm_password']) {
		      $errormess = "Passwords do not match!";
		      break;
		    }
		    
		    $uniq = mysql_query("SELECT DISTINCT * FROM `students` WHERE `email` = '{$_POST['email']}'",$db_link);

		    if (mysql_num_rows($uniq) > 0) {
		      $errormess = "That e-mail has already been registered, please log-in instead."; break; }
		    $_POST['fname'] = ucfirst($_POST['fname']);
		    $_POST['lname'] = ucfirst($_POST['lname']);
		    $query = "INSERT INTO `students` (`name`,`password`,`email`,`verif`) VALUES ( '{$_POST['fname']} {$_POST['lname']}','" . sha1($_POST['password']) . "','{$_POST['email']}','0')";
	    
		    $result = mysql_query($query, $db_link);
		    $message = "
Hey {$_POST['fname']},".'

Welcome to the new and improved OLHS Schedule DB. As a way to avoid fraudulent and redundant entries in the DB I have implemented a system that makes entries editable and tied to e-mail adresses. To make sure this is a real e-mail adress please go here http://olhs.donaldguy.com/verif.php?email=' . urlencode($_POST['email']). '&code=' .urlencode(md5(strtolower(substr($_POST['fname'].' '.$_POST['lname'],0,5)))) . '

From there you\'ll be able to enter your schedule and see who\'s in it! Have Fun.

~Donald Guy';
		   // $message =  wordwrap($message,70,"\n");
		    $headers = "From: Dolphinbook <admin@olhs.donaldguy.com>\r\n";
		   mail($_POST['email'],"Dolphinbook: OL Schedule Authentication",$message,$headers);

		
		    
		    include "header.php";
		    echo "<h1> Thank you for registering. Please check your e-mail for an activation e-mail.</h1> <br /> <br />";
		    include "footer.php";
		    exit();

		}
	} while(false);
	

	include "header.php";
	if(isset($_SESSION['sid'])) {header("Location: /"); }	
	
	if (isset($errormess)) {
		echo "<div id=\"error\"><h1>$errormess</h1></div>";
	}

	
	echo "
	<div style=\"position: relative; left: 20px\"> <h1 style=\"color: #3B5998;\"> Register </h3>
	<h2> create you account. Note: e-mail used for verification purposes only </h2><br />
	<form method=\"post\" id=\"regform\" action=\"r.php\">
	<table class=\"formtable\">
	<tr><td class=\"label\">First Name:</td><td><input name=\"fname\" size=\"30\" type=\"text\" class=\"inputtext\" value=\"{$_POST['fname']}\" /></td></tr>
	<tr><td class=\"label\">Last Name:</td><td><input name=\"lname\" size=\"30\" type=\"text\" class=\"inputtext\" value=\"{$_POST['lname']}\" /></td></tr>
	<tr><td class=\"label\">E-mail</td><td><input name=\"email\" size=\"30\" type=\"text\" class=\"inputtext\" value=\"{$_REQUEST['email']}\" /></td></tr>
<tr><td class=\"label\">Password</td><td><input name=\"password\" size=\"30\" type=\"password\" class=\"inputpassword\" /></td></tr>
<tr><td class=\"label\">Confirm Password</td><td><input name=\"confirm_password\" size=\"30\" type=\"password\" class=\"inputpassword\" /></td></tr>
</table>
<input type=\"hidden\" name=\"doreg\" value=\"1\">

<table class=\"formbuttons\"><tr><td><input type=\"submit\" class=\"inputsubmit\"value=\"Submit!\" /></td>
<td><input type=\"button\" value=\"Reset\" class=\"inputbutton\" onclick=\"javascript:document.getElementById('regform').reset()\" /></td>
</tr></table>
</form>
</div>
	";

	include "footer.php";
?>

