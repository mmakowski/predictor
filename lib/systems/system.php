<?php

define('QUAL_THRESHOLD', '0.6');


class System {
  var $db;

  /*
   * construct the system.
   */
  function System($dblink) {
    $this->db = $dblink;
  }

  /*
   * return given user result for given match.
   */
  function userMatchResult($user, $match) {
  }

  /*
   * return the minimal score for this system
   */
  function minScore() {
  }

  /*
   * return the maximal score for this system
   */
  function maxScore() {
  }

  /*
   * return split ranking for given game, i.e. ranking of players + not qualified players
   * The split ranking is an array with following fields:
   * * ranking -- the ranking
   * * unqualified -- the list of unqualified players
   */
  function splitGameRanking($gameId) {
    $q = "SELECT COUNT(matchId) AS cnt FROM MATCHES WHERE gameId = $gameId AND resultTime < NOW()";
    $res = mysql_query($q, $this->db) or showMySQLError();
    $matches = array();
    $r = mysql_fetch_array($res, MYSQL_ASSOC) or showError('no result for COUNT query');
    $matchLimit = round($r['cnt'] * QUAL_THRESHOLD);
    mysql_free_result($res);

    $rank = $this->gameRanking($gameId);
    $unq = array();
    foreach ($rank as $uid => $r) {
      if ($r['matches'] < $matchLimit) {
    $unq[$uid] = $r;
    unset($rank[$uid]);
      }
    }

    $sr = array();
    $sr['ranking'] = $this->sortRanking($rank);
    $sr['unqualified'] = $this->sortUnqualified($unq);

    return $sr;
  }

  /*
   * return the ranking for given game.
   * The ranking is an array indexed by user id's. For each user id
   * there are following fields:
   * * matches -- the number of user's predictions
   * * points -- the average score
   * * normPoints -- the average score normalized to 0..1 range
   */
  function gameRanking($gameId) {
    $q = "SELECT matchId FROM MATCHES WHERE gameId = $gameId AND resultTime < NOW()";
    $res = mysql_query($q, $this->db) or showMySQLError();
    $matches = array();
    while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
      $matches[] = $this->i_matchRanking($r['matchId']);
    }
    mysql_free_result($res);

    $rank = array();
    foreach ($matches as $mr) {
      foreach ($mr as $umr) {
  $rank[$umr['userId']]['sum'] += $umr['points'];
  $rank[$umr['userId']]['matches']++;
      }
    }
    foreach ($rank as $uid => $r) {
      $rank[$uid]['userId'] = $uid;
      $rank[$uid]['points'] = round($r['sum'] / $r['matches'], 3);
      $rank[$uid]['normPoints'] = $this->i_normalizeScore($rank[$uid]['points']);
    }

    $rank = $this->appendUserName($rank);
    return $this->sortRanking($rank);
  }


  /*
   * return the ranking for given match (internal).
   * The ranking is an array indexed by user id's. For each user id
   * there are following fields:
   * * points -- the total number of points this user received for this match
   * * misc -- a dictionary containing additional information
   */
  function i_matchRanking($match) {
  }

  /*
   * normalize given score to 0..1 range (internal).
   */
  function i_normalizeScore($score) {
    $min = $this->minScore();
    $max = $this->maxScore();
    return ($score == '') ? '' : ($score - $min) / ($max - $min);
  }

  /*
   * return the results for given match.
   */
  function matchRanking($match) {
    $pred = $this->i_matchRanking($match);
    $pred = $this->appendUserName($pred);
    return $this->sortRanking($pred);
  }

  /*
   * return the results for given game.
   */
  function gameResults($game) {
  }

  /*
   * sort the results.
   */
  function sortRanking($pred) {
    $kpts = array();
    foreach ($pred as $p) {
      $kpts[($p['points'] * 10000000) + $p['userId']] = $p;
    }
    krsort($kpts);
    $pred = $kpts;
    $spred = array();
    $i = 0;
    foreach ($pred as $p) {
      $spred[$i] = $p;
      $spred[$i]['showRowNum'] = ($spred[$i]['points'] != $spred[$i - 1]['points']);
      $i++;
    }
    return $spred;
  }

  /*
   * sort the results.
   */
  function sortUnqualified($pred) {
    $kpts = array();
    foreach ($pred as $p) {
      $kpts[($p['matches'] * 10000000) + $p['points'] * 1000 + $p['userId']] = $p;
    }
    krsort($kpts);
    $pred = $kpts;
    $spred = array();
    $i = 0;
    foreach ($pred as $p) {
      $spred[$i] = $p;
      $spred[$i]['showRowNum'] = ($spred[$i]['matches'] != $spred[$i - 1]['matches']);
      $i++;
    }
    return $spred;
  }

  /*
   * append user names to the result array
   */
  function appendUserName($pred) {
    $q = "SELECT userId, displayName FROM USERS";
    $res = mysql_query($q, $this->db) or showMySQLError();
    while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
      if (isset($pred[$r['userId']])) {
  $pred[$r['userId']]['displayName'] = $r['displayName'];
      }
    }
    mysql_free_result($res);

    return $pred;
  }

  /*
   * addMatchStats -- append the result stats
   * to each match array in given array.
   * Following fields are appended to each match array:
   * * averageScore -- the average score for this match
   * * yourScore -- $user's score for this match
   * * normAverageScore -- the average score normalized to 0..1 range
   * * normYourScore -- $user's score normalized to 0..1 range
   */
  function addMatchStats($matches, $user) {
    foreach ($matches as $i => $match) {
      // compute the average score and std. dev. and append it to the match array
      $avg = '';
      $your = '';
      $stddev = '';
      $ranking = $this->i_matchRanking($match['matchId']);
      $sum = 0;
      $count = 0;
      $sumsq = 0;
      foreach ($ranking as $uid => $score) {
  if ($uid == $user) $your = $score['points'];
  $sc = $score['points'];
  $sum += $sc;
  $sumsq += $sc * $sc;
  $count++;
      }
      if ($count > 0) $avg = round($sum / $count, 3);
      if ($count > 1) $stddev = round(sqrt(($sumsq - $sum * $avg) / ($count - 1)), 3);

      $match['averageScore'] = $avg;
      $match['yourScore'] = $your;
      $match['stdDev'] = $stddev;
      $match['normAverageScore'] = $this->i_normalizeScore($avg);
      $match['normYourScore'] = $this->i_normalizeScore($your);
      $match['normStdDev'] = $this->i_normalizeScore($stddev);

      $matches[$i] = $match;
    }
    return $matches;
  }

}
?>