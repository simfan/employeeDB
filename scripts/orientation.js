function fieldChecked(field, category, categoryCount, categoryTotal)
{
	myField = document.getElementById(field);
	textName = field.toLowerCase() + "Text";
	textField = document.getElementById("'" + textName+ "'");
	myCount = document.getElementById(categoryCount).value;
	if(myField.checked)
	{
		myCount++;
		document.getElementById(textName).style.color = "green";
	}
	else
	{
		myCount--;
		document.getElementById(textName).style.color = "black";
	}
	
	document.getElementById(categoryCount).value = myCount;
	
	if(document.getElementById(categoryCount).value == categoryTotal)
		document.getElementById(category).style.color = "green";
	else
		document.getElementById(category).style.color = "black";

	welcomeCount = parseInt(document.getElementById('welcomeCount').value);
	formsCount = parseInt(document.getElementById('formsCount').value);
	handbookCount = parseInt(document.getElementById('handbookCount').value);
	videoCount = parseInt(document.getElementById('videoCount').value);
	jobCount = parseInt(document.getElementById('jobCount').value);
	safetyCount = parseInt(document.getElementById('safetyCount').value);
	
	total = welcomeCount+formsCount+handbookCount+videoCount+jobCount+safetyCount;
	
	if(total == 34)
		document.getElementById('submit').disabled = false;
	else
		document.getElementById('submit').disabled = true;
}