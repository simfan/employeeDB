<?php
	//Connect to DB
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database.');
	}	
	$emp_num = $_POST['employee'];
	$hire_date = $_POST['hireDate'];
	$supervisor = $_POST['supervisor'];
	$s_orientation_date = $_POST['sOrientationDate'];
	$hr = $_POST['hr'];
	$hr_orientation_date = $_POST['hrOrientationDate'];
	$e_sign_date;
	$s_sign_date;
	$hr_sign_date;
	$init_dept = $_POST['initDept'];
	
	$query = "INSERT INTO employee_orientation(record_number, employee_number, hire_date, start_date, initial_department, supervisor, s_orientation_date, s_sign_date, hr, hr_orientation_date, hr_sign_date) VALUES('" . $record_num . "', '" . $emp_num . "', '" . $hire_date . "', '" . $start_date . "', '" . $init_dept . "', '" . $supervisor . "', '" . $s_orientation_date . "', '" . $s_sign_date . "', '" . $hr . "', '" . $hr_orientation_date . "', '" . $hr_sign_date . "')";
	
	$results = pg_query($conn, $query) or die("Error in query: $query . " . pg_last_error($conn));
	$query = "COMMIT";
	$results = pg_query($conn, $query) or die("Error in query: $query . " . pg_last_error($conn));
	
	$search_query = "SELECT emp_num FROM employee_records WHERE emp_num = '" . $emp_num . "'";
	$search_result = pg_query($conn, $search_query) or die("Error in query: $search_query . " . pg_last_error($conn));
	$search_count = pg_num_rows($search_result);
	if($search_count > 0)
	{
		if($_POST['submit'] == 'Submit')
			header("Location: ../index.php");
		else
			header("Location: ../orientation.html");
	}
	else
	{
		header("Location: ../new_employee.php?empNum=". $emp_num);
	}
	ob_end_flush();
?>