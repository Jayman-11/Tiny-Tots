<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();
    
}
?>

<html lang="en">

<head>
    <title>Add Event</title>
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
    <div>
        <h2>Event Details</h2>
        <form method="post" enctype="multipart/form-data" class="edit-form">

            <div>
                <label for="event_title">Event Title: </label>
                <input type="text" name="event_title" id="event_title" placeholder="Enter the event title" required>
            </div>

            <div>
                <label for="event_date">Event Date: </label>
                <input type="date" name="event_date" id="event_date" placeholder="Enter the event date" required>
            </div>

            <label>Event type</label> <select name="event_type">
                <option value="Announcement">Announcement</option>
                <option value="Orientation">Orientation</option>
                <option value="Parent and Faculty Meeting">Parent and Faculty Meeting</option>
                <option value="Others">Others</option>
            </select>

            <div>
                <label for="infotext">Event Information: </label>
                <input type="text" name="infotext" id="infotext" placeholder="Enter the event information" required>
            </div>


            <button class="submit-button">Add Event</button>
        </form>
    </div>

    <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // Select all the information of the SCA
                $sql = "SELECT * FROM scadmins WHERE sca_id='".$sca_id."'";
                $query = mysqli_query($conn, $sql);
                $results = mysqli_fetch_assoc($query);
                $sca_ps = $results['ps_id'];
            
                // Insert to the Event Table
                $addEvent = "INSERT INTO event (event_title, event_date, event_type, sca_id, ps_id, infotext)
                            VALUES ('". $_POST['event_title'] . "',
                            '". $_POST['event_date'] . "',
                            '". $_POST['event_type'] . "',
                            '$sca_id', 
                            '$sca_ps', 
                            '". $_POST['infotext'] . "');";
            
                    mysqli_query($conn, $addEvent);
                    echo "Your enrollment form has been submitted. Redirecting...";
                    header("Refresh:2; url=SCA-Home.php");
                    exit; 
                }
            ?>
</body>

</html>