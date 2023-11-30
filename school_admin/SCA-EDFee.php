<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $payment_id=htmlentities($_GET['payment_id']);

    // Payment Table
    $DelFees = "SELECT * from payment 
    join preschool on payment.ps_id = preschool.ps_id
    join users on payment.user_id = users.user_id
    join enroll on payment.en_id = enroll.en_id
    WHERE payment.payment_id = '".$payment_id."'";

    $DelFees_results = mysqli_query($conn, $DelFees);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($DelFees_results === false) {
        echo mysqli_error($conn);
    } else {
        $showDF = mysqli_fetch_all($DelFees_results, MYSQLI_ASSOC);     
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Payment Form</title>
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
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Payment Form</h2>
        <button onclick="window.print()" class="btn btn-primary">Print Fee Form</button>

        <?php foreach ($showDF as $fee): ?>
        <form method="post" enctype="multipart/form-data" class="edit-form">
            Billing Due Date: <input type="date" name="deadline" id="deadline" required>
            Status: <select name="payment_status">
                <option value="Paid">Paid</option>
                <option value="Pending">Pending</option>
                <option value="Unpaid">Unpaid</option>
                <option value="Refunded">Refunded</option>
            </select>
            <button>Update Status</button>
        </form>

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

            <?php 
                    $sql = "SELECT p_proof FROM payment 
                    WHERE payment_id='".$fee["payment_id"]."'";
                    $res = mysqli_query($conn,  $sql);

                    if (mysqli_num_rows($res) > 0) {
                        while ($payment = mysqli_fetch_assoc($res)) {  ?>

            <tr>
                <td>Transaction Photo</td>
                <td><?php if (empty($fee["p_proof"])): ?> No Transaction Photo
                    <?php else: $fee["p_proof"]?>
                    <img src="uploaded-image/<?=$payment['p_proof']?>">
                    <?php endif; ?>
                </td>

            </tr>
            <?php } }?>
        </table>
        <?php endforeach; ?>
    </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_GET['payment_id'])) {

        $p_status=htmlentities($_POST['payment_status']);
        $duedate=htmlentities($_POST['deadline']);
        $sql = "SELECT * FROM payment WHERE payment_id='".$payment_id."' LIMIT 1;";

        $results = mysqli_query($conn, $sql);

    if (mysqli_num_rows($results)==1) {
        $sql_2 = "UPDATE payment SET payment_status='".$p_status."', deadline = '".$duedate."'
        WHERE payment_id='".$payment_id."'";
        mysqli_query($conn, $sql_2);

        echo "The event information has been changed. Redirecting...";
        echo "<script >window.location.href='SCA-Fees.php';</script >";
        // header("Refresh:2; url=SCA-Fees.php");  
        exit();
    } else {
        echo "Can't change details. Redirecting to Account Information page";
        echo "<script >window.location.href='SCA-Fees.php';</script >";
        // header("Refresh:2; url=SCA-Fees.php");  
        exit();
        } 
    }
}
?>