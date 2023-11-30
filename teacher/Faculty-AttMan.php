<?php
session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require '../include/Connection.php';
    $conn = getDB();

    // Store the Faculty ID in a variable
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
    <link rel="stylesheet" href="../css/faculty-page.css">
</head>

<body>
    <?php require 'include/Faculty-Header.php'; ?>

    <div class="container">
        <h3 class="attendance-header">Attendance Management</h3>
        <button onclick="window.print()" class="btn btn-primary">Print Attendance</button><br>

        <?php while ($StudRRow = $studentResult->fetch_assoc()) : ?>
        <p class="student-name">
            Student Name: <?php echo $StudRRow['stud_fname']; ?> <?php echo $StudRRow['stud_lname']; ?>
        </p>
        <?php endwhile; ?><br>

        <a href="Faculty-AddAtt.php?SR_ID=<?= $SR_Id ?>" class="btn btn-success">Add Attendance</a>

        <table class="student-table" border="1">
            <tr>
                <th>Month</th>
                <th>Present</th>
                <th>Absent</th>
                <th>School Days</th>
                <th>Action</th>
            </tr>
            <?php while ($StAttRow = $studAttResult->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $StAttRow['month_for_attendance']; ?></td>
                <td><?php echo $StAttRow['number_of_present']; ?></td>
                <td><?php echo $StAttRow['number_of_absence']; ?></td>
                <td><?php echo $StAttRow['number_of_scdays']; ?></td>
                <td>
                    <a href="Faculty-EdAtt.php?attendance_id=<?= $StAttRow["attendance_id"] ?>"
                        class="btn btn-warning">Edit</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- <td>
            <a href="Faculty-DelAtt.php?SR_ID=<?= $SR_Id ?>" class="btn btn-danger">Delete Attendance</a>
        </td> -->
    </div>

</body>

</html>