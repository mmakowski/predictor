<?php

require "lib/defs.php";
require "lib/smarty/Smarty.class.php";
require "lib/common.php";
require "lib/game.php";
require "lib/match.php";
require "lib/systems/system.php";
require "lib/systems/kmiernik1.php";
require "lib/systems/mmakowski1.php";

include "lib/inc_pre.php";

function scatterPlotPoints($matchResults) {
  foreach ($matchResults as $pred) {
    $points[$pred['homeGoals'].':'.$pred['awayGoals']]++;
  }
  $enc = '';
  foreach ($points as $r => $n) {
    if ($enc != '') $enc .= ',';
    $enc .= $r.':'.$n;
  }
  return $enc;
}

$smarty = new Smarty;
//$smarty->debugging = true;

$id = $_GET['id'];

$sys = getSystem($db);
$user = array('id' => $_SESSION['userId']);
$match = getMatchInfo($db, $id);
$match = addPredictionInfo($db, $match);

$smarty->assign('user', $user);
$smarty->assign('match', $match);
$smarty->assign('game', getGameInfo($db, $match['gameId']));
date_default_timezone_set('CET');
$showResults = (strtotime($match['deadline']) < time());
$smarty->assign('showResults', $showResults);


if ($showResults) {
  $matchResults = $sys->matchRanking($id);
  $smarty->assign('matchResults', $matchResults);
  $smarty->assign('scatterPlotPoints', scatterPlotPoints($matchResults));
  $smarty->assign('scatterPlotHighlight', $match['predHomeGoals'].':'.$match['predAwayGoals']);
}
$smarty->display('match.tpl');  

include "lib/inc_post.php";
?>
