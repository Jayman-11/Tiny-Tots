<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $faculty_id=htmlentities($_GET['faculty_id']);

    // faculty Table
    $showFAC = "SELECT * from ((faculty 
    inner join scadmins on faculty.sca_id = scadmins.sca_id) 
    inner join preschool on faculty.ps_id = preschool.ps_id) 
    WHERE faculty.faculty_id='".$faculty_id."'";

    $showFAC_results = mysqli_query($conn, $showFAC);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showFAC_results === false) {
        echo mysqli_error($conn);
    } else {
        $showF = mysqli_fetch_all($showFAC_results, MYSQLI_ASSOC);  
         
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit - Faculity Information</title>
    <link rel="stylesheet" href="../css/faculty-page.css">
</head>

<body>
    <?php require 'include/SCA-Header.php'; ?>
    <?php foreach ($showF as $fac): ?>
    <div class="container">
        <h2 class="delete-attendance-header">Delete Faculty Member</h2>
        <table class="student-table">
            <tr>
                <th>Attribute</th>
                <th>Value</th>
                <!-- Title for the columns -->
            </tr>
            <tr>
                <td>Full Name </td>
                <td><?= $fac["f_fname"] ?> <?= $fac["f_lname"] ?></td>
            </tr>
            <tr>
                <td>Faculty Date of Birth</td>
                <td> <?= $fac["f_DOB"] ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?= $fac["f_address"] ?></td>
            </tr>
            <tr>
                <td>Marital Status</td>
                <td><?= $fac["f_m_status"] ?></td>
            </tr>
            <tr>
                <td>Nationality</td>
                <td><?= $fac["f_nationality"] ?></td>
            </tr>
            <tr>
                <td>Religion</td>
                <td><?= $fac["f_religion"] ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?= $fac["f_gender"] ?></td>
            </tr>
            <tr>
                <td>Lincese No</td>
                <td><?= $fac["f_licenseNo"] ?></td>
            </tr>
            <tr>
                <td>Subject</td>
                <td><?= $fac["f_subject"] ?></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td><?= $fac["f_phonenumber"] ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?= $fac["f_email"] ?></td>
            </tr>
            <tr>
                <td>School Name</td>
                <td><?= $fac["school_name"] ?></td>
            </tr>
            <tr>
                <td>Registered by Admin</td>
                <td><?= $fac["sca_first_name"] ?> <?= $fac["sca_last_name"] ?></td>
            </tr>

            <?php endforeach; ?>

            <form method="post" enctype="multipart/form-data" class="edit-form">
                <div class="container">
                    <p class="confirmation-message">Are you sure that you want to delete this faculty member's account?
                    </p>
                    <button class="btn btn-danger">Delete</button>
                    <a href="SCA-FAM.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>


            <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_GET['faculty_id'])) {

                    $sql = "SELECT * FROM faculty WHERE faculty_id='".$faculty_id."'";

                $results = mysqli_query($conn, $sql);

                if (mysqli_num_rows($results)==1) {
                    $sql_2 = "DELETE FROM faculty WHERE faculty_id='".$faculty_id."'";
                    mysqli_query($conn, $sql_2);
                    echo "The faculty member's accoount has been removed. Redirecting...";
                    echo "<script >window.location.href='SCA-FAM.php';</script >";
                    // header("Refresh:2; url=SCA-FAM.php");  
                    exit();
                } else {
                    echo "Can't change details. Redirecting to Account Information page";
                    echo "<script >window.location.href='SCA-FAM.php';</script >";
                    // header("Refresh:2; url=SCA-FAM.php");  
                    exit();
                    } 
                    
                }
            }

            ?>
    </div>
</body>

</html>