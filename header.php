<?php
session_start();

//SET UP OUR NAVIGATION LINKS ET AL

if (isset($_SESSION['sid'])) {
	$gnav = "<li><a href=\"mysched.php\"a>my schedule</a></li>\n<li><a href=\"logout.php\">logout</a></href>";

	$side = " <div id=\"snav\">\n\t<a href=\"/\">Home</a><br />\n\t<a href=\"mysched.php\" class=\"hasedit\">My Schedule</a><a class=\"edit\" href=\"editsched.php\">Edit</a><br />";

$side .= "\n</div>";


} else {
	$gnav = "<li><a href=\"login.php\">login</a></li>\n<li><a href=\"r.php\">register</a></li>";
	if (!$slog) {
		$side = " <div id=\"snav\">\n\t<a href=\"/\">Home</a><br />\n\t<a href=\"login.php\">Login</a><br />\n\t<a href=\"r.php\">Register</a><br />\n </div>\n";
	} else {
		$side = "<div id=\"squicklogin\">\n  <form method=\"post\" action=\"login.php\">\n\t<label for=\"email\">Email:</label> <input class=\"inputtext\" type=\"text\" name=\"email\" value=\"{$_SESSION['email']}\" id=\"email\" size=\"30\" />\n\t<label for=\"password\">Password:</label> <input type=\"password\" class=\"inputtext\" name=\"password\" id=\"password\" size=\"30\" />\n\t <table><tr>\n\t\t<td><input type=\"submit\" class=\"inputsubmit\" value=\"Login\" /></td>\n\t\t<td>&nbsp;</td>\n\t\t<td><input type=\"button\" class=\"inputsubmit\" value=\"Register\" onclick=\"javascript:document.location='http://olhs.donaldguy.com/r.php';\" /></td>\n\t</tr></table>\n  </form>\n </div>";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml;charset=UTF-8" />
<title>Dolphinbook | <?php echo $title; ?></title>
<link rel="stylesheet" href="fbook.css" />
<link rel="stylesheet" href="my.css" />
</head>

<body>
<div id="book">
<div id="pageheader"><h1 id="homelink"><a href="/">Dolphinbook</a></h1>
<ul id="gnav">
    <li><a href="index.php">home</a></li>
    <?php echo $gnav; ?>
    
</ul>
</div>
<div id="sidebar">
<?php echo $side; ?>
</div>

<div id="pagebody">
<div id="header"><?php echo $head; ?></div>
<div id="content">
<div style="margin: 10px 20px;">
