<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    //user information will be gathered
    $id = $_SESSION["user_id"];
    $info_sql = "SELECT * FROM users
                WHERE user_id='".$id."'";

    $info_results = mysqli_query($conn, $info_sql);

    // If there is a connection error, then echo the description of the error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($info_results === false) {
        echo mysqli_error($conn);
    } else {
        $info = mysqli_fetch_all($info_results, MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Account Information</title>
    <link rel="stylesheet" href="../css/view-details.css">
    <?php require 'include/Parent-Header.php'; ?>
</head>

<body>

    <div class="container">
        <h2>Registered Parent Information</h2>

        <header>
            <?php
        $stud = $_SESSION["user_id"];
        $sql = "SELECT pp FROM users WHERE user_id='".$stud."'";
        $res = mysqli_query($conn,  $sql);

        if (mysqli_num_rows($res) > 0) {
            while ($users = mysqli_fetch_assoc($res)) {  ?>
            <div class="profile-picture">
                <img src="../uploaded-image/default-pp.jpg" alt="Profile Picture">
            </div>
            <?php } }?>
            <a href="Parent-EditAccInfo.php"><button class="submit-button">Edit
                    Information</button></a>
            <a href="Parent-DelAcc.php" c><button lass="cancel-button">Delete Account</button></a>
        </header>

        <table class="info-table">
            <?php foreach ($info as $data): ?>
            <tr>
                <td><strong>Name:</strong></td>
                <td><?= $data["first_name"] ?> <?= $data["last_name"] ?></td>
            </tr>
            <tr>
                <td><strong>Sex:</strong></td>
                <td><?= $data["sex"] ?></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td><?= $data["email"] ?></td>
            </tr>
            <tr>
                <td><strong>Phone Number:</strong></td>
                <td><?= $data["phone_number"] ?></td>
            </tr>
            <tr>
                <td><strong>Username:</strong></td>
                <td><?= $data["user_username"] ?></td>
            </tr>
            <tr>
                <td><strong>Registered Date:</strong></td>
                <td><?= $data["created_at"] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

    </div>

</body>

</html>