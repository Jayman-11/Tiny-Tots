<?php
session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require '../include/Connection.php';
    $conn = getDB();
    $faculty_id = $_SESSION["faculty_id"];

    //Get the ps_id from the faculty information table
    $sql = "SELECT * FROM faculty WHERE faculty_id='" . $faculty_id . "'";
    $query = mysqli_query($conn, $sql);
    $results = mysqli_fetch_assoc($query);
    $fac_ps = $results['ps_id'];

    //Meetings Table
    $showMeetings = "SELECT * from meetings 
    JOIN preschool ON meetings.ps_id = preschool.ps_id
    JOIN users on meetings.user_id = users.user_id
    JOIN faculty on meetings.faculty_id = faculty.faculty_id
    WHERE meetings.faculty_id='" . $faculty_id . "'";
    $showMeetings_results = mysqli_query($conn, $showMeetings);

    // If there is a connection error, then echo the description of the error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showMeetings_results === false) {
        echo mysqli_error($conn);
    } else {
        $showM = mysqli_fetch_all($showMeetings_results, MYSQLI_ASSOC);
    }
}
?>

<html lang="en">

<head>
    <title>Set New Meeting</title>
    <?php require 'include/Faculty-Header.php'; ?>
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
        <form method="POST" class="edit-form">
            <div>
                <label for="meet_topic">Topic:</label>
                <input type="text" name="meet_topic" id="meet_topic" placeholder="Enter the topic for the meeting"
                    required>
            </div>

            <div>
                <label for="meet_desc">Description:</label>
                <input type="text" name="meet_desc" id="meet_desc" placeholder="Enter the description for the meeting"
                    required>
            </div>

            <div>
                <label for="user_id">Parent Name:</label>
                <select name="user_id">
                    <?php

                    // Get all the list of the adviser that is registered on the preschool
                    $sql1 = "SELECT * FROM StudRec 
                JOIN users ON StudRec.user_id = users.user_id
                JOIN adviser ON StudRec.adviser_ID = adviser.adviser_ID
                WHERE adviser.faculty_id='" . $faculty_id . "' GROUP by users.user_id;";

                    $sql1_results = mysqli_query($conn, $sql1);

                    // If there is an connection error, then echo the description of the  error
                    // Else, store the results on a variable using mysqli_fetch_all
                    if ($sql1_results === false) {
                        echo mysqli_error($conn);
                    } else {
                        $showFL = mysqli_fetch_all($sql1_results, MYSQLI_ASSOC);
                    }

                    foreach ($showFL as $parent) : $p_id = $parent["user_id"] ?>
                    <option value="<?php $p_id ?>"><?php echo $parent["first_name"] ?>
                        <?php echo $parent["last_name"] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="meeting_date">Meeting Date:</label>
                <input type="date" name="meeting_date" id="meeting_date" placeholder="Select the Meeting Date" required>
            </div>

            <div>
                <label for="meeting_time">Meeting Time:</label>
                <input type="time" name="meeting_time" id="meeting_time" placeholder="Select the Meeting Time" required>
            </div>

            <div>
                <label for="setup">Meeting Setup:</label>
                <select name="setup">
                    <option value="Online">Online</option>
                    <option value="Personal/Face to Face">Personal/Face to Face</option>
                </select>
            </div>

            <button class="submit-button">Schedule Meeting</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $meetstatus = "Requested by Faculty";

        $sql = "INSERT INTO meetings (user_id, faculty_id, ps_id, meet_topic, meet_desc,
        meeting_date, meeting_time, setup, meetstatus)
            VALUES ('$p_id', '$faculty_id', '$fac_ps', '" . $_POST['meet_topic'] . "',
                    '" . $_POST['meet_desc'] . "',
                    '" . $_POST['meeting_date'] . "',
                    '" . $_POST['meeting_time'] . "',
                    '" . $_POST['setup'] . "', '$meetstatus')";
        mysqli_query($conn, $sql);
        echo "Your meeting request has been submitted. Redirecting...";
        echo "<script>window.location.href='Faculty-Meetings.php';</script>";
    }
    ?>

</body>

</html>