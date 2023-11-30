<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $SR_ID=htmlentities($_GET['SR_ID']);

    // Enrollment Table
    $showStudRec = "SELECT * from StudRec 
                join preschool on StudRec.ps_id = preschool.ps_id
                join users on StudRec.user_id = users.user_id
                left join adviser on StudRec.adviser_ID = adviser.adviser_ID
                join enroll on StudRec.en_id = enroll.en_id
                where StudRec.ps_id = '".$sca_id."' 
                AND StudRec.SR_ID = '$SR_ID' LIMIT 1";

    $showStudRec_results = mysqli_query($conn, $showStudRec);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showStudRec_results === false) {
        echo mysqli_error($conn);
    } else {
        $showSTR = mysqli_fetch_all($showStudRec_results, MYSQLI_ASSOC);
    }

    // Retrieve the list of subjects where the student is enrolled
    $SPSQuery = "SELECT * FROM stud_per_subject
    JOIN StudRec ON stud_per_subject.SR_ID = StudRec.SR_ID
    JOIN subjects ON stud_per_subject.subject_id = subjects.subject_id
    JOIN faculty ON stud_per_subject.faculty_id = faculty.faculty_id
    WHERE stud_per_subject.SR_ID = '$SR_ID'";
    
    $SPS_results = mysqli_query($conn, $SPSQuery);

    if ($SPS_results === false) {
        echo mysqli_error($conn);
    } else {
        $showSPS = mysqli_fetch_all($SPS_results, MYSQLI_ASSOC);
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
    .container1 {
        max-width: 300px;
        margin: 10px auto;
        background-color: #ffffff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body>
    <?php require 'include/SCA-Header.php'; ?>
    <div class="container">
        <?php foreach ($showSTR as $STR): ?>
        <h3>Student Name: <?= $STR["stud_fname"] ?> <?= $STR["stud_lname"] ?></h3>
        <h2>Subjects</h2>

        <?php if (empty($showSPS)): ?>
        <h2>No subjects assigned</h2>
        <a href="SCA-AddSubjSR.php?SR_ID=<?= $SR_ID ?>">
            <button>Add Subjects</button></a>

        <?php else: ?>
        <div class="container1" style="background-color: #2a9d8f; color: white;">
            <?php foreach ($showSPS as $SPS) { ?>

            <p><?= $SPS["subjects"]; }?></p>
        </div>
        <br><a href="SCA-AddSubjSR.php?SR_ID=<?= $SR_ID ?>">
            <button style="background-color: white; color: #2a9d8f ;">Add Subjects</button></a>

        <?php endif;?>
        <?php endforeach; ?>

</body>

</html>