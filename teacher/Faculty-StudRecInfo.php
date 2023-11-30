<?php
session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require '../include/Connection.php';
    $conn = getDB();

    // Store the Faculty ID in a variable
    $faculty_id = $_SESSION["faculty_id"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty - Student Management</title>
    <link rel="stylesheet" href="../css/faculty-page.css"> <!-- Add your custom stylesheet link here -->
</head>

<body>

    <?php require 'include/Faculty-Header.php'; ?>
    <div class="container">
        <?php
// Check if the faculty is an adviser
$sql = "SELECT * FROM adviser
    JOIN faculty ON adviser.faculty_id = faculty.faculty_id
    WHERE adviser.faculty_id='" . $faculty_id . "' LIMIT 1";
$query = mysqli_query($conn, $sql);
$results = mysqli_fetch_assoc($query);
?>

        <?php if (empty($results)): ?>
        <div class="no-advisory-class">
            <h2>No Advisory Class</h2>
        </div>
        <?php else:
    $fac_id = $results['faculty_id'];
    $section = $results['section'];
    ?>
        <div class="advisory-class">
            <h2>Students of Section <?php echo $section ?></h2>
            <button onclick="window.print()" class="btn btn-primary">Print Student List Record</button>

            <?php
        $showRec = "SELECT * from StudRec
        JOIN preschool ON StudRec.ps_id = preschool.ps_id
        JOIN users on StudRec.user_id = users.user_id
        JOIN adviser on StudRec.adviser_ID = adviser.adviser_ID
        JOIN enroll on StudRec.en_id = enroll.en_id
        WHERE StudRec.adviser_ID='" . $fac_id . "'";

        $SRecResult = $conn->query($showRec);
        ?>

            <table class="student-table" border="1">
                <tr>
                    <th>Student Name</th>
                    <th>Action</th>
                </tr>
                <?php while ($SRRRow = $SRecResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $SRRRow['stud_fname']; ?> <?php echo $SRRRow['stud_lname']; ?></td>
                    <td>
                        <a href="Faculty-AttMan.php?SR_ID=<?= $SRRRow["SR_ID"] ?>"><button
                                class="action-btn">Attendance</button></a>
                        <a href="Faculty-GraMan.php?SR_ID=<?= $SRRRow["SR_ID"] ?>"><button
                                class="action-btn">Grades</button></a>
                        <!-- <a href="Faculty-AddFinalVal.php?SR_ID=<?= $SRRRow["SR_ID"] ?>">
                            <button class="action-btn">Add Final Value</button></a> -->
                    </td>
                    </td>
                </tr>
                <?php endwhile; ?>
        </div>
        <?php endif; ?>

        <!-- <?php
$SPSQuery = "SELECT *
        FROM stud_per_subject
        JOIN StudRec ON stud_per_subject.SR_ID = StudRec.SR_ID
        JOIN subjects on stud_per_subject.subject_id = subjects.subject_id 
        JOIN faculty on stud_per_subject.faculty_id = faculty.faculty_id
        WHERE stud_per_subject.faculty_id='$faculty_id';";

$SPSResult = $conn->query($SPSQuery);
?>

        <div class="enrolled-students">
            <table class="student-table" border="1">
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Action</th>
                </tr>
                <?php while ($SPSRow = $SPSResult->fetch_assoc()) : ?>
                <?php
            $sql = "SELECT * FROM StudRec
            JOIN enroll ON StudRec.en_id = enroll.en_id
            WHERE StudRec.SR_ID='" . $faculty_id . "' LIMIT 1";
            $query = mysqli_query($conn, $sql);
            $results = mysqli_fetch_assoc($query);
            $studfname = $results['stud_fname'];
            $studlname = $results['stud_lname'];
            ?>
                <tr>
                    <td><?php echo $studfname; ?> <?php echo $studlname; ?></td>
                    <td><?php echo $SPSRow['subjects']; ?></td>
                    <td><a href="Faculty-GraMan.php?SR_ID=<?= $SPSRow["SR_ID"] ?>"><button
                                class="action-btn">Grades</button></a></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div> -->
    </div>
</body>

</html>