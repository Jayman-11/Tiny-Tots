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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Payment Fee Form</title>
    <link rel="stylesheet" href="../css/faculty-page.css">
</head>

<body>
    <?php require 'include/SCA-Header.php'; ?>
    <?php foreach ($showDF as $fee): ?>
    <div class="container">
        <h2 class="delete-attendance-header">Payment Billing Summary</h2>
        <p>Enrollment ID: <?= $fee["enrollID"] ?></p>
        <p>Student Name: <?= $fee["stud_fname"] ?> <?= $fee["stud_lname"] ?></p>
        <p>School Name: <?= $fee["school_name"] ?></p>
        <p>Paid By: <?= $fee["first_name"] ?> <?= $fee["last_name"] ?></p>
        <p>Semester/School Year: <?= $fee["sy"] ?></p>
        <p>Year Level: <?= $fee["year_level"] ?></p>
        <p>Payment for: <?= $fee["payment_for"] ?></p>
        <p>Status: <?= $fee["payment_status"] ?></p>
        <p>Form of Payment: <?= $fee["FOP"] ?></p>
        <p>Total Amount: <?= $fee["total"] ?></p>
        <p>Amount Paid: <?= $fee["amount_paid"] ?></p>
        <p>Paid By: <?= $fee["first_name"] ?> <?= $fee["last_name"] ?></p>
        <p>Billing Due Date:<?= $fee["deadline"] ?></p>
        <?php 
                $sql = "SELECT p_proof FROM payment 
                WHERE payment_id='".$fee["payment_id"]."'";
                $res = mysqli_query($conn,  $sql);

                if (mysqli_num_rows($res) > 0) {
                    while ($payment = mysqli_fetch_assoc($res)) {  ?>

        <div>
            Transaction Photo:
            <?php if (empty($fee["p_proof"])): ?> No Transaction Photo
            <?php else: $fee["p_proof"]?>
            <img src="uploaded-image/<?=$payment['p_proof']?>">
            <?php endif; ?>

        </div>
        <?php } }?>
        <?php endforeach; ?>

        <form method="post">
            <div class="container">
                <p class="confirmation-message">Are you sure that you want to delete this payment form?</p>
                <button class="btn btn-danger">Delete</button>
                <a href="SCA-Fees.php" class="btn btn-primary">Cancel</a>
            </div>
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_GET['payment_id'])) {

                    $sql = "SELECT * FROM payment WHERE payment_id='".$payment_id."' LIMIT 1;";

                $results = mysqli_query($conn, $sql);

                if (mysqli_num_rows($results)==1) {
                    $sql_2 = "DELETE FROM payment WHERE payment_id='".$payment_id."'";
                    mysqli_query($conn, $sql_2);
                    echo "The payment form has been removed. Redirecting...";
                    echo "<script >window.location.href='SCA-Fees.php';</script >";
                    // header("Refresh:2; url=SCA-Fees.php");  
                    exit();
                } else {
                    echo "Can't remove payment form. Redirecting...";
                     echo "<script >window.location.href='SCA-Fees.php';</script >";
                    // header("Refresh:2; url=SCA-Fees.php");  
                    exit();
                    } 
                    
                }
            }
            ?>

    </div>
</body>

</html>