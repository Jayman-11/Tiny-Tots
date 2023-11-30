<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];
}
?>
<html lang="en">

<head>
    <title>Set New Meeting</title>
    <?php require 'include/Parent-Header.php'; ?>
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
        <form method="POST" class="edit-form">
            <h2 style="text-align: center;">Schedule New Meeting</h2>
            <div>
                <label for="meet_topic">Topic: </label>
                <input type="text" name="meet_topic" id="meet_topic" placeholder="Enter the topic for the meeting"
                    required>
            </div>

            <div>
                <label for="meet_desc">Description: </label>
                <input type="text" name="meet_desc" id="meet_desc" placeholder="Enter the descrption for the meeting"
                    required>
            </div>

            <label> Preschool Name: <label>
                    <select name="ps_id">
                        <option value="1">Children of Mary Academy of Dasmariñas Cavite</option>
                        <option value="2">Raises Montessori Academe</option>
                        <option value="3">Luke 19:4 Child Development Center Incorporated</option>
                    </select>
                    <!-- Name of the faculty members -->
                    <div>
                        <label for="faculty_fname">Faculty First Name: </label>
                        <input type="text" name="faculty_fname" id="faculty_fname"
                            placeholder="Enter the faculty first name">
                    </div>

                    <div>
                        <label for="faculty_lname">Faculty Last Name: </label>
                        <input type="text" name="faculty_lname" id="faculty_lname"
                            placeholder="Enter the faculty last name">
                    </div>

                    <div>
                        <label for="meeting_date">Meeting Date</label>
                        <input type="date" name="meeting_date" id="meeting_date" placeholder="Select the Meeting Date"
                            required>
                    </div>

                    <div>
                        <label for="meeting_time">Meeting Time</label>
                        <input type="time" name="meeting_time" id="meeting_time" placeholder="Select the Meeting Time"
                            required>
                    </div>

                    <label>Meeting Setup: <label>
                            <select name="setup">
                                <option value="Online">Online</option>
                                <option value="Personal/Face to Face">Personal/Face to Face</option>
                            </select>
                            <button>Schedule Meeting</button>
        </form>
    </div>

</html>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $facFName = $_POST['faculty_fname']; 
    $facLName = $_POST['faculty_lname']; 
    $ps_id = $_POST['ps_id']; 
    $meetstatus = "Requested by Parent"; 

    $sql = "SELECT * FROM faculty WHERE f_fname='".$facFName."' 
        AND f_lname='".$facLName."' AND ps_id = '".$ps_id."' LIMIT 1;";

    $re = mysqli_query($conn, $sql);
    if (mysqli_num_rows($re) === 1) {
    $row = mysqli_fetch_assoc($re);

    $sql = "INSERT INTO meetings (user_id, ps_id, meet_topic, meet_desc, faculty_id, 
    meeting_date, meeting_time, setup, meetstatus)
        VALUES ('$user_id', '". $_POST['ps_id'] . "', '". $_POST['meet_topic'] . "',
                '". $_POST['meet_desc'] . "',
                '".$row['faculty_id']."',
                '". $_POST['meeting_date'] . "',
                '". $_POST['meeting_time'] . "',
                '". $_POST['setup'] . "', '$meetstatus')";
                mysqli_query($conn, $sql);
				    echo "Your meeting request has been submitted. Redirecting...";
                    echo "<script>window.location.href='Parent-Meetings.php';</script>";
    } else {
        echo "No faculty member found for the selected preschool.";
    } 
    }         
?>