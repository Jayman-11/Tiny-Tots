<?php

session_start();
require '../include/Connection.php';
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
    <link rel="stylesheet" href="../css/faculty-page.css">
    <?php require 'include/Faculty-Header.php'; ?>
</head>

<body>

    <div class="container">
        <h2>Manage Grades</h2>

        <?php
if (isset($successMessage)) {
echo "<p style='color: green;'>$successMessage</p>";
}

if (isset($errorMessage)) {
echo "<p style='color: red;'>$errorMessage</p>";
}
?>

        <form method="post">
            <div class="form-group">
                <label for="periodic_grading">Grading:</label>
                <select name="periodic_grading">
                    <option value="f_grading">First Grading</option>
                    <option value="s_grading">Second Grading</option>
                    <option value="t_grading">Third Grading</option>
                    <option value="fourth_grading">Fourth Grading</option>
                </select>
            </div>

            <div class="form-group">
                <label for="grade">Grade:</label>
                <input type="number" name="grade" required>
            </div>

            <input type="submit" value="Add Grade">
        </form>
    </div>
</body>

</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_GET['grades_id'])) {

        $grading = $_POST['periodic_grading'];
        $gradeValue = $_POST['grade'];
    
        $AddGradePSub = "UPDATE grades_per_subject SET $grading = $gradeValue
        WHERE grades_id = '$grades_id';";
        mysqli_query($conn, $AddGradePSub);
        echo "The grade has been added. Redirecting...";
        echo "<script>window.location.href='Faculty-StudRecInfo.php';</script>";
        exit();
    } else {
        echo "Can't add grade record. Redirecting...";
        header("Refresh:2; url=Faculty-StudRecInfo.php");  
        exit();
        } 

}

?>