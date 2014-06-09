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
	$dept = $_GET['dept'];
	$query = "SELECT DISTINCT(skill) FROM department_skills WHERE plant = '" . $plant_name . "' ";
	if($dept != 'A')
		$query .= "AND department = '" . $dept . "' ";
	$query .= "ORDER BY skill";
	
	$results = pg_query($conn, $query) or die("Error in query " . $query ". " . pg_last_error($conn));
	$skill_count = pg_num_rows($results);
	for($i = 0; $i < $skill_count; $i++)
	{
		$skills = pg_fetch_array($results);
		$skill_name = $skills['skill'];
		$skill_options .= "<option value = '" . $skill_name . "'>$skill_name</option>";
	}
	pg_close($conn);
	print $skill_options;
?>