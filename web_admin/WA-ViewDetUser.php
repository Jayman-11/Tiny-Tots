<?php

session_start();
if (isset($_SESSION['wba_id']) && isset($_SESSION['wba_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    //Web Adminid will be collected using the session
    $wba_id = $_SESSION["wba_id"];

    //Get the user Id to provide the details of the specific user
    $user_id=htmlentities($_GET['user_id']);

    // Retrieve users/parents information
    $userQuery = "SELECT * FROM users WHERE user_id=$user_id";
    $userResult = $conn->query($userQuery);

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Information</title>
    <link rel="stylesheet" href="../css/view-details.css">
</head>

<body>
    <?php require 'include/WA-Header.php'; ?>

    <div class="container">
        <h2>Detailed User Information</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Value</th>
                </tr>
            </thead>
            <?php while ($userRow = $userResult->fetch_assoc()) : ?>
            <!-- Profile picture container -->
            <div class="profile-picture">
                <img src="../uploaded-image/<?=$userRow['pp']?>" alt="Profile Picture">
            </div>

            <tr>
                <td>User ID</td>
                <td><?php echo $userRow['user_id']; ?></td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><?php echo $userRow['first_name']; ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo $userRow['last_name']; ?></td>
            </tr>
            <tr>
                <td>Sex</td>
                <td><?php echo $userRow['sex']; ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo $userRow['email']; ?></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td><?php echo $userRow['phone_number']; ?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?php echo $userRow['user_username']; ?></td>
            </tr>
            <tr>
                <td>Registered Date</td>
                <td><?php echo $userRow['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>