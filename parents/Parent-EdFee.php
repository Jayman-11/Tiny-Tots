<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    $user_id = $_SESSION["user_id"];
    $payment_id=htmlentities($_GET['payment_id']);

    // Enroll Table
    $showFee = "SELECT * from payment 
    JOIN preschool ON payment.ps_id = preschool.ps_id
    JOIN users on payment.user_id = users.user_id
    JOIN enroll on payment.en_id = enroll.en_id
    WHERE payment.payment_id = '".$payment_id."'";
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
    <title>Update Fee Form</title>
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

    .pos {
        text-align: center;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="pos">Update Payment Form</h2>
        <form method="post" enctype="multipart/form-data" class="edit-form">
            <div>
                <label for="p_proof">Transaction Photo: </label>
                <input type="file" name="p_proof" id="p_proof" required>
            </div>
            <button>Upload</button>
        </form>
        <?php 
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {

                        $img_name = $_FILES['p_proof']['name'];
                        $img_size = $_FILES['p_proof']['size'];
                        $tmp_name = $_FILES['p_proof']['tmp_name'];
                        $error = $_FILES['p_proof']['error'];

                        if ($error === 0) {
                            if ($img_size > 5000000) {
                                $em = "Sorry, your file is too large.";
                                header("Location: Parent-Fees.php?error=$em");
                            }else {
                                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                                $img_ex_lc = strtolower($img_ex);
                    
                                $allowed_exs = array("jpg", "jpeg", "png", "pdf"); 
                    
                                if (in_array($img_ex_lc, $allowed_exs)) {
                                    $new_img_name = uniqid("IMG-PSA-", true).'.'.$img_ex_lc;
                                    $img_upload_path = '../uploaded-image/'.$new_img_name;
                                    move_uploaded_file($tmp_name, $img_upload_path);

                                    $sql = "SELECT * FROM users WHERE user_id='".$user_id."' LIMIT 1;";

                                    $results = mysqli_query($conn, $sql);
                                
                                    if (mysqli_num_rows($results)==1) {
                                        $sql_2 = "UPDATE payment SET p_proof='$new_img_name'
                                        WHERE payment_id = '".$payment_id."'";
                                        mysqli_query($conn, $sql_2);
                                        echo "Your information has been changed. Redirecting...";
                                        echo "<script >window.location.href='Parent-Fees.php';</script >";
                                        // header("Refresh:2; url=Parent-Fees.php");  
                                        exit();
                                    } else {
                                        echo "Can't change details. Redirecting..";
                                        header("Refresh:2; url=Parent-Fees.php");  
                                        exit();
                                        }

                                }
                            }
                        }
                    }
                ?>
    </div>
</body>

</html>