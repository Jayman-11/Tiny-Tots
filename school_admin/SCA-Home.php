<?php
session_start();
require '../include/Connection.php';

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    
    $conn = 'getDB'();
    $showEvent = "SELECT * from event 
                join scadmins on event.sca_id = scadmins.sca_id
                join preschool on event.ps_id = preschool.ps_id
                where event.sca_id = '".$sca_id."'";

    $showEvent_results = mysqli_query($conn, $showEvent);

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
    <title>School Admin Dashboard</title>
    <link rel="stylesheet" href="../css/view-information-styles.css">
    <style>
    .container1 {
        max-width: 800px;
        margin: 20px auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    </style>

</head>

<body>
    <?php require 'include/SCA-Header.php'; ?>
    <div class="container1">

        <?php if (empty($showE)): ?>
        <h2>No Events found</h2>
        <?php else: ?>
        <h2>List of Events</h2>

        <a href="SCA-CreEvent.php"><button class="button">Create Event</button></a>

        <table class="info-table">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Event Date</th>
                    <th>Event Type</th>
                    <th>Event Information</th>
                    <th class="button-col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($showE as $event): ?>
                <tr>
                    <td class="event-title"><?= $event["event_title"] ?></td>
                    <td class="event-date"><?= $event["event_date"] ?></td>
                    <td class="event-type"><?= $event["event_type"] ?></td>
                    <td class="event-info"><?= $event["infotext"] ?></td>
                    <td class="button-group">
                        <a href="SCA-DetEvent.php?event_id=<?= $event["event_id"] ?>"><button>Attendance
                                Details</button></a><br>
                        <a href="SCA-EdEvent.php?event_id=<?= $event["event_id"] ?>"><button>Edit
                                Details</button></a><br>
                        <a href="SCA-DelEvent.php?event_id=<?= $event["event_id"] ?>"><button>Delete
                                Event</button></a><br>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

    </div>

</body>

</html>