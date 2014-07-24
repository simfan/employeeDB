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

require_once("../../simplexml/class/IsterXmlSimpleXMLImpl.php");

$emp_insert = "INSERT INTO employee_records (emp_num, first_name, last_name, plant) VALUES";
$skill_insert = "INSERT INTO employee_training_record (employee_number, skill, presented_by, date_completed, verified_by) VALUES";
$orientation_insert = "INSERT INTO employee_orientation (employee_number, start_date, initial_department) VALUES";
$emp_count = 0;
$skill_count = 0;
$orientation_count = 0;
//1) Read each xml file in the current directory
$xml_dir = getcwd();
//$xml_dir = $dir . "/employeeXML/";
print $xml_dir;
$plant = "D";
if (is_dir($xml_dir))
{
  	if ($dh = opendir($xml_dir))
  	{
		//2) For each xml file,   		
		while (($file = readdir($dh)) !== false)
 		{
   	 		if(preg_match("/xml$/", $file))
   	 		{
	   	 		if(!preg_match("/template/", $file))
   				//A) extract employee number
				{
					$impl = new IsterXmlSimpleXMLImpl;
					$doc = $impl->load_file($file);				
					//$doc = $impl->load_file("Christopher_Glock.xml");
					$emp_num = $doc->Record->Employee->Number->CData();
			
					//B) check employee DB for matching emp #
					$emp_query = "SELECT emp_num FROM employee_records WHERE emp_num = '" . $emp_num . "'";
					$emp_results = pg_query($conn, $emp_query) or die("Error in employee query. " . pg_last_error($conn));
	 				
				//i) if there is no match, add new record
	 				if(pg_num_rows($emp_results) == 0)
	 				{
			 			$first_name = $doc->Record->Employee->Name->FirstName->CData();
			 			$names = explode(" ", $first_name);
			 			$i = 0;
			 			foreach($names as $name)
			 			{
				 			$names[$i] = ucfirst(strtolower($name));
				 			$i++;
			 			}
			 			
			 			$first_name = implode(" ", $names);
			 			$last_name = $doc->Record->Employee->Name->LastName->CData();
			 			$names = explode(" ", $last_name);
			 			$i = 0;
			 			foreach($names as $name)
			 			{
				 			$names[$i] = ucfirst(strtolower($name));
				 			$i++;
			 			}
			 			
			 			$last_name = implode(" ", $names);
			 			//$plant = $doc->Record->Employee->Plant->CData();
		 				if($emp_count > 0)
		 					$emp_insert .= ", ";
		 				$emp_insert .= " ('" . $emp_num . "', '" . $first_name . "', '" . $last_name . "', '" . $plant . "')";
			 			$emp_count++;
		 			}
					//Extract Orientation Info
					
					$orientation_query = "SELECT employee_number FROM employee_orientation WHERE employee_number = '" . $emp_num . "'";
	 				$orientation_results = pg_query($conn, $orientation_query) or die("Error in orientation query. " . pg_last_error($conn));
	 				if(pg_num_rows($orientation_results) == 0)
	 				{
		 				$start_date = $doc->Record->Employee->SDate->CData();
		 				$start_date = str_replace("/", "-", $start_date);
		 				list($month, $day, $year) = explode("-", $start_date);
		 				$year = "20" . $year;
		 				$start_date = "$year-$month-$day";
		 				$init_dept = $doc->Record->Employee->Department->CData();
		 				if($orientation_count > 0)
		 					$orientation_insert .= ",";
		 				$orientation_insert .= " ('" . $emp_num . "', '" . $start_date . "', '" . $init_dept . "')";
		 				$orientation_count++;
		 				print "Orientation for $emp_num added<br />";
	 				}
	 				//C) extract skills
	 	 			//i) for each skill,
	 	 			$i = 0;
		 			foreach($doc->Record->Employee->Skills->children() as $skill)
		 			{
	 					//a) extract skill code
	 					$title = $skill->Title->CDATA();
	 					//print "Title is $title<br/>";
	 					//b) check training_records DB
		 				$skill_query = "SELECT employee_number, skill FROM employee_training_record WHERE employee_number = '" . $emp_num . "' AND skill = '" . $title . "'";
		 				$skill_result = pg_query($conn, $skill_query) or die("Error in skill query. Skill = $skill, emp num = $emp_num. " . pg_last_error($conn));
	 					if(pg_num_rows($skill_result) == 0)
	 					{
	 						$presented_by = $skill->Presented->CData();
	 						$completed_date = $skill->CDate->CData();
	 						$completed_date = str_replace("/", "-", $completed_date);
	 						list($month, $day, $year) = explode("-", $completed_date);
	 						$year = "20" . $year;
	 						$completed_date = "$year-$month-$day";
		 					$verified = $skill->Verified->CData();
	 						if($skill_count > 0)
		 						$skill_insert .= ", ";
	 						$skill_insert .= " ('" . $emp_num . "', '" . $title . "', '" . $presented_by . "', '" . $completed_date . "', '" . $verified . "')";
	 						$skill_count++;
						}
					}
				}
			}
		}
		if($emp_count > 0)
		{
			$emp_results = pg_query($conn, $emp_insert) or die("Error in employee insert query. " . pg_last_error($conn));
		 	$emp_insert = "COMMIT";
		 	$emp_results = pg_query($conn, $emp_insert) or die("Error in employee insert query. " . pg_last_error($conn));
	 	}
	 	if($orientation_count > 0);
	 	{
		 	$orientation_results = pg_query($conn, $orientation_insert) or die("Error in orientation insert_query. " . pg_last_error($conn));
		 	$orientation_insert = "COMMIT";
		 	$orientation_results = pg_query($conn, $orientation_insert) or die("Error in orientation insert_query. " . pg_last_error($conn));
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