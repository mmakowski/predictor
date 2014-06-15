<?php

require "lib/defs.php";
require "lib/smarty/Smarty.class.php";
require "lib/common.php";

session_start();
header("Cache-control: private");

$smarty = new Smarty;

if ($_POST['name'] == '') {
  $smarty->display('login.tpl');  
  exit;
}

$smarty->assign('name', $_POST['name']);

$db = getDB();
$q = "SELECT userId, password FROM USERS WHERE name='" . $_POST['name'] . "'";

if (($res = mysql_query($q, $db)) != 0) {
  if ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
    if (strcmp($r['password'], $_POST['pass']) == 0) {
      $_SESSION['userId'] = $r['userId'];
      header("Location: http://" . $_SERVER['HTTP_HOST']
	     . dirname($_SERVER['PHP_SELF'])
	     . "/main.php");      
      mysql_free_result($res);
      mysql_close($db);
      exit;
    } else {
      $smarty->assign('passwordErr', 'nieprawid³owe has³o');
    }
  } else {
    $smarty->assign('nameErr', 'nie ma takiego u¿ytkownika');
  }
  $smarty->display('login.tpl');  
} else {
  showError("MySQL error: " . mysql_error());
}
mysql_free_result($res);
mysql_close($db);


?>
