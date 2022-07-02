<?php

include "db.php";

header("Content-Type: application/json; charset=utf-8", true);


if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $fname = $_GET['fname'];
        $lname = $_GET['lname'];

        $stmt = $conn->prepare("SELECT  email , phone FROM lecturer where fname = ? and lname = ? ");
        $stmt->execute(array($fname, $lname));

        $data = $stmt->fetchAll();
    $row = $stmt->rowcount();
    if ($row > 0) {    
        foreach($data as $user) 
        {
            $phone = $user['phone'];
            $email = $user['email'];
            echo json_encode(array('phone' => $phone, 'email' => $email, 'status' => 'Success'));
        }
    }
    else
        echo json_encode(array( 'status' => 'failed'));


        
} 

else
    die("request error");

?>