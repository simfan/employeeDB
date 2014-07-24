<?php
//Prelininary Steps - prepare DB tables, xml files

$host = "192.168.10.129";
$user = "postgres";
$pass = "pass";
$db = "inven";
$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
if(!$conn)
{
	die('Could not connect to database.');
}

require_once("../simplexml/class/IsterXmlSimpleXMLImpl.php");
$impl = new IsterXmlSimpleXMLImpl;

$emp_insert = "INSERT INTO employee_records (emp_num, first_name, last_name, plant)";
$skill_insert = "INSERT INTO employee_training_record (employee_number, skill, presented_by, date_completed, verified_by)";
$emp_count = 0;
$skill_count = 0;

//1) Read each xml file in the current directory
$xml_dir = getcwd();
//$xml_dir = $dir . "/employeeXML/";
print $xml_dir;
if (is_dir($xml_dir))
{
	print "<br />Is a directory";
  	if ($dh = opendir($xml_dir))
  	{
 		print "<br />Directory Opened";	
		//2) For each xml file,   		
		while (($file = readdir($dh)) !== false)
 		{
   	 		if(preg_match("/xml$/", $file))
   			//A) extract employee number
			{
				print "File is $file <br/>";
				$doc = $impl->load_file($file);
				$emp_num = $doc->Record->Employee->Number->CData();
				print "Emp Num is $emp_num <br/>";
				//B) check employee DB for matching emp #
				/*$emp_query = "SELECT emp_num FROM employee_records WHERE emp_num = '" . $emp_num . "'";
				$emp_results = pg_query($conn, $emp_query) or die("Error in employee query. " . pg_last_error($conn));
	 				
				//i) if there is no match, add new record
	 			if(pg_num_rows($emp_results) == 0)
	 			{
		 			$first_name = $doc->Record->Employee->FirstName->CData();
		 			$last_name = $doc->Record->Employee->LastName->CData();
		 			$plant = $doc->Record->Employee->Plant->CData();
		 			$emp_insert .= " VALUES('" . $emp_num . "', '" . $first_name . "', '" . $last_name . "', '" . $plant . "')";
		 			$emp_count++;
	 			}
	 
	 			//C) extract skills
	 	 		//i) for each skill,
	 			foreach($doc->Record->Employee->Skills->Skill() as $skill)
	 			{
	 				//a) extract skill code
	 				$title = $skill->Title->CDATA();
	 				
	 				//b) check training_records DB
	 				$skill_query = "SELECT employee_num, skill FROM employee_training_record WHERE employee_num = '" . $emp_num . "' AND skill = '" . $title . "'";
	 				$skill_result = pg_query($conn, $skill_query) or die("Error in skill query. Skill = $skill, emp num = $emp_num. " . pg_last_error($conn));
	 				if(pg_num_rows($skill_result) == 0)
	 				{
	 					$presented_by = $skill->Presented->CData();
	 					$completed_date = $skill->CDate->CData();
	 					$verified = $skill->Verified->CData();
	 					$skill_insert .= " VALUES('" . $emp_num . "', '" . $title . "', '" . $presented_by . "', '" . $completed_date . "', '" . $verified . "')";
	 					$skill_count++;
					}
				}*/
			}
		}
		if($emp_count > 0)
		{
			$emp_results = pg_query($conn, $emp_insert) or die("Error in employee insert query. " . pg_last_error($conn));
		 	$emp_insert = "COMMIT";
		 	$emp_results = pg_query($conn, $emp_insert) or die("Error in employee insert query. " . pg_last_error($conn));
	 	}
	 	if($skill_count > 0)
	 	{
		 	$skill_result = pg_query($conn, $skill_insert) or die("Error in skill query. Skill = $skill, emp num = $emp_num. " . pg_last_error($conn));
			$skill_insert = "COMMIT";
			$skill_result = pg_query($conn, $skill_insert) or die("Error in skill query. Skill = $skill, emp num = $emp_num. " . pg_last_error($conn));
		}
	}
}
?>