<?php

require 'lib/defs.php';
require 'lib/common.php';

$game = $_GET['game'];
$match = $_GET['match'];

if ($_POST['delete'] == 1) {
  // delete the match
  $db = getDB();
  $q = 'DELETE FROM PREDICTIONS WHERE matchId=' . $_POST['matchId'];
  mysql_query($q, $db) or die(mysql_error());      
  $q = 'DELETE FROM MATCHES WHERE matchId=' . $_POST['matchId'];
  mysql_query($q, $db) or die(mysql_error());      

  // redirect to game page
  header("Location: http://" . $_SERVER['HTTP_HOST']
	 . dirname($_SERVER['PHP_SELF'])
	 . "/adm_game.php?game=$game");        
  mysql_close($db);
  exit;
}

include 'lib/admin/inc_pre.php';

if ($_POST['save'] == 1) {
  // save the submitted game
  $match = $_POST['matchId'];
  if ($match > 0) {
    // update existing match
    $q = "UPDATE MATCHES SET roundNo="
      . ($_POST['roundNo'] != ''? $_POST['roundNo'] : 'NULL') . ", title="
      . ($_POST['title'] != '' ? "'".$_POST['title']."'" : 'NULL') . ", homeTeam='"
      . $_POST['homeTeam'] . "', awayTeam='"
      . $_POST['awayTeam'] . "', homeGoals="
      . ($_POST['homeGoals'] != ''? $_POST['homeGoals'] : 'NULL') . ", awayGoals="
      . ($_POST['awayGoals'] != ''? $_POST['awayGoals'] : 'NULL') . ", neutralStadium="
      . $_POST['neutralStadium'] . ", startPredicting='"
      . $_POST['startPredicting'] . "', deadline='"
      . $_POST['deadline'] . "', noDraw="
      . $_POST['noDraw'] . ", result="
      . ($_POST['result'] != ''? "'".$_POST['result']."'" : 'NULL') . ", resultTime="
      . ($_POST['homeGoals'] != '' && $_POST['awayGoals'] != '' ? 'NOW()' : 'NULL') . " "
      . "WHERE matchId=$match";
    mysql_query($q, $db) or die($q . '<br>' . mysql_error());    
  } else {
    // add new match
    $q = 'INSERT INTO MATCHES '
      . '(gameId, roundNo, title, homeTeam, awayTeam, homeGoals, awayGoals, neutralStadium, startPredicting, noDraw, deadline, resultTime) VALUES ('
      . $game . ', '
      . ($_POST['roundNo'] != '' ? $_POST['roundNo'] : 'NULL') . ", "
      . ($_POST['title'] != '' ? "'".$_POST['title']."'" : 'NULL') . ", '"
      . $_POST['homeTeam'] . "', '"
      . $_POST['awayTeam'] . "', "
      . "NULL, "
      . "NULL, "
      . $_POST['neutralStadium'] . ", '"
      . $_POST['startPredicting'] . "', "
      . $_POST['noDraw'] . ", '"
      . $_POST['deadline'] . "', NULL)";
    mysql_query($q, $db) or die($q . '<br>' . mysql_error());    
    $match = mysql_insert_id($db);
  }
}

// display the form
echo '<a href="adm_game.php?game=' . $game . '">Powrót</a><br>';
echo '<h2>Mecz</h2>';

if ($match > 0) {
  // existing match
  $q = 'SELECT * FROM MATCHES WHERE matchId=' . $match;
  $res = mysql_query($q, $db) or die(mysql_error());
  $r = mysql_fetch_array($res, MYSQL_ASSOC) or die("no such match: $match");
  mysql_free_result($res);
} else {
  // new match
  $r = array();
}

// display the form with game details
?>
<form method="POST" action="adm_match.php?game=<? echo $game; ?>">
<input type="hidden" name="save" value="1">
<input type="hidden" name="matchId" value="<? echo $match; ?>">
<table>
<tr><td>Gospodarze:</td><td><input name="homeTeam" value="<? echo $r['homeTeam']; ?>"></td></tr>
<tr><td>Go¶cie:</td><td><input name="awayTeam" value="<? echo $r['awayTeam']; ?>"></td></tr>
<?
if ($match > 0) {
  ?>
  <tr><td>Wynik:</td><td><input name="homeGoals" value="<? echo $r['homeGoals']; ?>" size="2">:<input name="awayGoals" value="<? echo $r['awayGoals']; ?>" size="2">
  zwyciêzca: <select name="result">
  <option value="" <? if ($r['result'] == '') echo 'selected'; ?>>--
  <option value="1" <? if ($r['result'] == '1') echo 'selected'; ?>>1
  <option value="X" <? if ($r['result'] == 'X') echo 'selected'; ?>>X
  <option value="2" <? if ($r['result'] == '2') echo 'selected'; ?>>2
  </select></td></tr>
  <?
}
?>
<tr><td>Nr kolejki:</td><td><input name="roundNo" value="<? echo $r['roundNo']; ?>" size="2"></td></tr>
<tr><td>Etap (æwieræfina³, pó³fina³ itp.):</td><td><input name="title" value="<? echo $r['title']; ?>"></td></tr>
<tr><td>Remis niedozwolony:</td><td><select name="noDraw">
<option value="0" <? if (! $r['noDraw']) echo 'selected'; ?>>Nie
<option value="1" <? if ($r['noDraw']) echo 'selected'; ?>>Tak
</select></td></tr>
<tr><td>Neutralny stadion:</td><td><select name="neutralStadium">
<option value="0" <? if (! $r['neutralStadium']) echo 'selected'; ?>>Nie
<option value="1" <? if ($r['neutralStadium']) echo 'selected'; ?>>Tak
</select></td></tr>
<tr><td>Start obstawiania (yyyy-mm-dd HH:MM:SS):</td><td><input name="startPredicting" value="<? echo $r['startPredicting']; ?>"></td></tr>
<tr><td>Koniec obstawiania (yyyy-mm-dd HH:MM:SS):</td><td><input name="deadline" value="<? echo $r['deadline']; ?>"></td></tr>
</table>
<input type="submit" value="Zapisz"/>
</form>

<?

if ($match > 0) {
  ?>
  <form name="delForm" method="POST" action="adm_match.php?game=<? echo $game; ?>">
  <input type="hidden" name="delete" value="1">
  <input type="hidden" name="matchId" value="<? echo $match; ?>">
  <button type="button" onClick="if (confirm('Czy na pewno usun±æ?')) { document.forms['delForm'].submit(); }">Usuñ</button>
  </form>
  <?
}

include 'lib/admin/inc_post.php';
?>