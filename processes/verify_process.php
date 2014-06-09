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
	
	$emp_num = $_POST['empNum'];
	$skill = $_POST['skill'];
	$hr_num = $_POST['hrClockNum'];
	
	$existance_query = "SELECT verified_by FROM employee_training_record WHERE employee_number = '" . $emp_num . "' AND skill = '" . $skill.  "'";
	$e_results = pg_query($conn, $existance_query) or die("Error in existance query. " . pg_last_error($conn));
	$training_record = pg_fetch_array($e_results);
	$hr = $training_record['verified_by'];
	if(is_null($hr) || $hr == '')
	{
		$query = "UPDATE employee_training_record SET verified_by = '" . $hr_num . "' WHERE employee_number = '" . $emp_num . "' AND skill = '" . $skill . "'";
		$results = pg_query($conn, $query) or die("Error updating employee training database record" . pg_last_error($conn));
		$query = "COMMIT";
		$results = pg_query($conn, $query) or die("Error committing update to employee training database record" . pg_last_error($conn));
		print "The training record for employee #$emp_num has been verified. You may close out of this window";
	}
	else
		print "This training record has previously been verified.  It will not be updated again.";
		
?>