<?php

require "lib/defs.php";
require "lib/smarty/Smarty.class.php";
require "lib/common.php";
require "lib/game.php";

include "lib/inc_pre.php";

$smarty = new Smarty;
//$smarty->debugging = true;

$curGames = getCurrentGames($db);
$avGames = getAvailableGames($db);
$clGames = getClosedGames($db);
$smarty->assign('currentGames', $curGames);
$smarty->assign('availGames', $avGames);
$smarty->assign('availGamesCount', count($avGames));
$smarty->assign('closedGames', $clGames);
$smarty->display('main.tpl');  

include "lib/inc_post.php";
?>
