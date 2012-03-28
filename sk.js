function sel(obj)
{
	return obj.options[obj.selectedIndex];
}

/* Raid Selection */
function move(b) 
{
	var full = document.getElementById('full');
	var raid = document.getElementById('raid');
	
	// move item
	if (b)
		raid.add(sel(full), null);
	else
		full.add(sel(raid), null);
	
	// update raid-count
	document.getElementById('count').innerHTML = raid.length;

}

function selectAll()
{
	var raid = document.getElementById('raid');

	for (var i = 0; i < raid.options.length; i++)
	{
		raid.options[i].selected = true;
	}
}
function selectAll(selectBox,selectAll) {
  // have we been passed an ID
  if (typeof selectBox == "string") {
    selectBox = document.getElementById(selectBox);
  }
  // is the select box a multiple select box?
  if (selectBox.type == "select-multiple") {
    for (var i = 0; i < selectBox.options.length; i++) {
      selectBox.options[i].selected = selectAll;
    }
  }
}

function isEmpty() {
	if(document.getElementById('raid').length == 0)
		return false;
	else
		if (document.getElementById('raidname').value.length > 0)
			return true;
	return false;
}

/* Loot Distribution */
function selectPlayer()
{
	var raid = document.getElementById('raid');
	document.getElementById('player').value = sel(raid).value;
	document.getElementById('lootplayer').innerHTML = sel(raid).text;
}

var xmlHttp;
function selectItem()
{
	var item = document.getElementById('item');
	var link = document.getElementById('itemlink');
		
	xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
		{
			elem = xmlHttp.responseXML.documentElement;
			
			if (elem.getElementsByTagName("error").length > 0)
			{
				link.innerHTML = '<img class="item" src="http://www.wow-castle.de/bboard/images/proxy.php?item=-1">&nbsp;<a class="error">Ung&uuml;ltiges Item</a>';
				document.getElementById('itemvalid').value = -1;
			} 
			else 
			{
				name = elem.getElementsByTagName('name')[0].textContent;
				quality = elem.getElementsByTagName('quality')[0].getAttribute('id');
				
				link.innerHTML = '<img class="item" src="http://www.wow-castle.de/bboard/images/proxy.php?item='+item.value+'">&nbsp;<a class="q'+quality+'" rel="item='+item.value+',domain=de">'+ name +'</a>';
				document.getElementById('itemvalid').value = 1;
			}
		}
	}
	xmlHttp.open("GET", 'itemxml.php?item='+item.value+'', true);
	xmlHttp.send();
}

function setLootMode(m)
{
	document.getElementById('lootmode').value = m;
}

function isLootEntryComplete()
{
	// Player, Item, Lootmode
	var player = document.getElementById('player').value != -1 ? true : false;
	var lootmode = document.getElementById('lootmode').value != -1 ? true : false;
	var item = document.getElementById('itemvalid').value != -1 ? true : false;
	
	alert(player + ' ' + lootmode + ' ' + item);
	
	if (player && lootmode && item)
		return true;
	
	return false;

}