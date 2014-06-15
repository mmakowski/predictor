<?php

require 'lib/defs.php';
require 'lib/common.php';

include 'lib/admin/inc_pre.php';


// new game link
echo '<a href="adm_game.php">Nowe rozgrywki</a><br>';

// current games
$q = 'SELECT * FROM GAMES WHERE dateEnd > NOW() ORDER BY dateStart, dateEnd';
$res = mysql_query($q, $db) or die(mysql_error());
echo '<table>';
while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
  echo '<tr><td><a href="adm_game.php?game=' . $r['gameId'] . '">'
    . $r['name'] . '</a></td></tr>';
}
echo '</table>';
mysql_free_result($res);

echo '<hr>';

// closed games
$q = 'SELECT * FROM GAMES WHERE dateEnd <= NOW() ORDER BY dateStart DESC, dateEnd DESC';
$res = mysql_query($q, $db) or die(mysql_error());
echo '<table>';
while ($r = mysql_fetch_array($res, MYSQL_ASSOC)) {
  echo '<tr><td><a href="adm_game.php?game=' . $r['gameId'] . '">'
    . $r['name'] . '</a></td></tr>';
}
echo '</table>';
mysql_free_result($res);

include 'lib/admin/inc_post.php';
?>