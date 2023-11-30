<?php

session_start();
if (isset($_SESSION['wba_id']) && isset($_SESSION['wba_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    //Web Admin information will be gathered
    $wba_id = $_SESSION["wba_id"];

    $info_sql = "SELECT * FROM webadmins
                WHERE wba_id='".$wba_id."'";

    $info_results = mysqli_query($conn, $info_sql);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($info_results === false) {
        echo mysqli_error($conn);
    } else {
        $info= mysqli_fetch_all($info_results, MYSQLI_ASSOC);
    }

    // Retrieve users/parents information
    $userQuery = "SELECT * FROM users";
    $userResult = $conn->query($userQuery);

    // Retrieve preschool information
    $preschoolQuery = "SELECT * FROM preschool WHERE status ='Registered';";
    $preschoolResult = $conn->query($preschoolQuery);

    // Retrieve student records
    $studentQuery = "SELECT * FROM StudRec JOIN enroll 
    ON StudRec.en_id = enroll.en_id";
    $studentResult = $conn->query($studentQuery);

    // Retrieve student records
    $FacultyQuery = "SELECT * FROM faculty JOIN preschool
    ON faculty.ps_id = preschool.ps_id";
    $facultyResult = $conn->query($FacultyQuery);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Information</title>
    <link rel="stylesheet" href="../css/view-information-styles.css">
</head>

<body>
    <?php require 'include/WA-Header.php'; ?>

    <div class="user-section">
        <h2 class="section-title">Registered Users</h2>

        <h3 class="section-title">Users/Parents Information</h3>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Name of the User/Parent</th>
                    <th class="button-col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($userRow = $userResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $userRow['first_name']; ?> <?php echo $userRow['last_name']; ?></td>
                    <td>
                        <a href="WA-ViewDetUser.php?user_id=<?= $userRow["user_id"] ?>">
                            <button class="button">View Details</button>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3 class="section-title">Preschool Information</h3>
        <table class="info-table">
            <thead>
                <tr>
                    <th>School Name</th>
                    <th>Status</th>
                    <th class="button-col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($preschoolRow = $preschoolResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $preschoolRow['school_name']; ?></td>
                    <td><?php echo $preschoolRow['status']; ?></td>
                    <td>
                        <a href="WA-ViewDetPS.php?ps_id=<?= $preschoolRow["ps_id"] ?>">
                            <button class="button">View Details</button>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3 class="section-title">Student Records</h3>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th class="button-col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($studentRow = $studentResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $studentRow['stud_fname']; ?> <?php echo $studentRow['stud_lname']; ?></td>
                    <td>
                        <a href="WA-ViewDetSR.php?SR_ID=<?= $studentRow["SR_ID"] ?>">
                            <button class="button">View Details</button>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3 class="section-title">Faculty Records</h3>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Faculty Name</th>
                    <th class="button-col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($facultyRow = $facultyResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $facultyRow['f_fname']; ?> <?php echo $facultyRow['f_lname']; ?></td>
                    <td>
                        <a href="WA-ViewDetFac.php?faculty_id=<?= $facultyRow["faculty_id"] ?>">
                            <button class="button">View Details</button>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>