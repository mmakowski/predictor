{config_load file="test.conf" section="setup"}
{include file="header.tpl" title="Predictor"}
{include file="bar.tpl"}

<br>
<table width="100%" cellpadding="0" cellspacing="10" border="0">
<tr><td class="secTitle" width="50%">Rozgrywki</td></tr>
<tr>
<td class="pageFrame">
<table>
{section name=sec1 loop=$currentGames}
	<tr><td class="game">{$currentGames[sec1].name}</td></tr>
	<tr><td class="gameLinks"><a href="predict.php?game={$currentGames[sec1].gameId}">Typuj</a> 
	&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="game.php?id={$currentGames[sec1].gameId}">Wyniki</a> 
	</td></tr>
	<tr><td></td></tr>
{/section} 
</table>
{if $availGamesCount > 0}
<br><a href="join.php">We¼ udzia³ w nowych rozgrywkach</a>
{/if}
<br>
</td>
</tr>

<tr><td class="secTitle" width="50%">Zakoñczone</td></tr>
<tr>
<td class="pageFrame">
<table>
{section name=sec1 loop=$closedGames}
	<tr>
	<td><a href="game.php?id={$closedGames[sec1].gameId}" class="game">{$closedGames[sec1].name}</a></td>
	<td>{$closedGames[sec1].rank}</td>
	<td>{$closedGames[sec1].result}</td>
	</tr>
{/section} 
</table>
</td>
</tr>
</table>


{include file="footer.tpl"}
