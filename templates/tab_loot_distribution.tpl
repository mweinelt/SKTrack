<h2>Lootvergabe</h2>
<table>
	<tr>
		<td width="350">Raid</td>
		<td width="450">&nbsp;</td>
	</tr>
		<!-- raid list -->
		<td style="vertical-align: top;">
			<form id="endraid" action="index.php" method="post">
				<input type="hidden" name="list" value="{$list_sel}">
				<input type="hidden" name="do" value="endraid">
			</form>
			<form id="suicide" method="post" action="index.php" onsubmit="return isLootEntryComplete();">
				<input type="hidden" name="do" value="distribute" />
				<input type="hidden" name="list" value="{$list_sel}" />
				<input type="hidden" name="player" id="player" value="-1" />
				<input type="hidden" name="lootmode" id="lootmode" value="-1" />
				<input type="hidden" name="itemvalid" id="itemvalid" value="-1" />
				<select id="raid" name="raid" size="26" onclick="selectPlayer();">
				{foreach $raid as $player}
					<option value="{$player['uid']}">({$player['pos'] + 1}) {$player['name']}</option>
				{/foreach}
				</select>

		</td>
		<!-- item distribution -->
		<td style="padding-top: 20px; vertical-align: top;">
			<p><span style="font-size: 13pt; font-weight: bold;">#{$raid_sel}: {$raid_title}</span><br />
			seit {$raid_start}
			<input onclick="document.getElementById('endraid').submit();" class="button" type="button" value="Raid beenden">
			</p>
			<table class="shadow" width="400">
				<tr>
					<td style="border-bottom: 2px solid grey; background: black; font-weight: bold;" colspan="2">Item vergeben</td>
				</tr>
				<tr>
					<td width="120">Spieler:</td>
					<td><div id="lootplayer"></div></td>
				</tr>
				<tr>
					<td width="120">Item:</td>
					<td><input class="text" type="text" name="item" id="item" onchange="selectItem();" /></td>
				</tr>
				<tr>
					<td></td><td style="vertical-align: middle;"><div name="itemlink" id="itemlink"></div></td>
				</tr>
				<tr>
					<td colspan="2"><input class="button" onclick="setLootMode(0);" type="submit" value="Suicide" /><input class="button" onclick="setLootMode(1);" type="submit" value="2nd" /><input class="button" onclick="setLootMode(2);" type="submit" value="Disenchant / Sell" /></form></td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<br />
			<table style="border: 2px solid grey;" width="890">
				<tr>
					<td style="border-bottom: 2px solid grey; background: blue; font-weight: bold;" colspan="5">Letzte Items</td>
				</tr>
				{foreach $items as $item}
				<tr>
					<td width="200">{$item['username']}</td>
					<td width="450"><a class="q{$item['item_quality']}" rel="domain=de,item={$item['item_id']}"><img src="http://www.wow-castle.de/bboard/images/proxy.php?item={$item['item_id']}" class="item">&nbsp;{$item['item_name']}</a></td>
					<td width="160">{$item['lootmode']}</td>
					<td width="120">{$item['loottime']}</td>
					<td width="60" style="text-align: center;">
					<!-- allow to revert last step ONLY -->
					{if $item['revert'] == true}
						<form id="revert" action="index.php" method="post">
						<input type="hidden" name="do" value="revert" />
						<input type="hidden" name="raid_id" value="{$raid_sel}" />
						<input type="hidden" name="list" value="{$list_sel}" />
						<input type="submit" value="&#8634;" />
						</form>
					{/if}
					</td>
				</tr>
				{/foreach}
			</table>
		</td>
	</tr>
</table>
