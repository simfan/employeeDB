<?php
	$emp_num = $_GET['empNum'];
?>
<html>
<head></head>
<body>
	<h3>New Employee</h3>
	<form name = "empForm" action = "processes/employee_process.php" method = "POST" onsubmit = "verify(this)">
		<div>Employee Number: <?php print $emp_num; ?><input type = "hidden" name = "empNum" id = "empNum" value = "<?php print $emp_num; ?>" /></div>
		<div>First Name:<input type = "text" name = "firstName" id = "firstName" /></div>
		<div>Last Name:<input type = "text" name = "lastName" id = "lastName" /></div>
		<div>Plant:<select name = "plant" id = "plant">
					<option value = "K">Atchison</option>
					<option value = "D">David City</option>
					<option value = "N">Norristonw</option>
					<option value = "T">Reading</option>
					<option value = "R">Richland</option>
				   </select>
		</div>
		<div><input type = "submit" name = "submit" id = "submit" value = "Submit" /></div>
	</form>
</body>
</html>
		