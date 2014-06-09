function highlightCells(field, row)
{
	var className = field.className;
	var columnCells = document.getElementsByClassName(className);
	for(var i = 0; i < columnCells.length; i++)
	{
		if(columnCells[i].tagName == 'TH')
			columnCells[i].style.background = "#FF9911";
		
		if(columnCells[i].tagName == 'TD')
			columnCells[i].style.background = "#00C0FF";
	}
	field.style.background = "#0080FF";
	document.getElementById(row).style.background = "#00C0FF";
	document.getElementById(row).getElementsByClassName('skillField')[0].style.background = "#FF9911";
}

function whiteCells(field, row)
{
	var className = field.className;
	var columnCells = document.getElementsByClassName(className);
	for(var i = 0; i < columnCells.length; i++)
	{
		columnCells[i].style.background = "";
	}
	document.getElementById(row).style.background = "white";
	document.getElementById(row).getElementsByClassName('skillField')[0].style.background = "";
	
}