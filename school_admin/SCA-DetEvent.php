<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $event_id=htmlentities($_GET['event_id']);

    // Events Table
    $showEvent = "SELECT * from eattend
                join event on eattend.event_id = event.event_id
                join users on eattend.user_id = users.user_id
                where eattend.event_id = '".$event_id."'";

    $showEvent_results = mysqli_query($conn, $showEvent);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showEvent_results === false) {
        echo mysqli_error($conn);
    } else {
        $showE = mysqli_fetch_all($showEvent_results, MYSQLI_ASSOC);  
         
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Attendance</title>
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
        <?php if (empty($showE)): ?>
        <h3>No Attendance found</h3>

        <?php else:  ?>
        <h3> Event Details </h3>
        <?php 
                        $sql = "SELECT * from eattend
                        join event on eattend.event_id = event.event_id
                        join users on eattend.user_id = users.user_id
                        where eattend.event_id = '".$event_id."'";

                        $query = mysqli_query($conn, $sql);

                        $results = mysqli_fetch_assoc($query);
                        $e_title = $results['event_title'];
                        $e_date = $results['event_date']; 
                        echo $e_title; 
                        echo "</br>";
                        echo $e_date;
                    ?>
        <h3>List of Attendees</h3>

        <?php foreach ($showE as $event): ?>
        <div class="container1" style="background-color: #2a9d8f; color: white;">
            <p><?= $event["first_name"] ?> <?= $event["last_name"] ?></p>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>