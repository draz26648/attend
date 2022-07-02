<?php

include "db.php";
header("Content-Type: application/json; charset=utf-8", true);

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	$email = $_GET['email'];
	$courses_array = array();

	$stmt = $conn->prepare("select course.course_name from lecturer_courses INNER JOIN lecturer on lecturer.lecturer_id = lecturer_courses.lecturer_id and email = ? INNER JOIN course on course.course_id = lecturer_courses.course_id");
    $stmt->execute(array($email));
    $data = $stmt->fetchAll();
    $row = $stmt->rowcount();
    if ($row > 0) {    
	    foreach($data as $user){
            array_push($courses_array,$user['course_name']);
        }
        echo json_encode(array('status' => 'Success', 'email' => $email, 'courses_names' => $courses_array));
	}
	else
		echo json_encode(array('status' => 'Failed'));
}
	

else
    die("Request Error");

?>