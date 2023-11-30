<?php
session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require '../include/Connection.php';
    $conn = getDB();

    // Store the Faculty ID in a variable
    $faculty_id = $_SESSION["faculty_id"];
    $attendance_id = htmlentities($_GET['attendance_id']);

    // Retrieve student's attendance record
    $studAttQuery = "SELECT * FROM attendance 
    JOIN StudRec ON attendance.SR_ID = StudRec.SR_ID
    WHERE attendance.attendance_id=$attendance_id";
    $studAttResult = $conn->query($studAttQuery);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty - Student Management</title>
    <link rel="stylesheet" href="../css/faculty-page.css">
</head>

<body>
    <?php require 'include/Faculty-Header.php'; ?>

    <div class="container">
        <h2 class="edit-attendance-header">Edit Attendance</h2>

        <table class="student-table" border="1">
            <tr>
                <th>Month</th>
                <th>Present</th>
                <th>Absent</th>
                <th>School Days</th>
            </tr>
            <?php while ($StAttRow = $studAttResult->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $StAttRow['month_for_attendance']; ?></td>
                <td><?php echo $StAttRow['number_of_present']; ?></td>
                <td><?php echo $StAttRow['number_of_absence']; ?></td>
                <td><?php echo $StAttRow['number_of_scdays']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table><br>

        <form method="post">
            <div class="form-group">
                <label for="number_of_present">Number of Present: </label>
                <input type="number" name="number_of_present" id="number_of_present" min="0" max="31" required>
            </div>

            <div class="form-group">
                <label for="number_of_absence">Number of Absent: </label>
                <input type="number" name="number_of_absence" id="number_of_absence" min="0" max="31" required>
            </div>

            <div class="form-group">
                <label for="number_of_scdays">Number of School Days: </label>
                <input type="number" name="number_of_scdays" id="number_of_scdays" min="0" max="31" required>
            </div>

            <button class="btn btn-primary">Update</button>
        </form>
    </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_GET['attendance_id'])) {

        $AddAttendance = "UPDATE attendance 
        SET number_of_present='". $_POST['number_of_present']."',
        number_of_absence='". $_POST['number_of_absence']."',
        number_of_scdays='". $_POST['number_of_scdays']."'
        WHERE attendance_id = '$attendance_id'";
        mysqli_query($conn, $AddAttendance);
        echo "The attendance record has been updated. Redirecting...";
        echo "<script>window.location.href='Faculty-StudRecInfo.php';</script>";
        exit();
    } 
    else {
        echo "Can't update attendance record. Redirecting...";
        header("Refresh:2; url=Faculty-StudRecInfo.php");  
        exit();
    } 
}
?>