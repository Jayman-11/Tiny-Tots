<?php

session_start();
if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    //school admin information will be gathered
    $sca_id = $_SESSION["sca_id"];
    $info_sql = "SELECT * FROM scadmins
                WHERE sca_id='".$sca_id."'";

    $info_results = mysqli_query($conn, $info_sql);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($info_results === false) {
        echo mysqli_error($conn);
    } else {
        $info= mysqli_fetch_all($info_results, MYSQLI_ASSOC);
    }  
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Account Information</title>
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
    <form method="post" class="edit-form">
        <h2>Admin Account Information - Edit</h2>
        <div>
            <label for="sca_first_name">First Name</label>
            <input type="text" name="sca_first_name" id="sca_first_name" placeholder="Enter your first name" required>
        </div>

        <div>
            <label for="sca_last_name">Last Name</label>
            <input type="text" name="sca_last_name" id="sca_last_name" placeholder="Enter your last name" required>
        </div>

        <div>
            <label for="new_sca_username">New Username</label>
            <input type="text" name="new_sca_username" id="new_sca_username" placeholder="Enter your new username"
                required>
        </div>

        <button>Submit Changes</button>
    </form>
    <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_POST['sca_first_name'])) {

                    $f_name=htmlentities($_POST['sca_first_name']);
                    $l_name=htmlentities($_POST['sca_last_name']);
                    $username = htmlentities($_POST['new_sca_username']);

                    $sql_2 = "UPDATE scadmins SET sca_first_name='".$f_name."',
                    sca_last_name='".$l_name."', sca_username='$username'
                    WHERE sca_id='".$sca_id."'";
                    mysqli_query($conn, $sql_2);

                    echo "Your information has been changed. Redirecting...";
                    echo "<script >window.location.href='SCA-AccInfo.php';</script >";
                    // header("Refresh:2; url=SCA-AccInfo.php");  
                    exit();

                }
            }
        ?>
</body>

</html>