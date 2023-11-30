<?php
session_start();
if (isset($_SESSION['wba_id']) && isset($_SESSION['wba_username'])) {
    require '../include/Connection.php';
    $conn = getDB();

    $wba_id = $_SESSION["wba_id"];
    $faculty_id = htmlentities($_GET['faculty_id']);

    $FacultyQuery = "SELECT * FROM faculty JOIN preschool ON faculty.ps_id = preschool.ps_id WHERE faculty_id=$faculty_id";
    $facultyResult = $conn->query($FacultyQuery);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Information</title>
    <link rel="stylesheet" href="../css/view-details.css">
</head>

<body>
    <?php require 'include/WA-Header.php'; ?>

    <div class="container">
        <h3>Detailed Faculty Information</h3>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Value</th>
                </tr>
            </thead>
            <?php while ($facultyRow = $facultyResult->fetch_assoc()) : ?>
            <div class="profile-picture">
                <img src="../uploaded-image/<?=$facultyRow['f_profile']?>" alt="Profile Picture">
            </div>
            <tr>
                <td>Faculty ID</td>
                <td><?php echo $facultyRow['fac_id']; ?></td>
            </tr>
            <tr>
                <td>Faculty Name</td>
                <td><?php echo $facultyRow['f_fname'] . ' ' . $facultyRow['f_lname']; ?></td>
            </tr>
            <tr>
                <td>Preschool Name</td>
                <td><?php echo $facultyRow['school_name']; ?></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><?php echo $facultyRow['f_DOB']; ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?php echo $facultyRow['f_address']; ?></td>
            </tr>
            <tr>
                <td>Marital Status</td>
                <td><?php echo $facultyRow['f_m_status']; ?></td>
            </tr>
            <tr>
                <td>Nationality</td>
                <td><?php echo $facultyRow['f_nationality']; ?></td>
            </tr>
            <tr>
                <td>Religion</td>
                <td><?php echo $facultyRow['f_religion']; ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo $facultyRow['f_gender']; ?></td>
            </tr>
            <tr>
                <td>License No</td>
                <td><?php echo $facultyRow['f_licenseNo']; ?></td>
            </tr>
            <tr>
                <td>Subject</td>
                <td><?php echo $facultyRow['f_subject']; ?></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td><?php echo $facultyRow['f_phonenumber']; ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo $facultyRow['f_email']; ?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo $facultyRow['f_username']; ?></td>
            </tr>
            <tr>
                <td>Account Status</td>
                <td><?php echo $facultyRow['f_status']; ?></td>
            </tr>
            <tr>
                <td>Registered Date</td>
                <td><?php echo $facultyRow['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>