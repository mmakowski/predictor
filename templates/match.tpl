{config_load file="test.conf" section="setup"}
{include file="header.tpl" title="Predictor"}
{include file="bar.tpl"}
{include file="title.tpl" game=$game.name}

{if $match.title != ""}
<font class="round">{$match.title}</font>
<br><br>
{else}
{if $match.roundNo != ""}
<font class="round">{$match.roundNo} kolejka</font>
<br><br>
{/if}
{/if}
<table border="0">
<tr><td class="bigresHome" width="250">{$match.homeTeam}</td><td class="bigresCenter">&ndash;</td><td class="bigresAway" width="250">{$match.awayTeam}</td></tr>
{if $match.homeGoals != ""}
<tr><td class="bigresHome">{$match.homeGoals}</td><td class="bigresCenter">:</td><td class="bigresAway">{$match.awayGoals}</td></tr>
{if ($match.noDraw == 1) and ($match.homeGoals == $match.awayGoals)}
<tr><td colspan="3" class="bigresExtra">w rzutach karnych zwyciê¿a 
  {if $match.result == 1}
    {$match.homeTeam}
  {else}
    {$match.awayTeam}
  {/if}
</td></tr>
{/if}
{/if}
</table>

<table width="100%" cellspacing="10">
{if $showResults}
<tr><td class="secTitle">Wyniki</td></tr>
<tr>
<td class="pageFrame">
<table cellspacing="0" cellpadding="3" class="ranking">
<tr class="rankingHead">
{if $match.homeGoals != ""}
<td class="rankingHead">miejsce</td>
{/if}
<td class="rankingHead">gracz</td><td class="rankingHead">typ</td>
{if $match.homeGoals != ""}
<td class="rankingHead">punkty</td>
<!-- TODO: tymczasowe szczegoly dla systemu kmienik1 - powinno byc zrobione bardziej ogolnie -->
<td class="rankingHead2">res</td>
<td class="rankingHead2">hg</td>
<td class="rankingHead2">ag</td>
<td class="rankingHead2">dif</td>
<td class="rankingHead2">sum</td>
{/if}
</tr>
{section name=sec1 loop=$matchResults}
	<tr {if $matchResults[sec1].userId == $user.id}class="rankingSelRow"{/if}>
	{if $match.homeGoals != ""}
	<td>{if $matchResults[sec1].showRowNum != 0}{%sec1.rownum%}.{/if}</td>
	{/if}
	<td><b>{$matchResults[sec1].displayName}</b></td>
	<td>{$matchResults[sec1].homeGoals}:{$matchResults[sec1].awayGoals} 
	{if ($match.noDraw == 1) and ($matchResults[sec1].homeGoals == $matchResults[sec1].awayGoals)}
	({$matchResults[sec1].result})
	{/if}
	</td>
	{if $match.homeGoals != ""}
	<td><b>{$matchResults[sec1].points}</b></td>
	<td>{$matchResults[sec1].resNW}</td>
	<td>{$matchResults[sec1].hgNW}</td>
	<td>{$matchResults[sec1].agNW}</td>
	<td>{$matchResults[sec1].difNW}</td>
	<td>{$matchResults[sec1].sumNW}</td>
	{/if}
	</tr>
{/section} 
</table>
<br/>
<img src="res/scatter.php?s={$match.homeGoals}:{$match.awayGoals}&p={$scatterPlotPoints}&h={$scatterPlotHighlight}"/>

</td>
</tr>
{/if}
</table>

{include file="footer.tpl"}
