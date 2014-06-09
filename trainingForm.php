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
<head>
	<link rel="stylesheet" type="text/css" href="stylesheets/orientation.css" />
	<style>.col2{left: 400px;}</style>
		
</head>
<body>
	<h2>EMPLOYEE TRAINING FORM</h2>
	<form name = "trainingForm" action = "processes/training_process.php" method = "post" onsubmit = "return verify(this)">
		<div class = "section">
			<input type = "text" name = "empNum" id = "empNum" /><div class = "underText">Employee Number</div>
		</div>
		<div class = "section">
			Course Instrucion/Training Description/Title:&nbsp;<select name = 'courseTitle' id = 'courseTitle'><?php for($i = 0; $i < $skill_count; $i++)
				{
					$skill = pg_fetch_array($result);
					$skill_code = $skill['skill'];
					$skill_dept = $skill['department'];
					$skill_name = "FP" . $skill_code . " - " . $skill_dept;
					print "<option value = " . $skill_code . ">$skill_name</option>";
				}
			?></select><!--<input type = "text" name = "courseTitle" id = "courseTitle" />&nbsp;
			Course Type:&nbsp;<select name ="courseType" id = "courseType">
							<option value = "M">Management</option>
							<option value = "S">Special Skill</option>
							<option value = "T">Technical</option>
							<option value = "O">Other (indicate)</option>-->
						</select><!--&nbsp;<input type = "text" name = "otherCourse" id = "otherCourse" />-->
			Presented By:&nbsp;<input type = "text" name = "presented" id = "presented" />
			Date Completed:&nbsp;<input type = "text" name = "dateCompleted" id = "dateCompleted" size = 10 maxlength = 10 />
		</div>
		<div class = "section">
			<input type = "submit" name = "submit" id = "submit" value = "Submit" />
		</div>
	</form>
</body>
</html>
						
			