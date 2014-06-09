<?php
	ob_start();
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die('Could not connect to database.');
	}	
	
	$emp_number = $_POST['empNum'];
	$first_name = $_POST['firstName'];
	$last_name = $_POST['lastName'];
	$plant = $_POST['plant'];
	
	$query = "INSERT INTO employee_records (emp_num, first_name, last_name, plant) VALUES('" . $emp_number . "', '" . $first_name . "', '" . $last_name . "', '" . $plant . "')";
	$results = pg_query($conn, $query) or die("Error in query: $query. " . pg_last_error($conn));
	$query = "COMMIT";
	$results = pg_query($conn, $query) or die("Error in query: $query. " . pg_last_error($conn));
	header("Location: ../index.html");
	ob_end_flush();
?>