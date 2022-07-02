<?php

header("Content-Type: application/json; charset=utf-8", true);

if ($_SERVER['REQUEST_METHOD'] == "GET") {

	function showStudents()
	{
	    include "db.php";
		$School_year = $_GET['School_year'];
		$day = $_GET['day'];
		$start_time = $_GET['start_time'];
        $students_data = array();


	    $stmt = $conn->prepare("select semester_schedule.start_time , student.fname , student.lname , student_academic_year.School_Year from semester_schedule, student, student_academic_year where semester_schedule.start_time = ? and semester_schedule.day = ? and student_academic_year.School_Year= ? and student.School_Year = student_academic_year.School_Year");
	    $stmt->execute(array($start_time , $day , $School_year));
	    $data = $stmt->fetchAll();
	    $row = $stmt->rowcount();
	    if ($row > 0) {    
		    foreach($data as $user) 
		    {
		    	$fname = $user['fname'];
		    	$lname = $user['lname'];
		    	$start_time = $user['start_time'];
		    	$School_Year = $user['School_Year'];
                $temp = array('fname' => $fname, 'lname' => $lname,'start_time' => $start_time ,'School_Year' => $School_Year, 'status' => 'Success');
		    	array_push($students_data,$temp);
		    }
		}
		$students_data = array('students' => $students_data);
        echo json_encode($students_data);
	}
	showStudents();
}

elseif ($_SERVER['REQUEST_METHOD'] == "POST") { 

	$json = file_get_contents('php://input');
    $data = json_decode($json,true);
    $start_time = $data['start_time'];
    $day = $data['day'];
    $students_names = $data['students'];
    $students_names = str_replace(array('[',']',' "','"'), '',$students_names);
    $students_array = explode(',', $students_names);
    $fname = array(); 
    $lname = array();
    $course_id ;
    $school_year;

    
    foreach ($students_array as $fullname) {

        $tmp = explode(' ', $fullname);
        array_push($fname,$tmp[0]);
        array_push($lname,$tmp[1]);

    }

	function modifyAttendence()
    {
        include "db.php";
        $counter = 0;
        global $fname, $lname, $course_id, $school_year,$students_array,$start_time, $day;
        foreach ($students_array as $fullname){
        	$stmt = $conn->prepare("SELECT st.student_id , s.course_id , s.school_year from `semester_schedule` s INNER JOIN `student` st ON st.fname = ? and st.lname = ? INNER JOIN `student_academic_year` ac ON s.start_time = ? and s.day = ? and ac.school_year = st.school_year ");
            $stmt->execute(array($fname[$counter], $lname[$counter], $start_time, $day));
            $data_query3 = $stmt->fetchAll();
            $row_query3 = $stmt->rowcount();
            if ($row_query3 > 0) {    
                foreach($data_query3 as $user_query3){
                    $student_id = $user_query3['student_id'];
                    $course_id = $user_query3['course_id'];
  					$school_year = $user_query3['school_year'];
                    $stmt = $conn->prepare("UPDATE attendence SET state = ? WHERE attendence.stu_id = ? AND attendence.course_id = ? and attendence.School_Year = ? and attendence.attend_date = ?");
                    $stmt->execute(array(1, $student_id, $course_id , $school_year, date("Y-m-d")));
                }
            }
            $counter++;
        
        }
        echo json_encode(array('status' => 'Success'));
    }
    
    modifyAttendence();
}

else
    die("request error");



?>