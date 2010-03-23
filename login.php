<?php

$title = "Login";
$head = "Log-In";

if(isset($_SESSION['sid'])) { header("Location: /"); }
if (isset($_POST['email']) && isset($_POST['password'])) {

if (isset($_POST["submit"]) && $_POST["submit"] == "Register") {
        header("Location: /r.php?email=" . $_POST['email']);
}

include "fnord.php";
$result = mysql_query("SELECT `id`, `password`, `verif` FROM `students` WHERE `email` = '".$_POST['email']."'",$db_link);

$mysql = "";
if ($result) {$mysql = mysql_fetch_assoc($result);}



if($mysql && $mysql['password'] == sha1($_POST['password'])) {
  if ($mysql['verif']){
        session_start();
	$_SESSION['sid']=$mysql['id'];
	header("Location: /");
  } else {
    include "header.php";
    echo '<div id="error"><h1>You must validate your email adress!</h1><p>thought you could get it past me.. didn\'t you?</p></div>';
  }
 }else {

  include "header.php";
  echo '<div id="error"><h1>Invalid Username or Password</h1></div>';
 }

} else {
include "header.php";
if(isset($_SESSION['sid'])) {header("Location: /"); }
}

?>

<form action="login.php" method="post" >
<table class="formtable">
<tr><td class="label">Email:</td><td><input type="text" class="inputtext" size="30" name="email"  value="<?php echo $_POST['email']; ?>"/></td></tr>
<tr><td class="label">Password:</td><td><input type="password" class="inputpassword" size="30" name="password" /></td></tr>
</table>
<table class="formbuttons"><tr><td><input type="Submit" value="Log In" class="inputsubmit" /></td><td><input type="button" class="inputsubmit" value="Register" onclick="javascript:document.location='r.php'" /></td></tr></table>
</form>



<?php include "footer.php" ?>