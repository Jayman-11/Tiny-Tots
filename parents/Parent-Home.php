<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    // Events Table
    $showEvent = "SELECT * from ((event inner join scadmins on event.sca_id = scadmins.sca_id) 
    inner join preschool on event.ps_id = preschool.ps_id)";

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
    <title>Parent Dashboard</title>
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
    <?php require 'include/Parent-Header.php'; ?>
    <div class="container1">
        <?php if (empty($showE)): ?>
        <h2>No Events found</h2>
        <?php else: ?>
        <h2>List of Events</h2>

        <table class="info-table">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Event Date</th>
                    <th>Preschool Name</th>
                    <th class="button-col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($showE as $event): ?>
                <tr>
                    <td class="event-title"><?= $event["event_title"] ?></td>
                    <td class="event-date"><?= $event["event_date"] ?></td>
                    <td class="event-type"><?= $event["school_name"] ?></td>
                    <td class="button-group">
                        <a href="Parent-DetEvent.php?event_id=<?= $event[
                                            "event_id"
                                        ] ?>"><button>View Detailed Information</button></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>

</html>