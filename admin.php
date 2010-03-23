<?php
session_start();
if ($_SESSION['sid'] != 1) { header("Location: index.php"); }


$title = "Administrative Tools";
$head = "Admin Tool Box";

include 'header.php';
?>

<h1 onclick="alert(<? echo $_SESSION['sid']?>)">Find and Fix Conflicts</h1>


<?
include 'footer.php';
?>
