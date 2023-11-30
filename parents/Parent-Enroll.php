<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];

    // Enroll Table
    $showEnroll = "SELECT * from enroll 
    JOIN preschool ON enroll.ps_id = preschool.ps_id
    JOIN users on enroll.user_id = users.user_id
    WHERE enroll.user_id='".$user_id."'";
    $showEnroll_results = mysqli_query($conn, $showEnroll);

    // If there is a connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showEnroll_results === false) {
        echo mysqli_error($conn);
    } else {
        $showE = mysqli_fetch_all($showEnroll_results, MYSQLI_ASSOC);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Parent Enrollment Management</title>
    <?php require 'include/Parent-Header.php'; ?>
    <link rel="stylesheet" href="../css/meetings-management.css">
    <style>
    .edit-form {
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .edit-form div {
        margin-bottom: 15px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #2a9d8f;
    }

    input,
    select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .info-table th,
    .info-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }

    .info-table th {
        background-color: #2a9d8f;
        color: #ffffff;
    }

    .info-table thead th {
        background-color: #264653;
        color: #ffffff;
    }

    .info-table tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .info-table th:last-child,
    .info-table td:last-child {
        border-right: none;
    }

    .profile-picture {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        /* Adjust the margin as needed */
        border-radius: 50%;
        overflow: hidden;
        /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
    }

    .profile-picture img {
        width: 200px;
        /* Adjust the size as needed */
        height: 200px;
        /* Adjust the size as needed */
        object-fit: cover;
        border-radius: 50%;
    }
    </style>
</head>

<body>
    <div class="container">
        <a href="Parent-EnrollForm.php"><button>Process an Enrollment</button></a>
        <?php if (empty($showE)): ?>
        <h2>No Enrollment Found</h2>

        <?php else: ?>
        <h2>List of Enrollment Submitted</h2>
        <?php foreach ($showE as $enroll): ?>
        <table class="info-table">
            <tr>
                <th>Attribute</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Enrollment ID</td>
                <td><?= $enroll["enrollID"] ?></td>
            </tr>
            <tr>
                <td>Student Name</td>
                <td><?= $enroll["stud_fname"] ?> <?= $enroll["stud_lname"] ?></td>
            </tr>
            <tr>

                <td>School Name</td>
                <td><?= $enroll["school_name"] ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?= $enroll["en_status"] ?></td>
            </tr>
            <tr>
                <td>Enrollment Date</td>
                <td><?= $enroll["enrollment_date"] ?></td>
            </tr>
            <tr>
                <td>Details</td>
                <td><?php if (empty($enroll["details"])): ?> No Details found
                    <?php else: $enroll["details"]?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <td>Action</td>
                <td><a href="Parent-DetEnroll.php?en_id=<?= $enroll["en_id"] ?>">
                        <button>View Enrollment</button></a>
                    <a href="Parent-EdPSA.php?en_id=<?= $enroll["en_id"] ?>">
                        <button>Update Enrollment</button></a>
                    <a href="Parent-DelEnroll.php?en_id=<?= $enroll["en_id"] ?>">
                        <button>Withdraw Enrollment</button></a>
                </td>
            </tr>
        </table>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>


</html>