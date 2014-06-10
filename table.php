<?php
	//connect to db
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
	
	//pull textboxes in from search
	$dept = $_POST['dept'];
	$skill = $_POST['skill'];
	$emp_num = $_POST['empNum'];
	$selection = $_POST['searchBy'];
	
	$emp_query = "SELECT employee_records.first_name AS f_name, employee_records.last_name AS l_name, employee_training_record.employee_number AS e_num, employee_training_record.skill AS e_skill FROM employee_records INNER JOIN employee_training_record ON employee_records.emp_num = employee_training_record.employee_number ";
	switch(TRUE)
	{	
		case($emp_num == '' && $skill == 'any'):// && $dept == ''):
			$emp_conditions = " WHERE employee_records.plant = '" . $plant_name ."' ";
			$order_info = setOrder($selection);
			$emp_conditions .= $order_info[0];
			$sort_order = $order_info[1];
			break;
			
		case($emp_num != '' && $skill == 'any'):// && $dept == ''):
			$emp_conditions = "WHERE employee_records.plant = '" . $plant_name . "' AND employee_training_record.employee_number = '" . $emp_num . "' ORDER BY e_skill";
			$sort_order = 1;
			break;
			
		case($emp_num == '' && $skill != 'any'):// && $dept == ''):
			$emp_conditions = "WHERE employee_records.plant = '" . $plant_name . "' AND employee_training_record.skill = '" . $skill . "' ORDER BY e_num";
			$sort_order = 2;
			break;
		
		case($emp_num != '' && $skill != 'any'):// && $dept == ''):
			$emp_conditions = "WHERE employee_records.plant = '" . $plant_name . "' OR employee_training_record.skill = '" . $skill . "' OR employee_training_record.employee_number = '" . $emp_num . "'";
			$sort_order = 3;
			break;
			
	}
	
	$emp_query .= $emp_conditions;
	$emp_results = pg_query($conn, $emp_query) or die("Error in Emp query. " . pg_last_error($conn));
	$num_emps = pg_num_rows($emp_results);
?>
<html>
<head>
	<link rel = "stylesheet" type = "text/css" href = "stylesheets/table.css" />
</head>
<body>
<?php
	for($i = 0; $i < $num_emps; $i++)
	{
		$emp_info = pg_fetch_array($emp_results);
		$employee_num = $emp_info['e_num'];
		$first_name = $emp_info['f_name'];
		$last_name = $emp_info['l_name'];
		$emp_skill = $emp_info['e_skill'];
		
		switch($sort_order)
		{
			//employee, skill
			case(1):
				$main_sort = "<a href = \"employee_training_record.php?empNum=$employee_num\">$employee_num  - $first_name $last_name</a>";
				$sub_sort = $emp_skill;
				break;
			
			//skill, employee	
			case(2):
				$sub_sort = "<a href = \"employee_training_record.php?empNum=$employee_num\">$employee_num - $first_name $last_name</a>";
				$main_sort = $emp_skill;
				break;
			
			case(3):
				$main_sort = "<a href = \"employee_training_record.php?empNum=".$employee_num."\">" .$employee_num . " - " . $first_name .  " " . $last_name . " - " . $emp_skill. "</a>";
				break;
		}
		
		if($main_sort != $last_sort)
		{
			print "<div class = 'main'>$main_sort</div>";
			$last_sort = $main_sort; 
		}
		
		if($sort_order != 3)
		{
			print "<div class = 'sub'>$sub_sort</div>";
		}
	}

	function setOrder($selection)
	{
		if($selection == 'es')
		{
			$query_order = " ORDER BY e_num, e_skill";
			$query_sort = "1";
		}
		if($selection == 'se')
		{
			$query_order = " ORDER BY e_skill, e_num";
			$query_sort = "2";
		}
		return array($query_order, $query_sort);
	}
?>
</body>
</html>