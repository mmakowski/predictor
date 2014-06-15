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
$smarty->debugging = false;

$id = $_GET['id'];

$sys = getSystem($db);
$gameRanking = $sys->splitGameRanking($id);
$closedMatches = getClosedMatches($db, $id);
$finishedMatches = getFinishedMatches($db, $id);
$finishedMatches = $sys->addMatchStats($finishedMatches, $_SESSION['userId']);
$user = array('id' => $_SESSION['userId']);

$smarty->assign('user', $user);
$smarty->assign('game', getGameInfo($db, $id));
$smarty->assign('gameRanking', $gameRanking['ranking']);
$smarty->assign('showUnqualified', count($gameRanking['unqualified']));
$smarty->assign('unqualified', $gameRanking['unqualified']);
$smarty->assign('showClosedMatches', count($closedMatches) > 0);
$smarty->assign('closedMatches', $closedMatches);
$smarty->assign('finishedMatches', $finishedMatches);
$smarty->display('game.tpl');  

include "lib/inc_post.php";
?>
