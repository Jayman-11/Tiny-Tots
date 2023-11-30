<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];

    $meetings_id=htmlentities($_GET['meetings_id']);

    $meetstatus= "Approved by Parent";

    // Update the meeting status
    $Approve = "UPDATE meetings SET meetstatus = '$meetstatus'
    WHERE meetings.meetings_id = '$meetings_id'";
    mysqli_query($conn, $Approve);
    echo "The meeting has been approved. Redirecting...";
    header("Refresh:2; url=../Parent-Meetings.php");  
    exit();
}

?>