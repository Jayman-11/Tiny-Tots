<?php

session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $faculty_id = $_SESSION["faculty_id"];

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

<html>
<title>Faculty Meeting Management</title>
<?php require 'include/Faculty-Header.php'; ?>
<link rel="stylesheet" href="../css/meetings-management.css">

<body>
    <div class="container">
        <h2 class="page-title">Delete Scheduled Meeting</h2>
        <form method="post" enctype="multipart/form-data" class="edit-form">
            <p style="color:black">Are you sure that you want to delete this scheduled meeting?</p>

            <button class="approve-button">Delete</button>
            <a href="Faculty-Meetings.php" class="delete-button">Cancel</a>
        </form>
    </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_GET['meetings_id'])) {

        $sql = "SELECT * FROM meetings WHERE meetings_id='".$meetings_id."' LIMIT 1;";

    $results = mysqli_query($conn, $sql);

    if (mysqli_num_rows($results)==1) {
        $sql_2 = "DELETE FROM meetings WHERE meetings_id='".$meetings_id."'";
        mysqli_query($conn, $sql_2);
        echo "The scheduled meeting has been removed. Redirecting...";
        echo "<script>window.location.href='Faculty-Meetings.php';</script>";
        exit();
    } else {
        echo "Can't remove scheduled meetings. Redirecting...";
        header("Refresh:2; url=Faculty-Meetings.php");  
        exit();
        } 
        
    }
}
?>