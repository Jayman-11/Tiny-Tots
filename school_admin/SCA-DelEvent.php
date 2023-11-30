<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $event_id=htmlentities($_GET['event_id']);

    // Events Table
    $showEvent = "SELECT * from ((event 
    inner join scadmins on event.sca_id = scadmins.sca_id) 
    inner join preschool on event.ps_id = preschool.ps_id) 
    WHERE event.event_id='".$event_id."'";

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
    <title>Delete Event</title>
    <link rel="stylesheet" href="../css/meetings-management.css">
</head>

<body>
    <?php require 'include/SCA-Header.php'; ?>
    <?php foreach ($showE as $event): ?>
    <div class="container">
        <h2 class="page-title">Delete Event</h2>
        <p><?= $event["event_title"] ?></p>
        <p><?= $event["event_date"] ?></p>
        <p><?= $event["event_type"] ?></p>
        <p><?= $event["infotext"] ?></p>
        <p><?= $event["sca_first_name"] ?> <?= $event["sca_last_name"] ?></p>
        <p><?= $event["school_name"] ?></p>
        <?php endforeach; ?>

        <form method="post" enctype="multipart/form-data" class="edit-form">
            <p>Are you sure that you want to delete this event?</p>
            <button class="approve-button">Delete</button>
            <a href="SCA-Home.php" class="delete-button">Cancel</a>
        </form>
    </div>
</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_GET['event_id'])) {

        $sql = "SELECT * FROM event WHERE event_id='".$event_id."' LIMIT 1;";

    $results = mysqli_query($conn, $sql);

    if (mysqli_num_rows($results)==1) {
        $sql_2 = "DELETE FROM event WHERE event_id='".$event_id."'";
        mysqli_query($conn, $sql_2);
        echo "The event has been removed. Redirecting...";
        echo "<script >window.location.href='SCA-Home.php';</script >";
        // header("Refresh:2; url=SCA-Home.php");  
        exit();
    } else {
        echo "Can't remove event. Redirecting...";
        echo "<script >window.location.href='SCA-Home.php';</script >";
        // header("Refresh:2; url=SCA-Home.php");  
        exit();
        } 
        
    }
}

?>