<?php


define('MM1_W_RES', 5);
define('MM1_W_HG', 2);
define('MM1_W_AG', 2);
define('MM1_W_DIF', 2);
define('MM1_W_SUM', 1);

class MMakowski1 extends System {

  /*
   * return given user result for given match.
   */
  function userMatchResult($user, $match) {
    // not used
  }

  /*
   * return the minimal score for this system
   */
  function minScore() {
    return 0;
  }
  
  /*
   * return the maximal score for this system
   */
  function maxScore() {
    return 12;
  }

  function i_calcScore($user, $result, $homeGoals, $awayGoals) {
    $user['points'] = 0;
    $userHome = $user['homeGoals'];
    $userAway = $user['awayGoals'];
    if ($user['result'] == $result) {
      $user['res'] = MM1_W_RES;
      $user['points'] += MM1_W_RES;
    } else {
      $user['res'] = 0;
    }
    $tmp = MM1_W_HG - abs($homeGoals - $userHome);
    if ($tmp < 0) $tmp = 0;
    $user['hg'] = $tmp;
    $user['points'] += $tmp;
    $tmp = MM1_W_AG - abs($awayGoals - $userAway);
    if ($tmp < 0) $tmp = 0;
    $user['ag'] = $tmp;
    $user['points'] += $tmp;
    $tmp = MM1_W_DIF - abs(($homeGoals - $awayGoals) - ($userHome - $userAway));
    if ($tmp < 0) $tmp = 0;
    $user['dif'] = $tmp;
    $user['points'] += $tmp;
    $tmp = MM1_W_SUM - abs(($homeGoals + $awayGoals) - ($userHome + $userAway));
    if ($tmp < 0) $tmp = 0;
    $user['sum'] = $tmp;
    $user['points'] += $tmp;
    return $user;
  }
  
  /*
   * return the results for given match.
   */
  function i_matchRanking($match) {
    // read the predictions from db
    $q = "SELECT * FROM PREDICTIONS WHERE matchId = $match";
    $res = mysql_query($q, $this->db) or showMySQLError();
    $pred = array();
    while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
      $pred[$r['userId']] = $r;
    }
    mysql_free_result($res);

    // read the result from db
    $q = "SELECT * FROM MATCHES WHERE matchId = $match";
    $res = mysql_query($q, $this->db) or showMySQLError();
    $result = mysql_fetch_array($res, MYSQL_ASSOC) or showError("No such match: $match");
    mysql_free_result($res);

    foreach ($pred as $uid => $f) {
      $pred[$uid] = $this->i_calcScore($pred[$uid], $result['result'], $result['homeGoals'], $result['awayGoals']);
      $pred[$uid]['resNW'] = $pred[$uid]['res'];
      $pred[$uid]['hgNW'] = $pred[$uid]['hg'];
      $pred[$uid]['agNW'] = $pred[$uid]['ag'];
      $pred[$uid]['difNW'] = $pred[$uid]['dif'];
      $pred[$uid]['sumNW'] = $pred[$uid]['sum'];
    }

    return $pred;
  }
  
  /*
   * return the results for given game.
   */
  function gameResults($game) {
  }
}
?>