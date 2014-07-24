<?php
	ob_start();
	require "common_functions.php";
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
	$course_title = $_POST['courseTitle'];
	/*$course_type = $_POST['courseType'];
	if($course_type != "O")
		$other_type = $_POST['otherCourse'];*/
	$presented_by = $_POST['presented'];
	$date_completed = $_POST['dateCompleted'];
	list($month, $day, $year) = explode("-", $date_completed);
	$date_completed = "$year-$month-$day";
	
	$query = "INSERT INTO employee_training_record(employee_number, skill, presented_by, date_completed) VALUES('" . $emp_num . "', '" . $course_title . "', '" . $presented_by . "', '" . $date_completed ."')";
	$results = pg_query($conn, $query) or die("Error in query: $query. " . pg_last_error($conn));
	$query = "COMMIT";
	$results = pg_query($conn, $query) or die("Error in query: $query. " . pg_last_error($conn));
	//Send local HR an email to verify training
	$plant = get_plant();
	$to = verify_training($plant);
	$subject = "Training Verification for $emp_num";
	$body = "Please verifiy the training for $emp_num by clicking this link: <a href = 'training_verification.php?emp_num=" . $emp_num . "&skill=" . $course_title . "'>Verify</a>";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	//mail($to, $subject, $body, $headers);
	if($_POST['submit'] == 'Submit')
		header("Location: ../index.html");
	else
		header("Location: ../trainingForm.php");
?>