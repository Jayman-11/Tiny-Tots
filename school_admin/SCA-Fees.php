<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();
    // Events Table

    $showFees = "SELECT * from payment 
                join preschool on payment.ps_id = preschool.ps_id
                join users on payment.user_id = users.user_id
                join enroll on payment.en_id = enroll.en_id
                WHERE payment.ps_id = '".$sca_id."'";

    $showFees_results = mysqli_query($conn, $showFees);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showFees_results === false) {
        echo mysqli_error($conn);
    } else {
        $showFS = mysqli_fetch_all($showFees_results, MYSQLI_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Fees Management</title>
    <?php require 'include/SCA-Header.php'; ?>
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
        <?php if (empty($showFS)): ?>
        <h2>No Payment Information</h2>

        <?php else: ?>
        <h2>Payments Received</h2>
        <button onclick="window.print()" class="btn btn-primary">Print Payment List</button>

        <?php foreach ($showFS as $fee): ?>

        <table class="info-table">
            <tr>
                <th>Attribute</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Enrollment ID</td>
                <td><?= $fee["enrollID"] ?></td>
            </tr>
            <tr>
                <td>Student Name</td>
                <td><?= $fee["stud_fname"] ?> <?= $fee["stud_lname"] ?></td>
            </tr>
            <tr>
                <td>School Name</td>
                <td><?= $fee["school_name"] ?></td>
            </tr>
            <tr>
                <td>Paid By</td>
                <td> <?= $fee["first_name"] ?> <?= $fee["last_name"] ?></td>
            </tr>
            <tr>
                <td>Semester/School Year</td>
                <td><?= $fee["sy"] ?></td>
            </tr>
            <tr>
                <td>Year Level</td>
                <td><?= $fee["year_level"] ?></td>
            </tr>
            <tr>
                <td>Payment for</td>
                <td><?= $fee["payment_for"] ?></td>
            </tr>
            <tr>
                <td>Form of Payment</td>
                <td><?= $fee["FOP"] ?></td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td><?= $fee["total"] ?></td>
            </tr>
            <tr>
                <td>Amount Paid</td>
                <td><?= $fee["amount_paid"] ?></td>
            </tr>
            <tr>
                <td>Paid By</td>
                <td><?= $fee["first_name"] ?> <?= $fee["last_name"] ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?= $fee["payment_status"] ?> <a href="SCA-EDFee.php?payment_id=<?= $fee[
                        "payment_id"
                        ] ?>"><button>Edit Status</button></a></td>
            </tr>
            <tr>
                <td>Billing Due Date</td>
                <td><?php if (empty($fee["deadline"])): ?> No Deadline Date
                    <?php else: echo $fee["deadline"]?>
                    <?php endif; ?></td>
            </tr>
            <tr>
                <?php 
                        $sql = "SELECT p_proof FROM payment 
                        WHERE payment_id='".$fee["payment_id"]."'";
                        $res = mysqli_query($conn,  $sql);

                        if (mysqli_num_rows($res) > 0) {
                            while ($payment = mysqli_fetch_assoc($res)) {  ?>
                <td>Transaction Photo</td>

                <td>
                    <div class="profile-picture">
                        <?php if (empty($fee["p_proof"])): ?> No Transaction Photo
                        <?php else: $fee["p_proof"]?>
                        <img src="../uploaded-image/<?=$payment['p_proof']?>" alt="Profile Picture">
                        <?php endif; ?>
                    </div>
                </td>
                <?php } }?>
            </tr>
            <tr>
                <td>Action</td>
                <td><a href="SCA-DelFee.php?payment_id=<?= $fee[
                                    "payment_id"
                                ] ?>"><button>Delete Payment Form</button></a>
                </td>
            </tr>
    </div>
    </li>
    <?php endforeach; ?>
    </ul>
    <?php endif; ?>

</html>