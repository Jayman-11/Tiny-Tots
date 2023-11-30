<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    // Events Table
    $payment_id=htmlentities($_GET['payment_id']);

    if (isset($_GET["payment_id"])) {

        $showDF = "SELECT * from payment 
        join users on payment.user_id = users.user_id 
        join enroll on payment.en_id = enroll.en_id
        join preschool on payment.ps_id = preschool.ps_id
        WHERE payment.payment_id='".$payment_id."';";

        $showDF_results = mysqli_query($conn, $showDF);

        // If there is an connection error, then echo the description of the  error
        // Else, store the results on a variable using mysqli_fetch_all
        if ($showDF_results === false) {
            echo mysqli_error($conn);
        } else {
            $showDF = mysqli_fetch_all($showDF_results, MYSQLI_ASSOC);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Fees - Billing Summary</title>
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
        <button onclick="window.print()" class="btn btn-primary">Print Payment Form</button>

        <?php foreach ($showDF as $DF): ?>
        <table class="info-table">
            <tr>
                <th>Attribute</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Enrollment ID</td>
                <td><?= $DF["enrollID"] ?></td>
            </tr>
            <tr>
                <td>Student Name</td>
                <td><?= $DF["stud_fname"] ?> <?= $DF["stud_lname"] ?></td>
            </tr>
            <tr>
                <td>School Name</td>
                <td><?= $DF["school_name"] ?></td>
            </tr>
            <tr>
                <td>Paid By</td>
                <td> <?= $DF["first_name"] ?> <?= $DF["last_name"] ?></td>
            </tr>
            <tr>
                <td>Semester/School Year</td>
                <td><?= $DF["sy"] ?></td>
            </tr>
            <tr>
                <td>Year Level</td>
                <td><?= $DF["year_level"] ?></td>
            </tr>
            <tr>
                <td>Payment for</td>
                <td><?= $DF["payment_for"] ?></td>
            </tr>
            <tr>
                <td>Form of Payment</td>
                <td><?= $DF["FOP"] ?></td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td><?= $DF["total"] ?></td>
            </tr>
            <tr>
                <td>Amount Paid</td>
                <td><?= $DF["amount_paid"] ?></td>
            </tr>
            <tr>
                <td>Paid By</td>
                <td><?= $DF["first_name"] ?> <?= $DF["last_name"] ?></td>
            </tr>
            <tr>
                <td>Billing Due Date</td>
                <td><?php if (empty($DF["deadline"])): ?> No Deadline Date
                    <?php else: echo $DF["deadline"]?>
                    <?php endif; ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?= $DF["payment_status"] ?></td>
            </tr>
            <tr>
                <?php 
                            $stud = $_SESSION["user_id"];

                            $sql = "SELECT * FROM users WHERE user_id='".$stud."'";
                            $query = mysqli_query($conn, $sql);
                            $results = mysqli_fetch_assoc($query);
                            $payment_id = $DF['payment_id'];

                            $sql1 = "SELECT p_proof FROM payment WHERE payment_id='".$payment_id."'";
                            $res = mysqli_query($conn,  $sql1);

                            if (mysqli_num_rows($res) > 0) {
                                while ($DF = mysqli_fetch_assoc($res)) {  ?>


                <td>Transaction Proof</td>
                <td>
                    <div class="profile-picture">
                        <?php if (empty($DF['p_proof'])): ?> No transaction Photo submitted
                        <?php else: $DF['p_proof']?>
                        <img src="../uploaded-image/<?=$DF['p_proof']?>" alt="Profile Picture">
                        <?php endif; ?>
                    </div>
                </td>
                <?php } }?>
            </tr>
        </table>
        <?php endforeach; ?>
    </div>
</body>

</html>