<?php

include "db.php";

if ($_SERVER['REQUEST_METHOD'] == "POST" ) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$isAdmin = $_POST['isAdmin'];

    header("Content-Type: application/json; charset=utf-8", true);

	if ($isAdmin === "true") {
		$stmt = $conn->prepare("select * from admin where email = ? and password = ?");
	    $stmt->execute(array($email, $password));
	    $data = $stmt->fetchAll();
	    $row = $stmt->rowcount();
	    if ($row > 0) {  
	        foreach($data as $user) 
            {
                $phone = $user['phone'];
                $email = $user['email'];
                $fname = $user['fname'];
                $lname = $user['lname'];
                
            }
            echo json_encode(array('status' => 'Success','message' => 'Welcome Admin','phone' => $phone, 'email' => $email, 'first_name' => $fname ,'last_name' => $lname));
		}
		else
		{
			echo json_encode(array('status' => 'credentials is not valid'));
		}
	}
	elseif ($isAdmin === "false") {
		$stmt = $conn->prepare("select * from lecturer where email = ? and password = ?");
	    $stmt->execute(array($email, $password));
	    $data = $stmt->fetchAll();
	    $row = $stmt->rowcount();
	    if ($row > 0) {    
		    foreach($data as $user) 
            {
                $phone = $user['phone'];
                $email = $user['email'];
                $fname = $user['fname'];
                $lname = $user['lname'];
                
            }
            echo json_encode(array('status' => 'Success','message' => 'Welcome Doctor','phone' => $phone, 'email' => $email, 'first_name' => $fname ,'last_name' => $lname));
		}
		else
		{
			echo json_encode(array('status' => 'credentials is not valid'));
		}
	}
	else
		echo json_encode(array('status' => 'Failed'));
    
} 
else
    die("Request Error");

?>