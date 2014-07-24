<?php
//Training Verification
	$host = "192.168.10.129";
	$user = "postgres";
	$pass = "pass";
	$db = "inven";
	$conn = pg_connect ("host=$host dbname=$db user=$user password=$pass");
		
	if(!$conn)
	{
		die('Could not connect to database.');
	}
	
	$record_num = $_POST['recordNumber'];
	$query = "UPDATE employee_training_records SET verified_by = '" . $hr_num . "' WHERE record_number = '" . $record_num . "'";
	$results = pg_query($conn, $query) or die("Error in query. " . pg_last_error($conn));
	$query = "COMMIT";
	$results = pg_query($conn, $query) or die("Error in query. " . pg_last_error($conn));
	print "The record has been verified. Thank you.";
?>