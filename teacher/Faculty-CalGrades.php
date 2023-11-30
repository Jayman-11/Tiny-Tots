<?php
    require 'C:\xampp\htdocs\TinyTots\Connection.php';
    $conn = getDB();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the SR and subject ID that will be added on the TGS table
        
        $SR_ID=$_POST['SR_ID'];
        $sub_id=$_POST['subject_id'];
        
        //Calculate the GWA or total grade per subject of Student
        $f_grade=$_POST['f_grading'];
        $s_grade=$_POST['s_grading'];
        $t_grade=$_POST['t_grading'];
        $ft_grade=$_POST['fourth_grading'];

        $f_grade1 = (int)$f_grade;
        $s_grade1 = (int)$s_grade;
        $t_grade1 = (int)$t_grade;
        $ft_grade1 = (int)$ft_grade;

        $FGV = $f_grade1 + $s_grade1 + $t_grade1 + $ft_grade1;
        $final_grade_value = $FGV / 4;

        if ($final_grade_value <= 74){
            $remarks = "Failed";
        }
        else {
            $remarks = "Passed";
        }

        //Add the value of all the grades to the Final Grades Table
        $AddTGSsql = "INSERT INTO total_grades_subjects (
        f_grades_for_subj, remarks)
        VALUES ('".$final_grade_value."', '".$remarks."')";
        mysqli_query($conn, $AddTGSsql);
        echo "<script>window.location.href='Faculty-StudRecInfo.php';</script>";
    } else {
    echo "cant add";
}


?>