<?php
	function get_plant()
	{
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
		return $plant_name;
	}
	
	function connect_db()
	{
		//Connect to DB
		$host = "192.168.10.129";
		$user = "postgres";
		$pass = "pass";
		$db = "inven";
		$conn = "host=$host dbname=$db user=$user password=$pass";
		/*if(!$conn)
		{
			die('Could not connect to database.');
		}*/	
		return $conn;
	}
	
	function verify_training($plant)
	{
		switch($plant)
		{
			case('D'):
				$to = "dorish@fargopa.com";	
				break;
				
			case('K'):
				$to = "karenk@fargopa.com";
				break;
				
			case('N'):
				$to = "lorrraine@fargopa.com";
				break;
				
			case('R'):
				$to = "lindab@fargopa.com";
				break;
				
			case('T'):
				$to = "brenda.santiago@fargopa.com";
				break;
			
		}
		return $to;
	}
?>