{config_load file="test.conf" section="setup"}
{include file="header.tpl" title="Predictor -- obstawianie"}
{include file="bar.tpl"}
{include file="title.tpl" game=$game.name}

<form method="POST" action="predict.php?game={$gameId}">
<input type="hidden" name="savePred" value="1"/>
<table>
<tr><td></td><td></td><td>zamkniêcie typowania:</td></tr>
{section name=sec1 loop=$currentMatches}
        {if $currentMatches[sec1].showRound != 0}
	{if $currentMatches[sec1].title != ""}
	<tr><td colspan="3" class="predRound"><br>{$currentMatches[sec1].title}</td></tr>
	{else}
	{if $currentMatches[sec1].roundNo != ""}
	<tr><td colspan="3" class="predRound"><br>{$currentMatches[sec1].roundNo} kolejka</td></tr>
	{/if}
	{/if}
	{/if}
	<tr>
	<td class="predFixture">{$currentMatches[sec1].homeTeam} &ndash; {$currentMatches[sec1].awayTeam}</td>
	<td>
	<select name="hg_{$currentMatches[sec1].matchId}">
	{html_options values=$goalVals output=$goalOutput selected=$currentMatches[sec1].predHomeGoals}
	</select>
 	:
	<select name="ag_{$currentMatches[sec1].matchId}">
	{html_options values=$goalVals output=$goalOutput selected=$currentMatches[sec1].predAwayGoals}
	</select>
	{if $currentMatches[sec1].noDraw == 1}
		zwyciêzca:
		<select name="res_{$currentMatches[sec1].matchId}">
		{html_options values=$resultVals output=$resultOutput selected=$currentMatches[sec1].predResult}
		</select>
	{/if}
	<input type="hidden" name="nd_{$currentMatches[sec1].matchId}" value="{$currentMatches[sec1].noDraw}">
	</td>
	<td>{$currentMatches[sec1].deadline}</td>
	</tr>
{/section} 
</table>
<br>
<input type="submit" value="Zapisz"/>
</form>

{include file="footer.tpl"}
