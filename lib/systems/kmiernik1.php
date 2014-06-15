<?php


define('W_RES', 0.5);
define('W_HG', 0.125);
define('W_AG', 0.125);
define('W_DIF', 0.2);
define('W_SUM', 0.05);

class KMiernik1 extends System {

  /*
   * return given user result for given match.
   */
  function userMatchResult($user, $match) {
    $mr = matchResults($match);
    return $mr[$user];
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
    return 1;
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

    // calculate the absolute differences in all categories
    foreach ($pred as $uid => $fields) {
      $pred[$uid]['res'] = ($fields['result'] == $result['result']) ? 0 : 1;
      $pred[$uid]['hg'] = abs($fields['homeGoals'] - $result['homeGoals']);
      $pred[$uid]['ag'] = abs($fields['awayGoals'] - $result['awayGoals']);
      $pred[$uid]['dif'] = abs(($fields['homeGoals'] - $fields['awayGoals']) - ($result['homeGoals'] - $result['awayGoals']));
      $pred[$uid]['sum'] = abs(($fields['homeGoals'] + $fields['awayGoals']) - ($result['homeGoals'] + $result['awayGoals']));
    }
    
    // calculate the sums of diffs in each category
    $toterr = array('res' => 1, 'hg' => 0, 'ag' => 0, 'dif' => 0, 'sum' => 0);
    foreach ($pred as $f) {
      $toterr['hg'] += $f['hg'];
      $toterr['ag'] += $f['ag'];
      $toterr['dif'] += $f['dif'];
      $toterr['sum'] += $f['sum'];
    }

    // normalize and weight the results
    foreach ($pred as $uid => $f) {
      $pred[$uid]['resNW'] = ($toterr['res'] == 0) ? 0 : $f['res'] / $toterr['res'] * W_RES;
      $pred[$uid]['hgNW'] = ($toterr['hg'] == 0) ? 0 : $f['hg'] / $toterr['hg'] * W_HG;
      $pred[$uid]['agNW'] = ($toterr['ag'] == 0) ? 0 : $f['ag'] / $toterr['ag'] * W_AG;
      $pred[$uid]['difNW'] = ($toterr['dif'] == 0) ? 0 : $f['dif'] / $toterr['dif'] * W_DIF;      
      $pred[$uid]['sumNW'] = ($toterr['sum'] == 0) ? 0 : $f['sum'] / $toterr['sum'] * W_SUM;
      $pred[$uid]['points'] = round(1 - $pred[$uid]['resNW'] - $pred[$uid]['hgNW'] - $pred[$uid]['agNW'] - $pred[$uid]['difNW'] - $pred[$uid]['sumNW'], 3);

      $pred[$uid]['resNW'] = round($pred[$uid]['resNW'], 3);
      $pred[$uid]['hgNW'] = round($pred[$uid]['hgNW'], 3);
      $pred[$uid]['agNW'] = round($pred[$uid]['agNW'], 3);
      $pred[$uid]['difNW'] = round($pred[$uid]['difNW'], 3);
      $pred[$uid]['sumNW'] = round($pred[$uid]['sumNW'], 3);
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