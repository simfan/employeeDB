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
	
	$emp_num = $_GET['empNum'];
	$emp_query = "SELECT first_name, last_name, plant FROM employee_records WHERE emp_num = '" . $emp_num  . "'";
	$emp_results = pg_query($conn, $emp_query) or die("Error in employee query. " . pg_last_error($conn));
	$employee = pg_fetch_array($emp_results);
	$orientation_query = "SELECT start_date, initial_department FROM employee_orientation WHERE employee_number = '" . $emp_num . "' ORDER BY start_date DESC LIMIT 1";
	$orientation_results = pg_query($conn, $orientation_query) or die("Error in orientation query. " . pg_last_error($conn));
	$orientation = pg_fetch_array($orientation_results);
	$emp_name = $employee['first_name'] . " " . $employee['last_name'];
	$plant = $employee['plant'];
	$start_date = $orientation['start_date'];
	$start_date = str_replace("/", "-", $start_date);
	list($year, $month, $day) = explode("-", $start_date);
	$start_date = "$month-$day-$year";
	$init_dept = $orientation['initial_department'];

	//$training_query = "SELECT tr.skill AS skill, s.skill_type AS skill_type, s.other_type AS other_type, tr.presented_by AS presented_by, tr.date_completed AS date_completed, tr.verified_by AS verified_by FROM employee_training_record AS tr, skills AS s WHERE tr.skill = s.skill AND tr.employee_number = '" . $emp_num . "' ORDER BY tr.date_completed";
	$training_query = "SELECT skill, presented_by, date_completed, verified_by FROM employee_training_record WHERE employee_number = '" . $emp_num . "' ORDER BY date_completed";
	$training_results = pg_query($conn, $training_query) or die("Error in training query. " . pg_last_error($conn));
	$skill_count = pg_num_rows($training_results);
	
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="stylesheets/employee_training_record.css" />
</head>
<body>
	<h3>Fargo Employlee Training Record</h3>
	<div id = "employeeInfo">
		<div id = "col1" class = "col">
			<div><?php print $emp_name; ?><br />Employee Name</div>
			<div><?php print $init_dept?><br />Department</div>
		</div>
		<div id = "col2" class = "col">
			<div><?php print $emp_num; ?><br />Employee Number</div>
			<div><?php print $start_date; ?><br />Start Date</div>
		</div>
	</div>
	<div id = "trainingInfo">
		<div id = "heading" class = "heading">
			<div class = "field0">&nbsp;</div>
			<div class = "field1">Course Instruction<br/>or Training Description<br/>or Title</div>
			<div class = "field2">Course Type</div>
			<div class = "field3">Presented By</div>
			<div class = "field4">Date Completed</div>
			<div class = "field5">Verified By</div>
		</div>
		<?php 
			for($i = 0; $i < $skill_count; $i++)
			{
				$training_info = pg_fetch_array($training_results);
				$skill = $training_info['skill'];
				//$skill_type = $training_info['skill_type'];
				//if($skill_type == 'O')//or 'Other'
				//	$skill_type .= " - " . $training_info['other_type'];

				$presented = $training_info['presented_by'];
				$date_completed = $training_info['date_completed'];
				list($year, $month, $day) = explode('-', $date_completed);
				$date_completed = "$month-$day-$year";
				$verified = $training_info['verified_by'];
				$row_class = 'record';
				if ($verified == '')
					$row_class .= ' red';
				
				
		?>
		<div class = "<?php print $row_class?>">
			<!--<div class = "field0">&nbsp;</div>-->
			<div class = "field1"><?php print $skill; ?></div><br/>
			<!--<div class = "field2"><?php print $skill_type; ?></div>-->
			<div class = "field2">&nbsp;</div>
			<div class = "field3"><?php print $presented; ?></div>
			<div class = "field4"><?php print $date_completed; ?></div>
			<div class = "field5"><?php print $verified; ?></div>
		</div>
		<?php } ?>
	</div>
</body>
</html>	