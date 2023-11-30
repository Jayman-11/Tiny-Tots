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

<html lang="en">

<head>
    <title>Event Attendance</title>
    <?php require 'include/SCA-Header.php'; ?>
    <link rel="stylesheet" href="../css/meetings-management.css">
    <style>
    .edit-form {
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .edit-form div {
        margin-bottom: 15px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #2a9d8f;
    }

    input,
    select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }
    </style>
</head>


<body>

    <form method="post" enctype="multipart/form-data" class="edit-form">
        <div>
            <h2>Edit Event Details</h2>
            <div>
                <label for="event_title">Event Title: </label>
                <input type="text" name="event_title" id="event_title" placeholder="Enter the event title" required>
            </div>

            <div>
                <label for="event_date">Event Date: </label>
                <input type="date" name="event_date" id="event_date" placeholder="Enter the event date" required>
            </div>

            <label>Event type </label><select name="event_type">
                <option value="Announcement">Announcement</option>
                <option value="Orientation">Orientation</option>
                <option value="Parent and Faculty Meeting">Parent and Faculty Meeting</option>
                <option value="Others">Others</option>
            </select>

            <div>
                <label for="infotext">Event Information: </label>
                <input type="text" name="infotext" id="infotext" placeholder="Enter the event information" required>
            </div>

            <button class="submit-button">Submit Changes</button>
    </form>
    <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_GET['event_id'])) {
                    
                    $e_title=htmlentities($_POST['event_title']);
                    $e_date=htmlentities($_POST['event_date']);
                    $e_type=htmlentities($_POST['event_type']);
                    $e_text=htmlentities($_POST['infotext']);

                    $sql = "SELECT * FROM event WHERE event_id='".$event_id."' LIMIT 1;";

                $results = mysqli_query($conn, $sql);

                if (mysqli_num_rows($results)==1) {
                    $sql_2 = "UPDATE event SET event_title='$e_title', 
                    event_date='$e_date', event_type='$e_type', infotext='$e_text'
                    WHERE event_id='".$event_id."'";
                    mysqli_query($conn, $sql_2);
                    echo "The event information has been changed. Redirecting...";
                    echo "<script >window.location.href='SCA-Home.php';</script >";
                    // header("Refresh:2; url=SCA-Home.php");  
                    exit();
                } else {
                    echo "Can't change details. Redirecting to Account Information page";
                    echo "<script >window.location.href='SCA-Home.php';</script >";
                    // header("Refresh:2; url=SCA-Home.php");  
                    exit();
                    } 
                    
                }
            }

        ?>

</body>

</html>