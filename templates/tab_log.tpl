<h2>Rangliste und Item-Log</h2>
<table>
	<tr>
		<td style="vertical-align: top;">
			<table>
				<tr>
					<td style="vertical-align: top;">
						<table style="border: 2px solid grey;" width="250">
							<tr style="background: black; font-weight: bold;"><td style="border-bottom: 2px solid grey;" width="40">#</td><td style="border-bottom: 2px solid grey;">Spieler</td></tr>
							<!-- Complete List -->
							{foreach $pool as $player}
							<tr {if ($player['active'] || $lists[$list_sel]['active_raid'] == -1) }style="color: white;"{else}style="color: grey;"{/if}>
								<td>{$player['pos'] + 1}</td>
								<td>{$player['name']}</td>
							</tr>
							{/foreach}
						</table>
					</td>
					<td>&nbsp;</td>
					<td style="vertical-align: top;">
						<table style="border: 2px solid grey;" width="910">
							<tr>
								<td style="border-bottom: 2px solid grey; background: black; font-weight: bold;" colspan="4">Letzte Items</td>
							</tr>
							<!-- Item Log -->
							{foreach $items as $raid}
							<tr>
								<td colspan="4" style="background: black; border-bottom: 2px solid grey;">
									<table width="100%">
										<tr>
											<td>{$raid['0']['raid_title']}</td>
											<td style="text-align: right;">{$raid['0']['raid_start']} - {$raid['0']['raid_end']}</td>
										</tr>
									</table>
								</td>
							</tr>
							{foreach $raid as $item}
							<tr>
								<td width="200">{$item['username']}</td>
								<td width="450"><a class="q{$item['item_quality']}" rel="domain=de,item={$item['item_id']}"><img src="http://www.wow-castle.de/bboard/images/proxy.php?item={$item['item_id']}" class="item">&nbsp;{$item['item_name']}</a></td>
								<td width="160">{$item['lootmode']}</td>
								<td width="120">{$item['loottime']}</td>
							</tr>
							{/foreach}
							{/foreach}
						</table>
					</td>
				</tr>
			</table>
	</tr>
</table>