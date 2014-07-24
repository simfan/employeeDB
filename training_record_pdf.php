<?php
	require('../fpdf153/fpdf.php');
	$host = "192.168.10.129";
	$user = "postgres";
	$db = "inven";
	$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");
	if(!$conn)
	{
		die("Could not connect to database.");
	}
	$special_chars = array("\n", "\r", "\t");
	$course_desc = array("M" => "Management", "O" => "Other", "S" => "Special Skill", "T" => "Technical");
	$emp_num = $_GET['empNum'];
	$emp_query = "SELECT first_name, last_name FROM employee_records WHERE emp_num = '" . $emp_num . "'";
	$emp_result = pg_query($conn, $emp_query) or die("Error in employee query. " . pg_last_error($conn));
	$emp = pg_fetch_array($emp_result);
	$emp_name = $emp['first_name'] . ' ' . $emp['last_name'];
	
	$orientation_query = "SELECT initial_department, start_date FROM employee_orientation WHERE employee_number = '" . $emp_num . "' ORDER BY start_date LIMIT 1";
	$orientation_result = pg_query($conn, $orientation_query) or die("Error in orientation query. " . pg_last_error($conn));
	$orienation = pg_fetch_array($orientation_result);
	$dept = $orientation['initial_department'];
	$start_date = $orientation['start_date'];
	
	//$training_query = "SELECT s.skill AS skill, s.department AS dept, s.skill_type AS type, t.presented_by AS pb, t.date_completed AS dc, t.verified_by AS vb FROM skills AS s, employee_training_record AS t WHERE s.skill = t.skill AND t.employee_number = '" . $emp_num . "' ORDER BY t.date_completed, t.skill";
	$training_query = "SELECT skill, presented_by AS pb, date_completed AS dc, verified_by AS vb FROM employee_training_record WHERE employee_number = '" . $emp_num . "' ORDER BY date_completed, skill";
	$training_results = pg_query($conn, $training_query) or die("Error in training query. " . pg_last_error($conn));
	$t_count = pg_num_rows($training_results);
	class PDF extends FPDF
	{
		var $x1;
		var $y1;
		function Header()
		{
			$this->SetFont('Arial', 'B', 15);
			$this->setX(60);
			$this->Cell(100, 10, "Fargo Employee Training Record", 0, 1);
			$this->setY(30);
		}
	}
	
	$pdf = new PDF;
	$dept = "Cutting";
	$start_date = "07-07-2014";
	//$pdf->AddPage();
	//$pdf->Rect(10,30,190,210);
	$title = "Training Record";
	$pdf->SetTitle($title);
	$pdf->SetFont('Arial', '', 12);
	$pdf->AddPage();
	$pdf->Rect(10, 30, 190, 210);
	$pdf->Line(10,70,200,70);
	$pdf->SetLeftMargin(20);
	$pdf->SetY(35);
	$pdf->Cell(100, 5, $emp_name);//, 0, 1);
	$pdf->Cell(100, 5, $emp_num, 0, 1);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(100, 10, "Employee Name");//, 0, 1);
	$pdf->Cell(100, 10, "Employee Number", 0, 1);
	$pdf->SetY(55);
	$pdf->SetFont('Arial', '', 12);
	$pdf->Cell(100, 5, $dept);//, 0, 1);
	$pdf->Cell(100, 5, $start_date, 0, 1);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->Cell(100, 10, "Department");//, 0, 1);
	$pdf->Cell(100, 10, "Start Date", 0, 1);
		
	$pdf->SetY(75);
	$pdf->SetX(10);
	$pdf->SetFont('Arial', '', 10);

	$pdf->MultiCell(50, 5, "Course Instruction or Training Description or Title");
	$pdf->SetY(73);
	$pdf->SetX(60);
	$pdf->Cell(25, 10, "Course Type");
	$pdf->Cell(40, 10, "Presented By");
	$pdf->Cell(27, 10, "Date Completed");
	$pdf->Cell(100, 10, "Verified By");
	$pdf->Cell(0, 20, "", 0, 1);
	
	for($i = 0; $i < $t_count; $i++)
	{
		$j = $i+1;
		$training = pg_fetch_array($training_results);
		$skill = $training["skill"];
		$dept = $training["dept"];
		$type = $training["type"];
		$presented = $training["pb"];
		$presented_query = "SELECT first_name, last_name FROM employee_records WHERE emp_num = '" . $presented . "'";
		$p_results = pg_query($conn, $presented_query) or die("Error in 'Presented by Query #" . $j . "'. " . pg_last_error($conn));
		$presented_info = pg_fetch_array($p_results);
		$presented_by = "$presented - " . $presented_info['first_name'] . ' ' . $presented_info['last_name'];
		$date_completed = $training["dc"];
		list($year, $month, $day) = explode("-", $date_completed);
		$date_completed = "$month-$day-$year";
		$verified_by = $training["vb"];
		$pdf->SetX(10);
		$pdf->Cell(50, 5, "$skill - $dept");
		$pdf->Cell(25, 5, $course_desc[$type]);
		$pdf->Cell(45, 5, $presented_by);
		$pdf->Cell(20, 5, $date_completed);
		$pdf->Cell(100, 5, $verified_by, 0, 1);
	}
		
	$pdf->Output();
?>