<?php
session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require '../include/Connection.php';
    $conn = getDB();

    // Store the Faculty ID in a variable
    $faculty_id = $_SESSION["faculty_id"];

    // Retrieve the Student Record Add that will be used when inserting 
    // the new data of the attendance on the database
    $SR_Id = htmlentities($_GET['SR_ID']);

    // Retrieve student records
    $studentQuery = "SELECT * FROM StudRec JOIN enroll 
    ON StudRec.en_id = enroll.en_id 
    WHERE SR_ID = $SR_Id";
    $studentResult = $conn->query($studentQuery);
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
        <h2 class="add-attendance-header">Add Attendance</h2>

        <?php while ($StudRRow = $studentResult->fetch_assoc()) : ?>
        <p class="student-name">
            Student Name: <?php echo $StudRRow['stud_fname']; ?> <?php echo $StudRRow['stud_lname']; ?>
        </p>
        <?php endwhile; ?> <br>

        <form method="post">
            <label for="month_for_attendance">Month:</label>
            <select name="month_for_attendance">
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>

            <div class="form-group">
                <label for="number_of_present">Number of Present:</label>
                <input type="number" name="number_of_present" id="number_of_present" min="0" max="31" required>
            </div>

            <div class="form-group">
                <label for="number_of_absence">Number of Absent:</label>
                <input type="number" name="number_of_absence" id="number_of_absence" min="0" max="31" required>
            </div>

            <div class="form-group">
                <label for="number_of_scdays">Number of School Days:</label>
                <input type="number" name="number_of_scdays" id="number_of_scdays" min="0" max="31" required>
            </div>

            <button class="btn btn-primary">Add</button>
        </form>
        
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
                if (isset($_GET['SR_ID'])) {
            
                    $sql = "SELECT * FROM adviser
                    JOIN faculty ON adviser.faculty_id = adviser_ID
                    WHERE adviser.faculty_id='".$faculty_id."' LIMIT 1";
                    $query = mysqli_query($conn, $sql);
                    $results = mysqli_fetch_assoc($query);
                    $adviser_ID = $results['adviser_ID'];
            
                    $results = mysqli_query($conn, $sql);
            
                    if (mysqli_num_rows($results) == 1) {
                        $AddAttendance = "INSERT INTO attendance (month_for_attendance, 
                        number_of_present, number_of_absence, number_of_scdays, 
                        adviser_ID, SR_ID)
                        VALUES ('" . $_POST['month_for_attendance'] . "',
                                '" . $_POST['number_of_present'] . "',
                                '" . $_POST['number_of_absence'] . "',
                                '" . $_POST['number_of_scdays'] . "',
                                '$adviser_ID',
                                '$SR_Id'
                            )";
                        mysqli_query($conn, $AddAttendance);
                        echo "The attendance record has been added. Redirecting...";
                        echo "<script>window.location.href='Faculty-StudRecInfo.php';</script>";
                        exit();
                    } else {
                        echo "Can't add attendance record. Redirecting...";
                        header("Refresh:2; url=Faculty-StudRecInfo.php");
                        exit();
                    }
                }
            }
        ?>
    </div>

</body>

</html>

