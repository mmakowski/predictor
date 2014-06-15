<?php
session_start();
header("Cache-control: private");

if ($_SESSION['userId'] > 0) {
  header("Location: http://" . $_SERVER['HTTP_HOST']
	 . dirname($_SERVER['PHP_SELF'])
	 . "/main.php");      
} else {
  header("Location: http://" . $_SERVER['HTTP_HOST']
	 . dirname($_SERVER['PHP_SELF'])
	 . "/login.php");      
}
?>
