<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{  
    require '../include/Connection.php';
    $conn = getDB();

    $user_id = $_SESSION["user_id"];
    $e_id=htmlentities($_GET['event_id']);
    

    if (isset($_GET["event_id"])) {

        $sql = "INSERT INTO eattend (event_id, user_id) 
        VALUES (?, ?);";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            echo mysqli_error($conn);} 
        else {
            mysqli_stmt_bind_param(
                $stmt,
                "si",
                $e_id,
                $user_id
            );
                if (mysqli_stmt_execute($stmt)) {
                    echo "Your attendance has been added to this event. Redirecting..";
                    echo "<script >window.location.href='Parent-Home.php';</script >";
                    // header("Refresh:2; url=Parent-Home.php");  
                    exit;
                }
        }
    }
}

?>