<?php
session_start();
header("Cache-control: private");

checkLogin();
$db = getDB();
?>
