<?php

require 'lib/defs.php';
require 'lib/common.php';

if ($_POST['delete'] == 1) {
  // delete the game
  $db = getDB();
  $q = 'DELETE FROM USER_GAMES WHERE gameId=' . $_POST['gameId'];
  mysql_query($q, $db) or die(mysql_error());      
  $q = 'DELETE FROM GAMES WHERE gameId=' . $_POST['gameId'];
  mysql_query($q, $db) or die(mysql_error());      

  // redirect to main admin page
  header("Location: http://" . $_SERVER['HTTP_HOST']
	 . dirname($_SERVER['PHP_SELF'])
	 . "/admin.php");        
  mysql_close($db);
  exit;
}


include 'lib/admin/inc_pre.php';

$game = $_GET['game'];

if ($_POST['save'] == 1) {
  // save the submitted game
  $game = $_POST['gameId'];
  if ($game > 0) {
    // update existing game
    $q = "UPDATE GAMES SET name='"
      . $_POST['name'] . "', dateStart='"
      . $_POST['dateStart'] . "', dateEnd='"
      . $_POST['dateEnd'] . "' WHERE gameId=$game";
    mysql_query($q, $db) or die(mysql_error());    
  } else {
    // add new game
    $q = "INSERT INTO GAMES (name, dateStart, dateEnd) VALUES ('"
      . $_POST['name'] . "', '"
      . $_POST['dateStart'] . "', '"
      . $_POST['dateEnd'] . "')";
    mysql_query($q, $db) or die(mysql_error());
    $game = mysql_insert_id($db);
  }
}

// display the form
echo '<a href="admin.php">Powrót</a><br>';

if ($game > 0) {
  // existing game
  $q = 'SELECT * FROM GAMES WHERE gameId=' . $game;
  $res = mysql_query($q, $db) or die(mysql_error());
  $r = mysql_fetch_array($res, MYSQL_ASSOC) or die("no such game: $game");
  mysql_free_result($res);
  echo '<table><tr><td vAlign="top">';
} else {
  // new game
  $r = array();
}

// display the form with game details
?>
<h2>Rozgrywki</h2>
<form method="POST" action="adm_game.php">
<input type="hidden" name="save" value="1">
<input type="hidden" name="gameId" value="<? echo $game; ?>">
<table>
<tr><td>Nazwa:</td><td><input name="name" value="<? echo $r['name']; ?>"></td></tr>
<tr><td>Czas rozpoczêcia (yyyy-mm-dd HH:MM:SS):</td><td><input name="dateStart" value="<? echo $r['dateStart']; ?>"></td></tr>
<tr><td>Czas zakoñczenia (yyyy-mm-dd HH:MM:SS):</td><td><input name="dateEnd" value="<? echo $r['dateEnd']; ?>"></td></tr>
</table>
<input type="submit" value="Zapisz"/>
</form>

<?

if ($game > 0) {

  ?>
  <form name="delForm" method="POST" action="adm_game.php">
  <input type="hidden" name="delete" value="1">
  <input type="hidden" name="gameId" value="<? echo $game; ?>">
  <button type="button" onClick="if (confirm('Czy na pewno usun±æ?')) { document.forms['delForm'].submit(); }">Usuñ</button>
  </form>
  <?

  echo '</td><td vAlign="top">';
  $q = 'SELECT * FROM MATCHES WHERE gameId=' . $game . ' ORDER BY resultTime, deadline';
  $res = mysql_query($q, $db) or die(mysql_error());
  echo '<h2>Mecze</h2>';
  echo '<a href="adm_match.php?game=' . $game . '">Nowy mecz</a><br>';
  echo '<table>';
  while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
    echo '<tr><td><a href="adm_match.php?game=' . $game . '&match=' . $r['matchId'] . '">'
      . $r['homeTeam'] . ' &ndash; ' . $r['awayTeam'] . '</a></td><td>';
    if ($r['homeGoals'] != '') {
      echo ' ' . $r['homeGoals'] . ':' . $r['awayGoals'];
    }
    echo '</td></tr>';
  }
  echo '</table>';
  echo '</td></tr></table>';
}

include 'lib/admin/inc_post.php';
?>