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

$smarty = new Smarty;
//$smarty->debugging = true;

$id = $_GET['id'];

$sys = getSystem($db);
$user = array('id' => $_SESSION['userId']);
$match = getMatchInfo($db, $id);

$smarty->assign('user', $user);
$smarty->assign('match', $match);
$smarty->assign('game', getGameInfo($db, $match['gameId']));
date_default_timezone_set('CET');
$showResults = (strtotime($match['deadline']) < time());
$smarty->assign('showResults', $showResults);

if ($showResults) {
  $matchResults = $sys->matchRanking($id);
  $smarty->assign('matchResults', $matchResults);
}
$smarty->display('match.tpl');  

include "lib/inc_post.php";
?>
