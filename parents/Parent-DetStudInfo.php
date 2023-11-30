<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{

    require '../include/Connection.php';
    $conn = getDB();

    $user_id = $_SESSION["user_id"];
    $SR_Id=htmlentities($_GET['SR_ID']);

    // Student Record Table
    $showRecord = "SELECT * from StudRec 
    JOIN users on StudRec.user_id = users.user_id
    JOIN enroll on StudRec.en_id = enroll.en_id
    JOIN preschool ON StudRec.ps_id = preschool.ps_id
    JOIN adviser on StudRec.adviser_ID = adviser.adviser_ID
    WHERE StudRec.SR_ID = '".$SR_Id."';";

    $sshowRecord_results = mysqli_query($conn, $showRecord);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($sshowRecord_results === false) {
        echo mysqli_error($conn);
    } else {
        $showR = mysqli_fetch_all($sshowRecord_results, MYSQLI_ASSOC);
    }

    // Grades Table
    $showGrade = "SELECT * from grades_per_subject 
    JOIN StudRec on grades_per_subject.SR_ID = StudRec.SR_ID
    JOIN faculty on grades_per_subject.faculty_id = faculty.faculty_id
    JOIN subjects on grades_per_subject.subject_id = subjects.subject_id
    WHERE grades_per_subject.SR_ID = $SR_Id
    ORDER by grades_id;";

    $showGrade_results = mysqli_query($conn, $showGrade);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showGrade_results === false) {
        echo mysqli_error($conn);
    } else {
        $showG = mysqli_fetch_all($showGrade_results, MYSQLI_ASSOC);
    }

    // Attendance Table
    $showAtt = "SELECT * from attendance 
    JOIN adviser on attendance.adviser_ID = adviser.adviser_ID
    JOIN StudRec on attendance.SR_ID = StudRec.SR_ID
    WHERE attendance.SR_ID = '".$SR_Id."'";

    $AttResult = $conn->query($showAtt);
}

?>

<html>

<head>
    <title>Student Grade and Attendance</title>
    <link rel="stylesheet" href="../css/faculty-page.css">

    <style>
    table,
    th,
    td {
        border: 1px solid black;
    }
    </style>
</head>
<?php require 'include/Parent-Header.php'; ?>


<body>
    <div class="container">
        <?php if (empty($showR)): ?>
        <h2>No Record Found</h2>

        <h1><?php else:  ?>
            <h2>Student Detailed Records</h2>
            <button onclick="window.print()" class="btn btn-primary">Print Student Record</button>

            <ul>
                <?php foreach ($showR as $Rec): ?>
                <li>
                    <div>
                        <p>Student Name: <?=$Rec["stud_fname"] ?> <?=$Rec["stud_lname"] ?></p>
                        <p>Preschool: <?=$Rec["school_name"] ?></p>
                        <p>Section: <?=$Rec["section"] ?></p>
                        <p>School Year: <?=$Rec["school_year"] ?></p> <br>
                        <?php endforeach; ?>

            </ul>
            <?php if (empty($showG)): ?>
            <h2>No Record Found for Student Grade</h2>

            <h1><?php else:  ?><h2>Student Grade</h2>
                <?php endif; ?>

                <table class="student-table" border="1">
                    <tr>
                        <th>Subject</th>
                        <th>First Grading</th>
                        <th>Second Grading</th>
                        <th>Third Grading</th>
                        <th>Fourth Grading</th>
                        <th>Faculty In Charge</th>
                        <th>Total Grade</th>
                        <th>Remarks</th>
                    </tr>
                    <?php foreach ($showG as $grade): ?>
                    <tr>
                        <td><?=$grade['subjects']; ?></td>
                        <td><?php echo (empty($grade["f_grading"])) ? "No Record Yet" : $grade["f_grading"]; ?></td>
                        <td><?php echo (empty($grade["s_grading"])) ? "No Record Yet" : $grade["s_grading"]; ?></td>
                        <td><?php echo (empty($grade["t_grading"])) ? "No Record Yet" : $grade["t_grading"]; ?></td>
                        <td><?php echo (empty($grade["fourth_grading"])) ? "No Record Yet" : $grade["fourth_grading"]; ?>
                        </td>
                        <td><?= $grade["f_fname"] ?> <?= $grade["f_lname"] ?></td>
                        <td>
                            <?php
                                $totalGrades = '';
                                $remarks = '';

                                if (!empty($grade["f_grading"]) && !empty($grade["s_grading"]) && !empty($grade["t_grading"]) && !empty($grade["fourth_grading"])) {
                                    $totalGrades = ($grade['f_grading'] + $grade['s_grading'] + $grade['t_grading'] + $grade['fourth_grading']) / 4;
                                    $remarks = ($totalGrades <= 74) ? 'Failed' : 'Passed';
                                }

                                echo (empty($totalGrades)) ? 'Incomplete Grades' : $totalGrades;
                                ?>
                        </td>
                        <td>
                            <?php echo (empty($remarks)) ? 'No Remarks Yet' : $remarks; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <?php endif; ?>
                </ul>


                <?php if (empty($AttResult)): ?>
                <h2>No Record Found for Attendance</h2>

                <?php else:  ?><h3>Student Attendance</h3>
                <table class="student-table" border="1">
                    <tr>
                        <th>Month</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>School Days</th>
                        <!-- Title for the columns -->
                    </tr>
                    <?php while ($AttRow = $AttResult->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $AttRow['month_for_attendance']; ?></td>
                        <td><?php echo $AttRow['number_of_present']; ?></td>
                        <td><?php echo $AttRow['number_of_absence']; ?></td>
                        <td><?php echo $AttRow['number_of_scdays']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                 </table>
                    <?php endif; ?>

    </div>
</body>

</html>