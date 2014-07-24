<?php
//Training Process

	require "common_functions.php";

	ob_start();
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database.');
	}
	//$conn = pg_connect(connect_db());	
	/*if(!$conn)
	{
		die('Could not connect to database.');
	}*/
	$plant_name = get_plant();
	$emp_num = $_POST['clockNum'];
	$presented_name = $_POST['trainSign'];
	$date_completed = $_POST['superDate'];
	list($month, $day, $year) = explode("-", $date_completed);
	$date_completed = "$year-$month-$day";
	//$verified_name = $_POST['personnelSign'];
	
	$presented_by = name_to_number($presented_name, $conn);
	//$verified_by = name_to_number($verified_name, $conn);
	
	$query = "INSERT INTO employee_training_record (record_number, employee_number, skill, presented_by, date_completed) VALUES('" . $record_num . "', '" . $emp_num . "', '122.3', '" . $presented_by . "', '" . $date_completed . "')";//, '" . $verified_by . "')";
	
	$results = pg_query($conn, $query) or die("Error in query. " . pg_last_error($conn));
	$query = "COMMIT";
	$results = pg_query($conn, $query) or die("Error in query. " . pg_last_error($conn));
	header("Location: ../index.html");
	ob_end_flush();
	function name_to_number($name, $conn)
	{
		list($first_name, $last_name) = explode(" ", $name);
		$query = "SELECT emp_num FROM employee_records WHERE first_name = '" . $first_name . "' AND last_name = '" . $last_name . "' ORDER BY emp_num";
		$query_result = pg_query($conn, $query) or die("Error in query. " . pg_last_error($conn));
		$row_count = pg_num_rows($query_result);
		switch(true)
		{
			case($row_count < 1):
				return "No Such Employee Exists";
				break;
			
			case($row_count == 1):
				$emp_info = pg_fetch_array($query_result);
				$emp_num = $emp_info['emp_num'];
				return $emp_num;
				break;
		
			case($row_count > 1):
				return "Multiple employees exist with that name.  Please choose the correct employee.";
				break;
		}
	}
?>