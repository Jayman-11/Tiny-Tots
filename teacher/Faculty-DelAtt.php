<?php
session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require 'C:\xampp\htdocs\TinyTots\Connection.php';
    $conn = getDB();

    // Store the Faculty ID on a variable
    $faculty_id = $_SESSION["faculty_id"];
    $SR_Id = htmlentities($_GET['SR_ID']);

    // Retrieve student records
    $studentQuery = "SELECT * FROM StudRec JOIN enroll 
    ON StudRec.en_id = enroll.en_id 
    WHERE SR_ID = $SR_Id";
    $studentResult = $conn->query($studentQuery);

    // Retrieve student's attendance record
    $studAttQuery = "SELECT * FROM attendance 
    JOIN StudRec ON attendance.SR_ID = StudRec.SR_ID
    WHERE attendance.SR_ID=$SR_Id";
    $studAttResult = $conn->query($studAttQuery);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty - Student Management</title>
    <link rel="stylesheet" href="css/faculty-page.css">
</head>

<body>
    <?php require 'C:\xampp\htdocs\TinyTots\Faculty-Header.php'; ?>

    <div class="container">
        <h2 class="delete-attendance-header">Delete Attendance</h2>

        <table class="student-table">
            <tr>
                <th>Month</th>
                <th>Present</th>
                <th>Absent</th>
                <th>School Days</th>
                <!-- Title for the columns -->
            </tr>
            <?php while ($StAttRow = $studAttResult->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $StAttRow['month_for_attendance']; ?></td>
                <td><?php echo $StAttRow['number_of_present']; ?></td>
                <td><?php echo $StAttRow['number_of_absence']; ?></td>
                <td><?php echo $StAttRow['number_of_scdays']; ?></td>
                <!-- Details/Each row in the column -->
            </tr>
            <?php endwhile; ?>
        </table>

        <form method="post" enctype="multipart/form-data">
            <p class="confirmation-message">Are you sure that you want to delete this attendance record?</p>
            <button class="btn btn-danger">Delete</button>
            <a href="Faculty-StudRecInfo.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_GET['SR_ID'])) {

        $sql = "SELECT * FROM attendance 
        JOIN StudRec ON attendance.SR_ID = StudRec.SR_ID
        WHERE attendance.SR_ID='" . $SR_Id . "' 
        LIMIT 1;";

        $results = mysqli_query($conn, $sql);

        if (mysqli_num_rows($results) == 1) {
            $sql_2 = "DELETE FROM attendance 
            WHERE attendance.SR_ID='" . $SR_Id . "';";
            mysqli_query($conn, $sql_2);
            echo "The attendance record has been removed. Redirecting...";
            header("Refresh:2; url=Faculty-StudRecInfo.php");
            exit();
        } else {
            echo "Can't remove attendance record. Redirecting...";
            header("Refresh:2; url=Faculty-StudRecInfo.php");
            exit();
        }
    }
}
?>