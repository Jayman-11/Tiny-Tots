<?php

session_start();
if (isset($_SESSION['wba_id']) && isset($_SESSION['wba_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    //Web Admin information will be gathered
    $wba_id = $_SESSION["wba_id"];

    //Preschool ID will be gathered
    $ps_id=htmlentities($_GET['ps_id']);

    $info_sql = "SELECT * FROM webadmins
                WHERE wba_id='".$wba_id."'";

    $info_results = mysqli_query($conn, $info_sql);

    // If there is a connection error, then echo the description of the error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($info_results === false) {
        echo mysqli_error($conn);
    } else {
        $info= mysqli_fetch_all($info_results, MYSQLI_ASSOC);
    }

    // Retrieve preschool information
    $preschoolQuery = "SELECT * FROM preschool WHERE ps_id =$ps_id;";
    $preschoolResult = $conn->query($preschoolQuery);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Preschool</title>
    <link rel="stylesheet" href="../css/view-details.css">
</head>

<body>
    <?php require 'include/WA-Header.php'; ?>
    <div class="container">
        <h2>Update Preschool</h2>

        <div class="form-container">
            <form method="post" enctype="multipart/form-data">
                <p>Update Status</p>
                <select name="status">
                    <option value="Registered">Registered</option>
                    <option value="Pending">Pending</option>
                </select>
                <button class="submit-button">Submit</button>
                <a href="WA-ChangeReq.php" class="cancel-button">Cancel</a>
            </form>
        </div>

        <table class="info-table">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Value</th>
                </tr>
            </thead>
            <?php while ($preschoolRow = $preschoolResult->fetch_assoc()) : ?>
            <div class="profile-picture">
                <img src="../uploaded-image/<?php echo $preschoolRow['ps_profile']; ?>" alt="Profile Picture">
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

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_GET['ps_id'])) {
            $sql = "SELECT * FROM preschool WHERE ps_id='".$ps_id."' LIMIT 1;";
            $results = mysqli_query($conn, $sql);
            if (mysqli_num_rows($results) == 1) {
                $sql_2 = "UPDATE preschool SET status = '" . $_POST['status'] . "'
                WHERE ps_id='" . $ps_id . "'";
                mysqli_query($conn, $sql_2);
                echo "The preschool information has been changed. Redirecting...";
                echo "<script>window.location.href='WA-ChangeReq.php';</script>";
                exit();
            } else {
                echo "Can't change preschool information. Redirecting...";
                header("Refresh:2; url=WA-ChangeReq.php");
                exit();
            }
        }
    }
    ?>
</body>

</html>