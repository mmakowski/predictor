<?php

require "lib/defs.php";
require "lib/smarty/Smarty.class.php";
require "lib/common.php";
require "lib/game.php";
require "lib/match.php";
require "lib/predict.php";

include "lib/inc_pre.php";

$smarty = new Smarty;
$smarty->debugging = false;

$game = $_GET['game'];

if (!($game > 0)) {
  showError("No such game: " . $game);
}

if ($_POST['savePred'] == 1) {
  savePredictions($db);
}

$curMatches = getCurrentMatches($db, $game);
$goalVals = array('', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
$goalOutput = array('--', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
$resultVals = array('', '1', '2');
$resultOutput = array('--', '1', '2');

$smarty->assign('game', getGameInfo($db, $game));
$smarty->assign('gameId', $game);
$smarty->assign('currentMatches', $curMatches);
$smarty->assign('goalVals', $goalVals);
$smarty->assign('goalOutput', $goalOutput);
$smarty->assign('resultVals', $resultVals);
$smarty->assign('resultOutput', $resultOutput);
$smarty->display('predict.tpl');  

include "lib/inc_post.php";
?>
