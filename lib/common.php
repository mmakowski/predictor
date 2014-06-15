<?php

/*
 * showError -- display an error message 
 */
function showError($msg) {
  echo $msg;
  exit;
}

/*
 * showMySQLError -- display a MySQL error
 */
function showMySQLError() {
  showError("MySQL error: " . mysql_error());
}

/*
 * checkLogin -- check if user is logged in and if not 
 * redirect to the login page.
 */
function checkLogin() {
  if (!($_SESSION['userId'] > 0)) {
    header("Location: http://" . $_SERVER['HTTP_HOST']
	   . dirname($_SERVER['PHP_SELF'])
	   . "/login.php");      
    exit;
  }
}

/*
 * getDB -- initialize the database and return a handle to
 * it.
 */
function getDB() {
  $db = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) 
    or showError("MySQL error when connecting: " . mysql_error());
  mysql_select_db(DB_NAME, $db)
    or showError("MySQL error when selecting database: " . mysql_error());
  return $db;
}

/*
 * redirect -- redirect the request to given page.
 */
function redirect($page) {
  header("Location: http://" . $_SERVER['HTTP_HOST']
	 . dirname($_SERVER['PHP_SELF'])
	 . "/$page");      
  exit;
}

/*
 * getSystem -- return scoring system object
 */
function getSystem($db) {
  return new MMakowski1($db);
}
  
?>