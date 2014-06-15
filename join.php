<?php

require "lib/defs.php";
require "lib/smarty/Smarty.class.php";
require "lib/common.php";
require "lib/game.php";

include "lib/inc_pre.php";

$game = $_GET['game'];

if ($game > 0) {
  // join given game
  joinGame($db, $game);
  redirect('main.php');
}

// display the list of available games
$smarty = new Smarty;
//$smarty->debugging = true;

$avGames = getAvailableGames($db);
$smarty->assign('availGames', $avGames);
$smarty->display('join.tpl');  

include "lib/inc_post.php";
?>
