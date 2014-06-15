{config_load file="test.conf" section="setup"}
{include file="header.tpl" title="Predictor"}
{include file="bar.tpl"}

<table width="100%" cellspacing="10">

<tr><td class="secTitle" width="50%">Dostêpne rozgrywki</td></tr>
<tr><td>
<table>
{section name=sec1 loop=$availGames}
	<tr><td class="game">{$availGames[sec1].name}</td></tr>
	<tr><td class="gameLinks"><a href="join.php?game={$availGames[sec1].gameId}">Do³±cz</a> 
	&nbsp;&nbsp;|&nbsp;&nbsp;
	<a href="game.php?id={$availGames[sec1].gameId}">Wyniki</a> 
	</td></tr>
	<tr><td></td></tr>
{/section}  
</table>

</td></tr>
</table>
{include file="footer.tpl"}
