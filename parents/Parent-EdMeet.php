<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];

    $meetings_id=htmlentities($_GET['meetings_id']);

    //Meetings Table
    $showMeetings = "SELECT * from meetings 
    JOIN preschool ON meetings.ps_id = preschool.ps_id
    JOIN users on meetings.user_id = users.user_id
    JOIN faculty on meetings.faculty_id = faculty.faculty_id
    WHERE meetings.meetings_id='".$meetings_id."'";
    $showMeetings_results = mysqli_query($conn, $showMeetings);

    // If there is a connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showMeetings_results === false) {
        echo mysqli_error($conn);
    } else {
        $showM = mysqli_fetch_all($showMeetings_results, MYSQLI_ASSOC);
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Meeting Management</title>
    <?php require 'include/Parent-Header.php'; ?>
    <link rel="stylesheet" href="../css/meetings-management.css">
</head>


<body>
    <div class="container">
        <h2 class="page-title">Update Scheduled Meeting Information</h2>

        <form method="post" enctype="multipart/form-data" class="edit-form">
            <div>
                <label for="meeting_date">Meeting Date</label>
                <input type="date" name="meeting_date" id="meeting_date" placeholder="Select the Meeting Date" required>
            </div>

            <div>
                <label for="meeting_time">Meeting Time</label>
                <input type="time" name="meeting_time" id="meeting_time" placeholder="Select the Meeting Time" required>
            </div>

            <button class="approve-button">Update</button>
            <a href="Parent-Meetings.php" class="delete-button">Cancel</a>
        </form>
        <?php 
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $sql = "UPDATE meetings SET meeting_date = '". $_POST['meeting_date'] ."',  
                    meeting_time = '". $_POST['meeting_time'] ."', meetstatus = 'Requested by Parent'
                    WHERE meetings.meetings_id = '".$meetings_id."'";

                    mysqli_query($conn, $sql);

                    echo "Your changes has been submitted. Redirecting...";
                    echo "<script>window.location.href='Parent-Meetings.php';</script>";
                    exit();
                }
            ?>
    </div>
</body>

</html>