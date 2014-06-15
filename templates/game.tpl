{config_load file="test.conf" section="setup"}
{include file="header.tpl" title="Predictor"}
{include file="bar.tpl"}
{include file="title.tpl" game=$game.name}

<table width="100%" cellspacing="0" cellpadding="0">
<tr><td>

<table width="100%" cellspacing="10">

<tr><td class="secTitle">Ranking</td><tr>

<td class="pageFrame">
<table cellspacing="0" cellpadding="3" class="ranking">
<tr class="rankingHead"><td class="rankingHead">miejsce</td><td class="rankingHead">gracz</td><td class="rankingHead" colspan="2">¶rednia</td><td class="rankingHead">mecze</td></tr>
{section name=sec1 loop=$gameRanking}
	<tr {if $gameRanking[sec1].userId == $user.id}class="rankingSelRow"{/if}>
	<td>{if $gameRanking[sec1].showRowNum != 0}{%sec1.rownum%}.{/if}</td>
	<td><b>{$gameRanking[sec1].displayName}</b></td>
	<td><b>{$gameRanking[sec1].points}</b></td>
	<td><img src="res/bar.php?b={$gameRanking[sec1].normPoints}"></td>
	<td>{$gameRanking[sec1].matches}</td>
	</tr>
{/section} 
{if $showUnqualified != 0}
	<tr><td colspan="5"><b>niesklasyfikowani:</b></td></tr>
{section name=sec1 loop=$unqualified}
	<tr {if $unqualified[sec1].userId == $user.id}class="rankingSelRow"{/if}>
	<td>{if $unqualified[sec1].showRowNum != 0}{%sec1.rownum%}.{/if}</td>
	<td><b>{$unqualified[sec1].displayName}</b></td>
	<td><b>{$unqualified[sec1].points}</b></td>
	<td><img src="res/bar.php?b={$unqualified[sec1].normPoints}"></td>
	<td>{$unqualified[sec1].matches}</td>
	</tr>
{/section} 
{/if}
</table>
<br><br>
</td>
</tr>

{if $showClosedMatches}
<tr><td class="secTitle">Mecze rozgrywane</td></tr>
<tr>
<td rowspan="3" class="pageFrame">
<table cellspacing="0" cellpadding="2">
{section name=sec1 loop=$closedMatches}
        {if $closedMatches[sec1].showRound != 0}
	{if $closedMatches[sec1].title != ""}
	<tr><td colspan="2" class="gameRound"><br>{$closedMatches[sec1].title}</td></tr>
	{else}
	{if $closedMatches[sec1].roundNo != ""}
	<tr><td colspan="2" class="gameRound"><br>{$closedMatches[sec1].roundNo} kolejka</td></tr>
	{/if}
	{/if}
	{/if}
	<tr>
	<td><a href="match.php?id={$closedMatches[sec1].matchId}" class="match">{$closedMatches[sec1].homeTeam} &ndash; {$closedMatches[sec1].awayTeam}</a></td>
	</tr>
{/section} 
</table>
</td>
</tr>
{/if}

</table>

</td>
<td >


<table width="100%" cellspacing="10">

<tr><td class="secTitle" width="50%">Zakoñczone mecze</td></tr>

<td class="pageFrame">
<table cellspacing="0" cellpadding="2">
{section name=sec1 loop=$finishedMatches}
        {if $finishedMatches[sec1].showRound != 0}
	{if $finishedMatches[sec1].title != ""}
	<tr><td colspan="2" class="gameRound"><br>{$finishedMatches[sec1].title}</td></tr>
	{else}
	{if $finishedMatches[sec1].roundNo != ""}
	<tr><td colspan="2" class="gameRound"><br>{$finishedMatches[sec1].roundNo} kolejka</td></tr>
	{/if}
	{/if}
	{/if}
	<tr>
	<td><a href="match.php?id={$finishedMatches[sec1].matchId}" class="match">{$finishedMatches[sec1].homeTeam} &ndash; {$finishedMatches[sec1].awayTeam}</a></td>
	<td>{$finishedMatches[sec1].homeGoals}:{$finishedMatches[sec1].awayGoals}
	{if ($finishedMatches[sec1].noDraw == 1) and ($finishedMatches[sec1].homeGoals == $finishedMatches[sec1].awayGoals)}
	({$finishedMatches[sec1].result})
	{/if}
	</td>
	<td><img src="res/bar.php?b={$finishedMatches[sec1].normYourScore}&l={$finishedMatches[sec1].normAverageScore}&sd={$finishedMatches[sec1].normStdDev}">
	</td>
	</tr>
{/section} 
</table>
</td>
</tr>


</table>

</td></tr>
</table>


{include file="footer.tpl"}
