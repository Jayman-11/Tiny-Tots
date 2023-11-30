<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    
    require '../include/Connection.php';
    $conn = getDB();

    // Events Table
    $event_id=htmlentities($_GET['event_id']);
    $user_id = $_SESSION["user_id"];

    if (isset($_GET["event_id"])) {

        $showEvent = "SELECT * from ((event inner join scadmins on event.sca_id = scadmins.sca_id) 
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
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Attendance</title>
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

    .center {
        text-align: center;
    }
    </style>

</head>

<body>
    <?php require 'include/Parent-Header.php'; ?>
    <div class="container1">
        <table class="info-table">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Event Date</th>
                    <th>Event Type</th>
                    <th>Event Details</th>
                    <th>Posted by</th>
                    <th>Preschool Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($showE as $event): ?>
                <tr>
                    <td class="event-title"><?= $event["event_title"] ?></td>
                    <td class="event-title"><?= $event["event_date"] ?></td>
                    <td class="event-title"><?= $event["event_type"] ?></td>
                    <td class="event-title"><?= $event["infotext"] ?></td>
                    <td class="event-title"><?= $event["sca_first_name"] ?> <?= $event["sca_last_name"] ?></td>
                    <td class="event-title"><?= $event["school_name"] ?></td>

                    <?php // check the eattend if the user is alredy present there

                            $CheckAttEvent = "SELECT * FROM eattend 
                            JOIN users ON eattend.user_id = users.user_id
                            JOIN event on eattend.event_id = event.event_id
                            WHERE eattend.user_id = '$user_id' AND eattend.event_id = $event_id LIMIT 1;";
                            $CheckAttEvent_results = mysqli_query($conn, $CheckAttEvent);

                            // If there is an connection error, then echo the description of the  error
                            // Else, store the results on a variable using mysqli_fetch_all
                            if ($CheckAttEvent_results === false) {
                                echo mysqli_error($conn);
                            } else {
                                $showEATT = mysqli_fetch_all($CheckAttEvent_results, MYSQLI_ASSOC);
                            }
                            ?>
                    <div class="center">
                        <?php if (!empty($showEATT )): ?> Your attendance has been added on this event. </p>
                        <a href="Parent-DelEventAtt.php?event_id=<?= $event[
                                        "event_id"
                                    ] ?>"><button>Remove Attendance</button></a>
                        <p><?php else: ?> Want to attend this event?
                            <a href="Parent-DetEventAdd.php?event_id=<?= $event[
                                        "event_id"
                                    ] ?>"><button>Yes</button></a>
                            <a href="Parent-Home.php"><button>No</button></a>
                            <?php endif; ?>

                    </div>
            </tbody>
        </table>
    </div>
    <?php endforeach; ?>
</body>

</html>