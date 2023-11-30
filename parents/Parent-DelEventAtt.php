<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{  
    require '../include/Connection.php';
    $conn = getDB();

    $user_id = $_SESSION["user_id"];
    $e_id=htmlentities($_GET['event_id']);
    

    if (isset($_GET["event_id"])) {

        $sql = "DELETE FROM eattend WHERE event_id = '$e_id' AND user_id = '$user_id';";
        mysqli_query($conn, $sql);
        echo "Your attendance has been removed for this event. Redirecting...";
        echo "<script >window.location.href='Parent-Home.php';</script >";
        // header("Refresh:2; url=Parent-Home.php");  
        exit();
}}

?>