<?php
session_start();
if (isset($_SESSION['wba_id']) && isset($_SESSION['wba_username'])) {
    require '../include/Connection.php';
    $conn = getDB();

    $wba_id = $_SESSION["wba_id"];
    $ps_id = htmlentities($_GET['ps_id']);

    $preschoolQuery = "SELECT * FROM preschool WHERE ps_id=$ps_id";
    $preschoolResult = $conn->query($preschoolQuery);
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
        <h2>Detailed Preschool Information</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Value</th>
                </tr>
            </thead>
            <?php while ($preschoolRow = $preschoolResult->fetch_assoc()) : ?>
            <div class="profile-picture">
                <img src="../uploaded-image/<?=$preschoolRow['ps_profile']?>" alt="Profile Picture">
            </div>
            <tr>
                <td>Preschool ID</td>
                <td><?php echo $preschoolRow['ps_id']; ?></td>
            </tr>
            <tr>
                <td>School Name</td>
                <td><?php echo $preschoolRow['school_name']; ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?php echo $preschoolRow['address']; ?></td>
            </tr>
            <tr>
                <td>Hours</td>
                <td><?php echo $preschoolRow['hours']; ?></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td><?php echo $preschoolRow['phone_number']; ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo $preschoolRow['email']; ?></td>
            </tr>
            <tr>
                <td>Website</td>
                <td><?php echo $preschoolRow['website']; ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?php echo $preschoolRow['status']; ?></td>
            </tr>
            <tr>
                <td>Fees</td>
                <td><?php echo $preschoolRow['fees']; ?></td>
            </tr>
            <tr>
                <td>Zoom Links</td>
                <td><?php echo $preschoolRow['ps_zoom']; ?></td>
            </tr>
            <tr>
                <td>Registered Date</td>
                <td><?php echo $preschoolRow['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>