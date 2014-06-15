<?php

/*
 * savePredictions -- save all the results
 * predicted by user.
 */
function savePredictions($db) {
  foreach ($_POST as $key => $val) {
    if (substr($key, 0, 3) == 'hg_') {
      $matchId = substr($key, 3);
      $hg = $val;
      $ag = $_POST['ag_' . $matchId];
      $nd = $_POST['nd_' . $matchId];
      // obtain the result 1X2
      if ($hg > $ag) {
	$res = '1';
      } elseif ($hg == $ag) {
	$res = 'X';
      } else {
	$res = '2';
      }
      // if it's play-off and a draw has been predicted get the 
      // final result from form
      if ($nd == 1 && $res == 'X') {
	$res = $_POST['res_' . $matchId];
      }
      // if both hg and ag have been given update the result
      if ($hg != '' && $ag != '' && (!$nd || $res)) {
	$q = 'DELETE FROM PREDICTIONS '
	  . 'WHERE matchId = ' . $matchId . ' '
	  . 'AND userId = ' . $_SESSION['userId'];
	if (!mysql_query($q, $db)) {
	  showError("MySQL error: " . mysql_error());    
	}
	$q = 'INSERT INTO PREDICTIONS '
	  . '(userId, matchId, homeGoals, awayGoals, result) '
	  . 'VALUES (' . $_SESSION['userId'] . ', '
	  . $matchId . ', ' . $hg . ', ' .$ag . ", '" . $res . "')";
	if (!mysql_query($q, $db)) {
	  showError("MySQL error: " . mysql_error());    
	}
      }
    }
  }
}

?>
