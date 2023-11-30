<?php

session_start();
if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    //school admin information will be gathered
    $sca_id = $_SESSION["sca_id"];

    $sql = "SELECT * FROM scadmins WHERE sca_id='".$sca_id."'";
    $query = mysqli_query($conn, $sql);
    $results = mysqli_fetch_assoc($query);
    $sca_ps = $results['ps_id'];


    $info_sql = "SELECT * FROM scadmins
                WHERE sca_id='".$sca_id."'";

    $info_results = mysqli_query($conn, $info_sql);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($info_results === false) {
        echo mysqli_error($conn);
    } else {
        $info= mysqli_fetch_all($info_results, MYSQLI_ASSOC);
    }

    // select the preschool admin information and the preschool data
    $sql1 = "SELECT * FROM scadmins
    JOIN preschool ON scadmins.ps_id = preschool.ps_id
    WHERE scadmins.ps_id = preschool.ps_id limit 1;";

    $query1 = mysqli_query($conn, $sql1);

    if ($query1 === false) {
        echo mysqli_error($conn);
    } else {
        $info1= mysqli_fetch_all($query1, MYSQLI_ASSOC);
    }
    
     // Retrieve preschool book records and information
     $PS_bookInfo = "SELECT * FROM ps_books JOIN preschool 
     ON ps_books.ps_id = preschool.ps_id
     WHERE ps_books.ps_id ='$sca_ps'";
     $PS_bookInfoResult = $conn->query($PS_bookInfo);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preschool Account Information</title>
    <link rel="stylesheet" href="../css/view-details.css">

    <head>

    <body>
        <?php require 'include/SCA-Header.php'; ?>
        <div class="container">
            <h2>Registered Admin Information</h2>

            <?php foreach ($info as $data): ?>
            <p>First Name: <?= $data["sca_first_name"] ?></p>
            <p>Last Name: <?= $data["sca_last_name"] ?></p>
            <p>School Admin Username: <?= $data["sca_username"] ?></p>
            <?php endforeach; ?>
            <a href="SCA-EditAccInfo.php" class="buttons green" style="text-decoration:none">Edit Information</a>
            <a href="SCA-changepass.php" class="buttons red" style="text-decoration:none">Change Password</a>

            <h2>Registered Preschool Information</h2>
            <button onclick="window.print()" class="buttons green">Print Preschool Information</button>
            <header>
                <?php // php code in order to retrieve and display the 
                    // profile picture of the registered preschool
                    $stud = $_SESSION["sca_id"];
                    
                    $sql = "SELECT ps_profile FROM preschool WHERE ps_id='".$sca_ps."'";
                    $res = mysqli_query($conn,  $sql);

                    if (mysqli_num_rows($res) > 0) {
                        while ($preschool = mysqli_fetch_assoc($res)) {  ?>

                <div class="profile-picture">
                    <img src="../uploaded-image/<?=$preschool['ps_profile']?>" alt="Profile Picture">
                </div>

                <?php } }?>

            </header>
            <br><a href="SCA-EditAccPreInfo.php" class="buttons green" style="text-decoration:none">Edit
                Information</a><br>

            <?php foreach ($info1 as $pdata): ?>
            <table class="info-table">
                <tr>
                    <th>Attribute</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>School Name</td>
                    <td><?= $pdata["school_name"] ?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><?= $pdata["address"] ?></td>
                </tr>
                <tr>
                    <td>Hours</td>
                    <td><?= $pdata["hours"] ?></td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td><?= $pdata["phone_number"] ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= $pdata["email"] ?></td>
                </tr>
                <tr>
                    <td>Website</td>
                    <td><?= $pdata["website"] ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><?= $pdata["status"] ?></td>
                </tr>
                <tr>
                    <td>Fees</td>
                    <td><?= $pdata["fees"] ?></td>
                </tr>
            </table>
            <?php endforeach; ?>

            <h2>Preschool Photos (Facilities)</h2>
            <a href="SCA-PSPhotos.php" class="buttons green" style="text-decoration:none">Upload Photos for
                Preschool</a>
            <?php // collect all the media that was uploaded by the preschool

                $sql = "SELECT * FROM scadmins WHERE sca_id='".$sca_id."'";
                $query = mysqli_query($conn, $sql);
                $results = mysqli_fetch_assoc($query);
                $sca_ps = $results['ps_id'];

                $sql = "SELECT photo_name FROM ps_media WHERE ps_id='".$sca_ps."'";
                $res = mysqli_query($conn,  $sql);

                if (mysqli_num_rows($res) > 0) {
                    while ($preschool = mysqli_fetch_assoc($res)) {  ?>

            <div class="profile-picture">
                <img src="../uploaded-image/<?=$preschool['photo_name']?>" alt="Profile Picture">
            </div>
            <?php } }?>

            <br>
            <h2>Preschool Book Details</h2>
            <a href="SCA-AddBook.php?ps_id=<?= $sca_ps ?>" class="buttons green" style="text-decoration:none">
                Add Book Details</a><br>

            <table class="info-table">
                <tr>
                    <th>Book Title</th>
                    <th>Book Author</th>
                    <th>Book Publisher</th>
                    <th>Subject</th>
                    <th>Action</th>
                </tr>
                <?php 
                while ($PSBI = $PS_bookInfoResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $PSBI['b_name']; ?></td>
                    <td><?php echo $PSBI['b_author']; ?></td>
                    <td><?php echo $PSBI['b_publisher']; ?></td>
                    <td><?php echo $PSBI['subject_name']; ?></td>
                    <td><a href="SCA-DelBook.php?psb_id=<?= $PSBI["psb_id"] ?>">
                            <button>Delete Details</button></a></td>
                    <!-- Details/Each row in the column -->
                </tr>
                <?php  endwhile; ?>
            </table>
        </div>

</html>