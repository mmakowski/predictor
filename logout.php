<?php
session_start();
header("Cache-control: private");

$_SESSION = array();
session_destroy();

header("Location: http://" . $_SERVER['HTTP_HOST']
       . dirname($_SERVER['PHP_SELF'])
       . "/login.php");      
?>
