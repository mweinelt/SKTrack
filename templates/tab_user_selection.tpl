<h2>Raid-Teilnehmer ausw&auml;hlen</h2>
<table>
	<tr>
		<td width="350">Gesamtliste</td>
		<td width="350">Aktueller Raid (<div style="display:inline;" id="count">0</div>)</td>
	</tr>
	<tr>
		<!-- Complete List -->
		<td style="vertical-align: top;">
			<select id="full" name="full" size="50" multiple="multiple" onclick="move(true);">
			{foreach $pool as $player}
				<option value="{$player['uid']}">({$player['pos'] + 1}) {$player['name']}</option>
			{/foreach}
			</select>
			<p>1. Spieler hinzuf&uuml;gen:
			<form id="adduser" method="post" action="index.php">
				<input type="hidden" name="do" value="adduser" />
				<input type="hidden" name="list" value="{$list_sel}" />
				<input class="text" type="text" name="name" style="width: 280px;" autofocus="autofocus" />
				<input style="border: 0px;" type="submit" value="&#187;" />
			</form></p>
			<p>2. Spieler in den Raid einf&uuml;gen.</p>
		<!-- Raid List -->
		</td>
		<td style="vertical-align: top;">
			<form id="sublist" action="index.php" method="post" onsubmit="selectAll();">
				<input type="hidden" name="do" value="startraid" />
				<input type="hidden" name="list" value="{$list_sel}" />
				<select id="raid" name="raid[]" size="50" multiple="multiple" onclick="move(false);">
				<!-- loop only for debugging purposes -->
				{foreach $raid as $player}
					<option value="{$player['uid']}">({$player['pos']}) {$player['name']}</option>
				{/foreach}
				</select>
				<p>3. Raid fertig? Titel eingeben...<br />
				&nbsp;<br />
				<input class="text" type="text" id="raidname" name="raidname" style="width: 315px;" /><br /><br />
				...dann weiter zur Lootvergabe!</br />
				<input class="button" onclick="return isEmpty();" type="submit" value="Raid starten" /></p>
			</form>
		</td>
	</tr>
</table>