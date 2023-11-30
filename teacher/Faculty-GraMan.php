<?php
session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require '../include/Connection.php';
    $conn = getDB();

    // Store the Faculty ID in a variable
    $faculty_id = $_SESSION["faculty_id"];
    $SR_ID = htmlentities($_GET['SR_ID']);

    // Gather the data for the grades of a specific student
    $showGrades = "SELECT * FROM grades_per_subject
        JOIN subjects ON grades_per_subject.subject_id = subjects.subject_id
        JOIN StudRec ON grades_per_subject.SR_ID = StudRec.SR_ID
        JOIN faculty ON grades_per_subject.faculty_id = faculty.faculty_id
        WHERE grades_per_subject.SR_ID='$SR_ID'
        AND grades_per_subject.subject_id = subjects.subject_id
        LIMIT 1";

    $GradesResult = $conn->query($showGrades);

    // Fetch student details
    $studentDetailsQuery = "SELECT * FROM StudRec
        JOIN enroll ON StudRec.en_id = enroll.en_id
        WHERE StudRec.SR_ID='$SR_ID'
        LIMIT 1";
    $studentDetailsQueryResult = $conn->query($studentDetailsQuery);
    $studentDetails = $studentDetailsQueryResult->fetch_assoc();
    $studfname = $studentDetails['stud_fname'];
    $studlname = $studentDetails['stud_lname'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty - Student Management</title>
    <link rel="stylesheet" href="../css/faculty-page.css">
</head>

<body>
    <?php require 'include/Faculty-Header.php'; ?>

    <div class="container"><button onclick="window.print()" class="btn btn-primary">Print Grade Information</button><br>

        <?php while ($GrRow = $GradesResult->fetch_assoc()) : ?>
        <div class="student-name"><?php echo $studfname; ?> <?php echo $studlname; ?></div>

        <table class="student-table" border="1">
            <tr>
                <th>Subject</th>
                <th>First Grading</th>
                <th>Second Grading</th>
                <th>Third Grading</th>
                <th>Fourth Grading</th>
                <!-- <th>Faculty In Charge</th> -->
                <th>Total Grade</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>

            <tr>
                <td><?php echo $GrRow['subjects']; ?></td>
                <td class="grading-column">
                    <?php echo (empty($GrRow["f_grading"])) ? 'No Record Yet' : $GrRow["f_grading"]; ?></td>
                <td class="grading-column">
                    <?php echo (empty($GrRow["s_grading"])) ? 'No Record Yet' : $GrRow["s_grading"]; ?></td>
                <td class="grading-column">
                    <?php echo (empty($GrRow["t_grading"])) ? 'No Record Yet' : $GrRow["t_grading"]; ?></td>
                <td class="grading-column">
                    <?php echo (empty($GrRow["fourth_grading"])) ? 'No Record Yet' : $GrRow["fourth_grading"]; ?></td>
                <!-- <td><?php
                // echo $GrRow['f_fname']; ?> <?php 
                // echo $GrRow['f_lname']; 
                ?></td> -->

                <?php
            // Calculate total grade and remarks
            $totalGrades = '';
            $remarks = '';

            if (!empty($GrRow["f_grading"]) && !empty($GrRow["s_grading"]) && !empty($GrRow["t_grading"]) && !empty($GrRow["fourth_grading"])) {
                $totalGrades = ($GrRow['f_grading'] + $GrRow['s_grading'] + $GrRow['t_grading'] + $GrRow['fourth_grading']) / 4;
                $remarks = ($totalGrades <= 74) ? 'Failed' : 'Passed';
            }
            ?>

                <td><?php echo $totalGrades; ?></td>
                <td><?php echo $remarks; ?></td>

                <td>
                    <!-- <form method="post">
                    <input type="hidden" name="SR_ID" value="<?php 
                    // echo $SR_ID; ?>">
                    <input type="hidden" name="subject_id" value="<?php 
                    // echo $GrRow['subject_id']; ?>">
                    <input type="hidden" name="f_grades_for_subj" value="<?php 
                    // echo $totalGrades; ?>">
                    <input type="hidden" name="remarks" value="<?php 
                    // echo $remarks; ?>">
                    <input type="submit" value="Calculate Grades" />
                </form> -->

                    <a href="Faculty-AddGrades.php?grades_id=<?= $GrRow["grades_id"] ?>">
                        <button class="btn btn-success">Add/Edit Grades</button>
                    </a>
                    <!-- <a href="Faculty-DelGrades.php?grades_id=<?= $GrRow["grades_id"] ?>">
                    <button class="btn btn-danger">Delete Grades</button>
                </a> -->
                </td>
            </tr>
        </table>
        <?php endwhile; ?>
    </div>
</body>

<!-- <?php
    // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     // Add the value of all the grades to the Final Grades Table
    //     $AddTGSsql = "INSERT INTO total_grades_subjects (SR_ID, subject_id,
    //     f_grades_for_subj, remarks)
    //     VALUES ('$SR_ID', '{$GrRow['subject_id']}', '{$totalGrades}', '{$remarks}')";
    //     mysqli_query($conn, $AddTGSsql);
    //     echo "<script>window.location.href='Faculty-StudRecInfo.php';</script>";
    // }
    ?> -->

</html>