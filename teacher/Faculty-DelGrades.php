<?php

session_start();
require 'C:\xampp\htdocs\TinyTots\Connection.php';
$conn = getDB();

    // Check if the teacher is logged in
    if (!isset($_SESSION['faculty_id'])) {
        // Redirect to login page if not logged in
        header("Location: Faculty-Login.php");
        exit();
    }

    //Store the Faculty ID on a variable
    $faculty_id = $_SESSION["faculty_id"];
    $grades_id=htmlentities($_GET['grades_id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty - Grades Management</title>
    <?php require 'C:\xampp\htdocs\TinyTots\Faculty-Header.php'; ?>
</head>

<body>
    <h2>Delete Grade Record</h2>
</body>


<form method="post">
    <br>
    <p>Are you sure that you want to delete this grades?</p>
    <input type="submit" value="Remove">
    <a href="Faculty-StudRecInfo.php">Cancel</a>
</form>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_GET['grades_id'])) {

        $sql = "SELECT * FROM grades_per_subject WHERE grades_id='".$grades_id."' 
        LIMIT 1;";

    $results = mysqli_query($conn, $sql);

    if (mysqli_num_rows($results)==1) {
        $sql_2 = "DELETE FROM grades_per_subject WHERE grades_id='".$grades_id."'";
        mysqli_query($conn, $sql_2);
        echo "The grade record has been removed. Redirecting...";
        header("Refresh:2; url=Faculty-StudRecInfo.php");  
        exit();
    } else {
        echo "Can't remove grade record. Redirecting...";
        header("Refresh:2; url=Faculty-StudRecInfo.php");  
        exit();
        } 
        
    }
}
?>