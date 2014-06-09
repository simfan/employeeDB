function getSkills()
{
	var xmlhttp;
	var deptName = dept.value;
	if(window.XMLHttpRequest)
	{
		//code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		//code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			document.getElementById('skill').innerHTML = xmlhttp.responseText;
		}
	}
	//&#38; - ampersand
	xmlhttp.open('GET', 'processes/get_skils.php?dept='+dept, true);
	xmlhttp.send();
}


