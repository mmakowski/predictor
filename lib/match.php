<?php

/*
 * addPredictionInfo -- append the current user's prediction info
 * to the given match array. Following fields are appended:
 * * predHomeGoals -- predicted home goals
 * * predAwayGoals -- predicted away goals
 * * predResult -- predicted match result
 * * predTime -- the time prediction was submitted
 */
function addPredictionInfo($db, $match) {
  $q = 'SELECT * FROM PREDICTIONS '
    . 'WHERE matchId = ' . $match['matchId'] . ' '
    . 'AND userId = ' . $_SESSION['userId'];
  if ($res = mysql_query($q, $db)) {
    if ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
      $match['predHomeGoals'] = $r['homeGoals'];
      $match['predAwayGoals'] = $r['awayGoals'];      
      $match['predResult'] = $r['result'];      
      $match['predTime'] = $r['submissionTime'];
    } else {
      $match['predHomeGoals'] = "";
      $match['predAwayGoals'] = "";
      $match['predResult'] = "";
      $match['predTime'] = "";
    }
  } else {
    showError("MySQL error: " . mysql_error());    
  }
  
  mysql_free_result($res);
  return $match;
  
}


/*
 * addShowRoundInfo -- to each match add a flag saying
 * whether the round number should be displayed before
 * that match.
 */
function addShowRoundInfo($matches) {
  foreach ($matches as $k => $m) {
    $matches[$k]['showRound'] = ($k == 0 || ($matches[$k - 1]['roundNo'] != $m['roundNo']));
  }
  return $matches;
}

/*
 * getCurrentMatches -- build an array of matches
 * available for predicting in given game.
 */
function getCurrentMatches($db, $gameId) {
  $q = 'SELECT * FROM MATCHES '
    . 'WHERE gameId = ' . $gameId . ' '
    . 'AND startPredicting <= NOW() ' 
    . 'AND deadline >= NOW() '
    . 'ORDER BY deadline';
  $matches = array();
  if ($res = mysql_query($q, $db)) {
    while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
      $matches[] = addPredictionInfo($db, $r);
    }
  } else {
    showError("MySQL error: " . mysql_error());    
  }
  
  mysql_free_result($res);
  $matches = addShowRoundInfo($matches);
  return $matches;
}

/*
 * getFinishedMatches -- build an array of matches
 * for which results are known.
 */
function getFinishedMatches($db, $gameId) {
  $q = 'SELECT * FROM MATCHES '
    . 'WHERE gameId = ' . $gameId . ' '
    . 'AND NOT (homeGoals is NULL) '
    . 'ORDER BY roundNo, deadline';
  $matches = array();
  $res = mysql_query($q, $db) or showMySQLError();
  while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
    $matches[] = addPredictionInfo($db, $r);
  }
  
  mysql_free_result($res);
  $matches = addShowRoundInfo($matches);
  return $matches;
}


/*
 * getClosedMatches -- build an array of matches
 * for which predicting is closed but results are not known yet.
 */
function getClosedMatches($db, $gameId) {
  $q = 'SELECT * FROM MATCHES '
    . 'WHERE gameId = ' . $gameId . ' '
    . 'AND resultTime is NULL '
    . 'AND deadline < NOW() '
    . 'ORDER BY roundNo, deadline';
  $matches = array();
  $res = mysql_query($q, $db) or showMySQLError();
  while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
    $matches[] = addPredictionInfo($db, $r);
  }
  
  mysql_free_result($res);
  $matches = addShowRoundInfo($matches);
  return $matches;
}

/*
 * getMatchInfo -- return the details for given match.
 */
function getMatchInfo($db, $matchId) {
  $q = "SELECT * FROM MATCHES WHERE matchId = $matchId";
  $res = mysql_query($q, $db) or showMySQLError();
  $inf = mysql_fetch_array($res, MYSQL_ASSOC) or showError("No such match: $matchId");
  mysql_free_result($res);
  return $inf;
}
?>
