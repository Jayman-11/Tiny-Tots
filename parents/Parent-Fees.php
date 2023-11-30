<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    $user_id = $_SESSION["user_id"];

    // Enroll Table
    $showFee = "SELECT * from payment 
    JOIN preschool ON payment.ps_id = preschool.ps_id
    JOIN users on payment.user_id = users.user_id
    JOIN enroll on payment.en_id = enroll.en_id
    WHERE payment.user_id='".$user_id."'";
    $showFee_results = mysqli_query($conn, $showFee);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showFee_results === false) {
        echo mysqli_error($conn);
    } else {
        $showF = mysqli_fetch_all($showFee_results, MYSQLI_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Parent - Fees Management</title>
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
        text-align: center;
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
        text-align: center;
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

    .button1 {
        background-color: red;
        border-radius: 4px;
        border: none;
        color: white;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        cursor: pointer;
    }

    .button2 {
        background-color: green;
        border-radius: 4px;
        border: none;
        color: white;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        cursor: pointer;
    }

    .pos {
        text-align: center;
    }

    .buttons.green {
        background-color: #4caf50;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        margin-bottom: 5px;
    }
    </style>
</head>


<body>
    <div class="container">
        <a class="pos" href="Parent-FeeProcess.php"><button class="buttons green">Process New Payment</button></a>

        <?php foreach ($showF as $fee): ?>
        <div class="pos">
            <table class="info-table">
                <tr>
                    <th>Preschool Name</th>
                    <th>Date</th>
                    <th>Payment For</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>

                <tr>
                    <td><?= $fee["school_name"] ?></td>
                    <td><?php if (empty($fee["deadline"])) : ?> No Due Date Yet
                        <?php else : echo $fee["deadline"] ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $fee["payment_for"] ?></td>
                    <td><?= $fee["payment_status"] ?></td>
                    <td><a href="Parent-DetFee.php?payment_id=<?= $fee[
                                "payment_id"
                            ] ?>"><button class="buttons green">View Detailed Information</button></a>
                        <a href="Parent-EdFee.php?payment_id=<?= $fee[
                                "payment_id"
                            ] ?>"><button class="buttons green">Edit Transaction Photo</button></a>
                        <a href="Parent-DelFee.php?payment_id=<?= $fee[
                                "payment_id"
                            ] ?>"><button class="buttons green">Delete Payment Form</button></a>
                    </td>
                </tr>
        </div>
        <?php endforeach; ?>
</body>

</html>