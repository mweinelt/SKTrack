<div id="container">
	<ul id="navlist">
		{foreach key=id item=tab from=$lists}
			<li{if $id == $list_sel} id="active"{/if}><a{if $id == $list_sel} id="current"{/if} href="?list={$id}">{$tab['title']}</a></li>
		{/foreach}
		{if $logged_in}
		<form name="addlist" action="index.php" method="post">
		<li style="padding-left: 5px;">
			<input type="hidden" name="do" value="addlist">
			<input class="text" type="text" name="title">
			<input style="border: 0px;" type="submit" value="&#43;">
		</li>		
		</form>
		{/if}
	</ul>
	<div id="content">
	{if isset($content)}
		{$content}
	{else}
		{include file=$template}
	{/if}
	<br /></div>
</div>