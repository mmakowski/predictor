<?php

/*
 * miscGameInfo -- append to the game array additional
 * info like the number of matches to predict etc.
 */
function addMiscGameInfo($db, $game) {
  // TODO
  return $game;
}

/*
 * getCurrentGames -- build an array of open games
 * current user participates in.
 */
function getCurrentGames($db) {
  $q = 'SELECT * FROM USER_GAMES AS ug LEFT JOIN GAMES AS g ON ug.gameId = g.gameId '
    . 'WHERE ug.userId = ' . $_SESSION['userId'] . ' '
    . 'AND g.dateStart <= NOW() ' 
    . 'AND g.dateEnd >= NOW()';
  $games = array();
  if ($res = mysql_query($q, $db)) {
    while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
      $games[] = addMiscGameInfo($db, $r);
    }
  } else {
    showError("MySQL error: " . mysql_error());    
  }
  
  mysql_free_result($res);
  return $games;
}

/*
 * getAvailableGames -- build an array of open games
 * current user does not participate in.
 */
function getAvailableGames($db) {
  $q = 'SELECT * FROM GAMES '
    . 'WHERE dateStart <= NOW() ' 
    . 'AND dateEnd >= NOW()';
  $games = array();
  $res = mysql_query($q, $db) or showError("MySQL error: " . mysql_error());    
  while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
    $q = 'SELECT userGameId FROM USER_GAMES '
      . 'WHERE userId = ' . $_SESSION['userId'] . ' '
      . 'AND gameId = ' . $r['gameId'];
    $res2 = mysql_query($q, $db) or showError("MySQL error: " . mysql_error());
    if (!mysql_fetch_row($res2)) {
      $games[] = addMiscGameInfo($db, $r);
    }
    mysql_free_result($res2);
  }
  
  mysql_free_result($res);
  return $games;
}

/*
 * joinGame -- join the given game.
 */
function joinGame($db, $gameId) {
  $q = 'INSERT INTO USER_GAMES (userId, gameId) VALUES ('
    . $_SESSION['userId'] . ', ' . $gameId . ')';
  mysql_query($q, $db) or showError("MySQL error: " . mysql_error());
}

/*
 * getClosedGames -- build an array of closed games
 * current user participated in.
 */
function getClosedGames($db) {
  $q = 'SELECT * FROM GAMES '
    . 'WHERE dateEnd < NOW()';
  $games = array();
  $res = mysql_query($q, $db) or showError("MySQL error: " . mysql_error());    
  while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
    $q = 'SELECT userGameId FROM USER_GAMES '
      . 'WHERE userId = ' . $_SESSION['userId'] . ' '
      . 'AND gameId = ' . $r['gameId'];
    $res2 = mysql_query($q, $db) or showError("MySQL error: " . mysql_error());
    if (mysql_fetch_row($res2)) {
      $games[] = addMiscGameInfo($db, $r);
    }
    mysql_free_result($res2);
  }
  
  mysql_free_result($res);
  return $games;
}

/*
 * getGameInfo -- return the details for given game.
 */
function getGameInfo($db, $gameId) {
  $q = "SELECT * FROM GAMES WHERE gameId = $gameId";
  $res = mysql_query($q, $db) or showMySQLError();
  $inf = mysql_fetch_array($res, MYSQL_ASSOC) or showError("No such game: $gameId");
  mysql_free_result($res);
  return $inf;
}


?>
