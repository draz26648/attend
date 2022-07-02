<?php

include "db.php";


header("Content-Type: application/json; charset=utf-8", true);


if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$gender = $_POST['gender'];
		$age = $_POST['age'];
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$School_year = $_POST['School_year'];

		// check if school year is already exist
		$stmt = $conn->prepare("select * from student_academic_year where School_Year = ?");
		$stmt->execute(array($School_year,));
		$data = $stmt->fetchAll();
	    $row = $stmt->rowcount();

		// case school year is not exist
		if($row === 0 ){
		$stmt = $conn->prepare("INSERT INTO student_academic_year(School_Year)values(?)");
		$stmt->execute(array($School_year));

	    $stmt = $conn->prepare("INSERT INTO student (fname, lname, email, gender, age, address, phone, School_year) VALUES (?,?,?,?,?,?,?,?)");
	    $stmt->execute(array($fname, $lname, $email, $gender, $age, $address, $phone, $School_year));
		
	    echo json_encode(array('status' => 'success','message' => 'you add new student'));
		} 
		// case is school year is already exist
		else
		{
			$stmt = $conn->prepare("INSERT INTO student (fname, lname, email, gender, age, address, phone, School_year) VALUES (?,?,?,?,?,?,?,?)");
			$stmt->execute(array($fname, $lname, $email, $gender, $age, $address, $phone, $School_year));
			
			echo json_encode(array('status' => 'success','message' => 'you add new student'));
		}
		
	    
} 

else
    die("request error");

?>