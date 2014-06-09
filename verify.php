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
	
	$emp_num = $_GET['empNum'] ;
	$skill = $_GET['skill'];

	$existance_query = "SELECT verified_by FROM employee_training_record WHERE employee_number = '" . $emp_num . "' AND skill = '" . $skill.  "'";
	$e_results = pg_query($conn, $existance_query) or die("Error in existance query. " . pg_last_error($conn));
	$training_record = pg_fetch_array($e_results);
	$hr = $training_record['verified_by'];
	if(is_null($hr) || $hr == '')
	{	
		$emp_query = "SELECT first_name, last_name FROM employee_records WHERE emp_num = '" . $emp_num . "'";
		$skill_query = "SELECT department FROM skills WHERE skill = '" . $skill . "'";
	
		$emp_results = pg_query($conn, $emp_query) or die("Error in Employee Query. " . pg_last_error($conn));
		$skill_results = pg_query($conn, $skill_query) or die("Error in Skill Query. " . pg_last_error($conn));
	
		$emp_info = pg_fetch_array($emp_results);
		$skill_info = pg_fetch_array($skill_results);
	
		$first_name = $emp_info['first_name'];
		$last_name = $emp_info['last_name'];
		$emp_name = $first_name . ' ' . $last_name;
		$skill_dept = $skill_info['department'];
?>

<html>
<head>
</head>
<body>
	<h2>Training Verification</h2>
	<div class = "recordInfo">Employee Name: <?php print "$emp_num - $emp_name";?><br />Skill Trained In: <?php print "$skill - $skill_dept"; ?></div>
	<form name = "verifyForm" id = "verifyForm" method = "POST" action = "processes/verify_process.php";>
		<div class = "verification">HR Clock Number: <input type = "text" name = "hrClockNum" id = "hrClockNum" size = "4" maxlength = "4" /></div>
		<div class = "buttonRow"><input type = "submit" name = "verify" id = "verify" value = "Verify" /></div>
		<input type = "hidden" name = "empNum" id = "empNum" value = "<?php print $emp_num; ?>" />
		<input type = "hidden" name = "skill" id = "skill" value = "<?php print $skill; ?>" />
	</form>
</body>
</html>
<?php
	}
	else
	{
		print "This record has been previously updated.  Please close out of this screen";
	}
?>