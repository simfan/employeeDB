function fillInfoBox(currentLink)
{
	var linkTo = currentLink.href;
	//alert(linkTo);
	var regEx1 = /search.php$/;
	var regEx2 = /trainingIndex.html$/;
	var regEx3 = /orientation.html$/;
	var regEx4 = /training_matrix.php$/;
	var myInfo;

		if(regEx1.test(linkTo) == true)
			myInfo = "Search the employee records for specific skills and/or employees";
		
		if(regEx2.test(linkTo) == true)
			myInfo = "Go to the training form index";
			
		if(regEx3.test(linkTo) == true)
			myInfo = "Fill out an employee orientation form.  If the employee is brand new, you will also have to fill out a new employee record";
			
		if(regEx4.test(linkTo) == true)
			myInfo = "A table that displays all skills and all employees of this plant. Each cell shows the date each employee was trained in each skill.";
	
	
	document.getElementById("infoBox").innerHTML = myInfo;
}

function emptyInfoBox()
{
	document.getElementById("infoBox").innerHTML = "";
}