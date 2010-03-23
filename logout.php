<?php
     session_destroy();
     setcookie("PHPSESSID", false);
     header("Location: /");
?>