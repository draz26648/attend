<?php

header("Content-Type: application/json; charset=utf-8", true);

if ($_SERVER['REQUEST_METHOD'] == "POST" ) {

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

    function getCourse()
    {
        include "db.php";
        global $fname, $lname, $start_time, $day, $course_id, $school_year;
        $stmt = $conn->prepare("SELECT st.student_id , s.course_id , s.school_year from `semester_schedule` s INNER JOIN `student` st ON st.fname = ? and st.lname = ? INNER JOIN `student_academic_year` ac ON s.start_time = ? and s.day = ? and ac.school_year = st.school_year ");
        $stmt->execute(array($fname[0], $lname[0], $start_time , $day));
        $data_query1 = $stmt->fetch();
        $row_query1 = $stmt->rowcount();
        if ($row_query1 > 0) {

            $course_id = $data_query1['course_id'];
            $school_year = $data_query1['school_year'];
            $student_id = $data_query1['student_id'];
        }
    }

    function addAllGroupToAttendenceTable()
    {
        include "db.php";
        global $course_id, $school_year;
        $stmt = $conn->prepare("SELECT student_id from student");
        $stmt->execute();
        $data_query2 = $stmt->fetchAll();
        $row_query2 = $stmt->rowcount();
        if ($row_query2 > 0) {    
            foreach($data_query2 as $user_query2) 
            {
                $student_id = $user_query2['student_id'];
                $stmt = $conn->prepare("INSERT INTO attendence (attend_date, course_id, stu_id, state, school_year) VALUES (?,?,?,?,?)");
                $stmt->execute(array(date("Y-m-d") , $course_id , $student_id , 0 , $school_year));
            }
        }
    }

    function takeAttendence()
    {
        include "db.php";
        $counter = 0;
        global $fname, $lname, $course_id, $school_year,$students_array;
        foreach ($students_array as $fullname){
            $stmt = $conn->prepare("SELECT student_id from student where fname = ? and lname = ?;");
            $stmt->execute(array($fname[$counter],$lname[$counter]));
            $data_query3 = $stmt->fetchAll();
            $row_query3 = $stmt->rowcount();
            if ($row_query3 > 0) {    
                foreach($data_query3 as $user_query3){
                    $student_id = $user_query3['student_id'];
                    $stmt = $conn->prepare("UPDATE attendence SET state = ? WHERE attendence.stu_id = ? AND attendence.course_id = ? and attendence.School_Year = ? and attendence.attend_date = ? ");
                    $stmt->execute(array(1, $student_id, $course_id , $school_year, date("Y-m-d")));
                }
            }
            $counter++;
        
        }
        echo json_encode(array('status' => 'Success'));
    }


}


else 
    die("request error");


getCourse();
addAllGroupToAttendenceTable();
takeAttendence();

?>