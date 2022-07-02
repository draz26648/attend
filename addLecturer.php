<?php

include "db.php";


header("Content-Type: application/json; charset=utf-8", true);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$email = $_POST['email'];
		$password = $_POST['password'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$phone = $_POST['phone'];
		$gender = $_POST['gender'];
		$address = $_POST['address'];

	    $stmt = $conn->prepare("INSERT INTO lecturer (fname, lname, gender, email, address, phone, password) VALUES (?,?,?,?,?,?,?)");
	    $stmt->execute(array($fname, $lname, $gender, $email, $address, $phone, $password));
	    echo json_encode(array('status' => 'success'));
	    
} 
else
    die("request error");

?>