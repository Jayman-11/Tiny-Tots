<?php

session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    //faculty information will be gathered
    $faculty_id = $_SESSION["faculty_id"];

    $info_sql = "SELECT * FROM faculty
                JOIN preschool on faculty.ps_id = preschool.ps_id
                WHERE faculty_id='".$faculty_id."'";

    $info_results = mysqli_query($conn, $info_sql);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($info_results === false) {
        echo mysqli_error($conn);
    } else {
        $info= mysqli_fetch_all($info_results, MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Account Information</title>
    <link rel="stylesheet" href="../css/view-details.css">
</head>

<body>
    <?php require 'include/Faculty-Header.php'; ?>
    <div class="container">
        <h2>Registered Faculty Information</h2>

        <header>
            <?php 
            $faculty_id = $_SESSION["faculty_id"];
            $sql = "SELECT f_profile FROM faculty WHERE faculty_id='".$faculty_id."'";
            $res = mysqli_query($conn,  $sql);

            if (mysqli_num_rows($res) > 0) {
                while ($users = mysqli_fetch_assoc($res)) {  ?>

            <div class="profile-picture">
                <img src="../uploaded-image/<?=$users['f_profile']?>" alt="Profile Picture">
            </div>

            <?php } }?>

            <a href="Faculty-EditAccInfo.php" class="buttons green" style="text-decoration: none;">Edit Information</a>
            <!-- <a href="Faculty-DelAcc.php" class="buttons red">Delete Account</a> -->
        </header>

        <?php foreach ($info as $data): ?>
        <table class="info-table">
            <tr>
                <th>Attribute</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Faculty ID</td>
                <td><?= $data["fac_id"] ?></td>
            </tr>
            <tr>
                <td>Preschool Name</td>
                <td><?= $data["school_name"] ?></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><?= $data["f_fname"] ?> <?= $data["f_lname"] ?></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><?= $data["f_DOB"] ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?= $data["f_address"] ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?= $data["email"] ?></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td><?= $data["phone_number"] ?></td>
            </tr>
            <tr>
                <td>Marital Status</td>
                <td><?= $data["f_m_status"] ?></td>
            </tr>
            <tr>
                <td>Nationality</td>
                <td><?= $data["f_nationality"] ?></td>
            </tr>
            <tr>
                <td>Religion</td>
                <td><?= $data["f_religion"] ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?= $data["f_gender"] ?></td>
            </tr>
            <tr>
                <td>License No.</td>
                <td><?= $data["f_licenseNo"] ?></td>
            </tr>
            <tr>
                <td>Subject</td>
                <td><?= $data["f_subject"] ?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?= $data["f_username"] ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?= $data["f_status"] ?></td>
            </tr>
            <tr>
                <td>Registered Date</td>
                <td><?= $data["created_at"] ?></td>
            </tr>
        </table>
        <?php endforeach; ?>
    </div>
</body>

</html>