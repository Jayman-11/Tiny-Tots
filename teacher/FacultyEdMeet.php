<?php
session_start();

if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require '../include/Connection.php';
    $conn = getDB();
    $faculty_id = $_SESSION["faculty_id"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $meetings_id = htmlentities($_GET['meetings_id']);

        $sql = "UPDATE meetings 
                SET meeting_date = '".$_POST['meeting_date']."',  
                    meeting_time = '".$_POST['meeting_time']."', 
                    meetstatus = '".$_POST['meetstatus']."'
                WHERE meetings.meetings_id = '".$meetings_id."'";

        mysqli_query($conn, $sql);

        echo "Your changes have been submitted. Redirecting...";
        echo "<script>window.location.href='Faculty-Meetings.php';</script>";
        exit();
    } else {
        $meetings_id = htmlentities($_GET['meetings_id']);

        $showMeetings = "SELECT * FROM meetings 
                        JOIN preschool ON meetings.ps_id = preschool.ps_id
                        JOIN users ON meetings.user_id = users.user_id
                        JOIN faculty ON meetings.faculty_id = faculty.faculty_id
                        WHERE meetings.meetings_id='".$meetings_id."'";

        $showMeetings_results = mysqli_query($conn, $showMeetings);

        if ($showMeetings_results === false) {
            echo mysqli_error($conn);
        } else {
            $showM = mysqli_fetch_all($showMeetings_results, MYSQLI_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Meeting Management</title>
    <?php require 'include/Faculty-Header.php'; ?>
    <link rel="stylesheet" href="../css/meetings-management.css">
</head>

<body>
    <div>
        <h2 class="page-title">Update Scheduled Meeting Information</h2>

        <form method="post" enctype="multipart/form-data" class="edit-form">
            <div>
                <label style="color:black" for="meeting_date">Meeting Date</label>
                <input type="date" name="meeting_date" id="meeting_date" placeholder="Select the Meeting Date" required>
            </div>
            <div>
                <label style="color:black" for="meeting_time">Meeting Time</label>
                <input type="time" name="meeting_time" id="meeting_time" placeholder="Select the Meeting Time" required>
            </div>
            <label style="color:black" for="meetstatus">Meeting Status:</label>
            <select name="meetstatus">
                <option value="Scheduled">Scheduled</option>
                <option value="Completed">Completed</option>
                <option value="Requested">Requested</option>
            </select>
            <button class="approve-button">Update</button>
            <a href="Faculty-Meetings.php" class="delete-button">Cancel</a>
    </div>
    </form>
    </div>
</body>

</html>