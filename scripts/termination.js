function verify(f)
{

	var fieldCount = 62;
	var rField = new Array(fieldCount);
	rField[0] = "Employee Number";
	rField[1] = "Date Issued";
	for(var i = 2; i < 56; i++)
	{
		rField[i] = "";
	}
	rField[56] = "Employee Signature";
	rField[57] = "Trainer Signature";
	rField[58] = "Supervisor Signature"
	rField[59] = "Employee Signature Date";
	rField[60] = "Trainer Signature Date";
	rField[61] = "Supervisor Signature Date";
	var firstError = false;
	var errorField;
	var blankCheck = false;
	
	var checkCount = 0;
	alert("Check count: " + checkCount);
	for(var i  = 0; i < f.length; i++)
	{
		var e = f.elements[i];
		//alert(rField[i] + " is type: " + e.type);
		if(e.type == "text")
		{
			if((e.value == null) || (e.value == ""))// || isblank(e))
			{
				blankCheck = true;
			}
			else
			{
				blankCheck = false;
			}

			if(e.required == true)// && e.getAttribute("optional") == null)
			{
				alert(rField[i] + " is required");
				alert("Blank Check is " + blankCheck);
				if(blankCheck == true)
				{
					errors = true;
					if(!firstError)
					{
						errorField = e;
						alert(i + ". Please enter the " + rField[i]);
						errorField.focus();
						firstError = true;
					}
					continue;
				}
				else
				{
					alert(rField[i] + " is not blank");
					alert("Date Check: " + e.dateCheck);
					if(e.dateCheck)
					{
						alert(rField[i] + " must be a date");
						//firstError = checkDateFormat(e, rField[i], firstError);
					}
					
					if(e.numberTest == true || e.numeric == true)
					{
						alert("Number Test");
						firstError = checkNumber(e, rField[i], firstError);
					}
					
					//if(
				}
			}
			else
			{
				alert("Field " + i + " is optional");
				if(e.dateCheck)
				{
					alert(rField[i] + " must be a date");
					//firstError = checkDateFormat(e, rField, firstError);
				}
				
				if(e.numberTest == true || e.numeric == true)
				{
					alert("Number Test");
					firstError = checkNumber(e, rField[i], firstError);
				}
			}
		}
	}
	
	if(firstError)
	{
		errors = true;
		errorField = e;
	}
	
	if(!errors)
		return true;
	else
		return false;
}


function checkDateFormat(e, fieldName, firstError)
{
	var v = e.value;
	var dateFormat = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/;
	if(!dateFormat.test(v))
	{
		alert("The " + fieldName + " must be in the following format: mm-dd-yyyy");
		e.focus();
		firstError = true;
	}
	
	return firstError;
}



function numericTest(e, fieldName, firstError)
{
	var v = e.value;
	if (isNaN(v))
	{
		if (!firstError)
		{
			alert("The " + fieldName + " must be numeric.");
			e.focus();
			firstError = true;
		}
	}
	return firstError;
}
		

function checkNumber(empNum, fieldName, firstError)
{
	//alert(empNum);
	var xmlhttp;
	var deptName = empNum.value;
	//alert(deptName);
	//return firstError;
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
			var numExists = xmlhttp.responseText;
			if(numExists == "false")
			{
				document.getElementById(fieldName).style.color = "red";
				alert("The employee number you entered does not exist");
				empNum.focus();
				firstError = true;
			}
			else
			{
				
				firstError = false;
			}
			return firstError;
		}
	}
	//&#38; - ampersand
	xmlhttp.open('GET', 'processes/get_skils.php?dept='+dept, true);
	xmlhttp.send();*/
}


