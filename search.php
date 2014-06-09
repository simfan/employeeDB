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
	
	$query = "SELECT skill, department FROM skills ORDER BY skill";
	$result = pg_query($conn, $query) or die("Error in query. " . pg_last_error($conn));
	$skill_count = pg_num_rows($result);
?>
<html>
<body>
	<h2>Training Search</h2>
	<form name = "searchForm" action = "table.php" method = "POST">

		<!--<div>Department<input type = "text" name = "dept" id = "dept" /></div>--><!-- Change to select when all depts. are known -->
		<div>Employee Number<input type = "text" name = "empNum" id = "empNum" size = 4 maxlength = 4 /></div>
		<div>Course/Skill<!--<input type = "text" name = "skill" id = "skill" />--><select name = "skill" id = "skill"><option value = 'any'>Any</option><?php
		for($i = 0; $i < $skill_count; $i++)
				{
					$skill = pg_fetch_array($result);
					$skill_code = $skill['skill'];
					$skill_dept = $skill['department'];
					$skill_name = "FP" . $skill_code . " - " . $skill_dept;
					print "<option value = " . $skill_code . ">$skill_name</option>";
				}?></select><div>
	
		<div><input type = "radio" name = "searchBy" id = "se" value = "se" />Skill, Employee</div>
		<div><input type = "radio" name = "searchBy" id = "es" value = "es" />Employee, Skill</div>
		<input type = "submit" id = "submit" value = "Submit" />
	</form>
</body>
</html>
		