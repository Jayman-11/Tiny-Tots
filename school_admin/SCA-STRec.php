<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    // Enrollment Table
    $showStudRec = "SELECT * from StudRec 
                join preschool on StudRec.ps_id = preschool.ps_id
                join users on StudRec.user_id = users.user_id
                left join adviser on StudRec.adviser_ID = adviser.adviser_ID
                join enroll on StudRec.en_id = enroll.en_id
                where StudRec.ps_id = '".$sca_id."'";

    $showStudRec_results = mysqli_query($conn, $showStudRec);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showStudRec_results === false) {
        echo mysqli_error($conn);
    } else {
        $showSTR = mysqli_fetch_all($showStudRec_results, MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Admin - Student Record</title>
    <link rel="stylesheet" href="../css/faculty-page.css"> <!-- Add your custom stylesheet link here -->
    <style>
    .center {
        margin-left: auto;
        margin-right: auto;
    }
    </style>
</head>

<body>
    <?php require 'include/SCA-Header.php'; ?>
    <div class="container">
        <?php if (empty($showSTR)): ?>
        <div class="no-advisory-class">
            <h2>No student record found</h2>
        </div>
        <?php else: ?>
        <div class="advisory-class">

            <h2>Student Record List</h2>
            <table class="center student-table" border="1">
                <tr>
                    <th>Student Name</th>
                    <th>Adviser</th>
                    <th>Section</th>
                    <th>Grade Level</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($showSTR as $STR): ?>
                <tr>
                    <td><?= $STR["stud_fname"] ?> <?= $STR["stud_lname"] ?></td>
                    <td><?php if (empty($STR["adviser_ID"])): ?>
                        <a href="SCA-STRAssignAdv.php?SR_ID=<?= $STR["SR_ID"] ?>">
                            <button>Assign Adviser</button></a>
                        <?php else: 
                                $checkAD = $STR["adviser_ID"];

                            // Get the information of the adviser
                                $sql = "SELECT * FROM adviser
                                JOIN faculty ON adviser.faculty_id = faculty.faculty_id
                                WHERE adviser.adviser_ID= '$checkAD' 
                                LIMIT 1";
                                $query = mysqli_query($conn, $sql);
                                $results = mysqli_fetch_assoc($query);
                                $f_name = $results['f_fname'] . " " .$results['f_lname'];
                                //Print the name of the adviser
                                echo $f_name?>
                        <?php endif; ?>
                    </td>
                    <td><?php if (empty($STR["section"])): ?> No section yet
                        <?php else: echo $STR["section"]?><?php endif; ?>
                    </td>
                    <td><?= $STR["sr_grade_level"] ?> </td>
                    <td><a href="SCA-STRSubj.php?SR_ID=<?= $STR["SR_ID"] ?>">
                            <button class="action-btn">View Subjects</button></a>
                        <a href="SCA-UpgStud.php?SR_ID=<?= $STR["SR_ID"] ?>">
                            <button class="action-btn">Update Grade Level</button></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
</body>

</html>