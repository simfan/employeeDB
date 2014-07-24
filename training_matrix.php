<?php
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database.');
	}	
	$ip_address = $_SERVER['REMOTE_ADDR'];
	
	$ip_sections = explode('.', $ip_address);
	$plant = $ip_sections[2];
	switch($plant)
	{
		case(10):
			$plant_name = 'N';
			break;
		case(11):
			$plant_name = 'T';
			break;
		case(12):
			$plant_name = 'D';
			break;
		case(13):
			$plant_name = 'R';
			break;
		case(14):
			$plant_name = 'K';
			break;
	}		
	//generate rows for matrix
	$skills_query = "SELECT skill, department FROM skills ORDER BY skill";
	$skills_result = pg_query($conn, $skills_query) or die("Error in skills query. " . pg_last_error($conn));
	$skills_count = pg_num_rows($skills_result);
	print "# of skills: $skills_count";
	//generate columns for matrix
	$emp_query = "SELECT first_name, last_name, emp_num FROM employee_records WHERE plant = '" . $plant_name . "' ORDER BY last_name, first_name, emp_num";
	$emp_result = pg_query($conn, $emp_query) or die("Error in employee query. " . pg_last_error($conn));
	$emp_count = pg_num_rows($emp_result);
	
	$emp_skills_count = "SELECT employee_training_record.skill, COUNT(*) AS emp_count FROM employee_training_record INNER JOIN employee_records ON employee_training_record.employee_number = employee_records.emp_num WHERE employee_records.plant = '" . $plant_name . "' GROUP BY employee_training_record.skill ORDER BY employee_training_record.skill"; 
	$skill_count_result = pg_query($conn, $emp_skills_count) or die("Error in employee skill count query. " . pg_last_error($conn));
	
	$emp_skill_query = "SELECT employee_training_record.skill AS skill, employee_training_record.employee_number AS emp_num, employee_training_record.date_completed AS date_completed FROM employee_training_record INNER JOIN employee_records ON employee_training_record.employee_number = employee_records.emp_num WHERE employee_records.plant = '" . $plant_name . "' ORDER BY employee_training_record.skill, employee_records.last_name, employee_records.first_name, employee_training_record.employee_number";
	$emp_skill_result = pg_query($conn, $emp_skill_query) or die("Error in employee skill query. " . pg_last_error($conn));
	
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="stylesheets/matrix.css" />
	<script type = "text/javascript" src = "scripts/matrix.js"></script>
</head>
<body>
	<h2>Training Matrix</h2>
	<table>
		<tr><th>&nbsp</th>
		<?php
		for($i = 0; $i < $emp_count; $i++)
		{
			$h = $i + 1;
			$class_name = "field" . $h;
			$emp = pg_fetch_array($emp_result);
			$emp_num = $emp['emp_num'];
			$first_name = $emp['first_name'];
			$last_name = $emp['last_name'];
			$emp_name = "$last_name, $first_name";
			$employee_num[$i] = $emp_num;
			print "<th class = '" . $class_name ."'>$emp_num<br/>$emp_name</th>";
		}?>
		</tr>
		<?php
			//Generate rows, based on skill		
			for($i = 0; $i < $skills_count; $i++)
			{
				$l = 0;
				$h = $i+1;
				$skills_info = pg_fetch_array($skills_result); 
				$skill_name = $skills_info['skill'];
				$skill_dept = $skills_info['department'];
				$emp_skills = pg_fetch_array($skill_count_result);
				$emp_skills_count = $emp_skills['emp_count'];
				$row_name = "row" . $h;
				print "<tr id = '" . $row_name . "'><td class = 'skillField'>FP". $skill_name. " - $skill_dept</td>";
				
				//Read each employee record
				for($j = 0; $j < $emp_skills_count; $j++)
				{
					$j1 = $j + 1;
					$emp_info = pg_fetch_array($emp_skill_result);
					$emp_num = $emp_info['emp_num'];
					$date_completed = $emp_info['date_completed'];
					list($year, $month, $day) = explode("-", $date_completed);
					$date_completed = "$month-$day-$year";
					//$class_name = "field" . $j1;
					//determine placement of record within the row, starting at the last location1
					for($k = $l; $k < $emp_count; $k++)
					{
						$z = $k + 1;
						$class_name = "field" . $z;
						print "<td class = '" . $class_name . "' onmouseover = \"highlightCells(this, '" . $row_name . "');\" onmouseout = \"whiteCells(this, '" . $row_name . "');\">";
						
						if($employee_num[$k] == $emp_num)
						{
							print "$date_completed</td>";
							$l = $k+1;
							break;
						}
						else
							print "&nbsp;</td>";
					}
				}
				
				while($l < $emp_count)
				{
					$l1 = $l + 1;
					$class_name = "field" . $l1;
					print "<td class = '" . $class_name . "' onmouseover = \"highlightCells(this, '" . $row_name . "');\" onmouseout = \"whiteCells(this, '" . $row_name . "');\">&nbsp</td>";
					$l++;
				}
				print "</tr>";
			}
		?>
	</table>
</body>
</html>