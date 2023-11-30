<?php

require '../include/Connection.php';
session_start();
$conn = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['sca_username'])) {
    
        $sca_username=$_POST['sca_username'];
        $sca_password=$_POST['sca_password'];

        $sql = "SELECT * FROM scadmins 
        JOIN preschool ON scadmins.ps_id = preschool.ps_id
        WHERE sca_username='".$sca_username."' 
        AND sca_password='".$sca_password."' AND preschool.status = 'Registered' 
        LIMIT 1;";

    $re = mysqli_query($conn, $sql);

    if (mysqli_num_rows($re)==1) {
        $row = mysqli_fetch_assoc($re);
        if ($row['sca_username'] === $sca_username && 
            $row['sca_password'] === $sca_password){
            $_SESSION['sca_username'] = $row['sca_username'];
            $_SESSION['sca_id'] = $row['sca_id'];
            header("Location: SCA-Home.php");
            exit();
        }
    } else {
        $error = "Incorrect Login Credentials";
    } 
    }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>School Admin Login Page</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>

<body>
    <?php require 'include/index-header.php'; ?>
    <main>
        <div class="title">
            <h2>Tiny Tots - Preschool Admin</h2>
            <br>
        </div>

        <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="post" class="login-form">

            <div class="form-group">
                <label for="sca_username">Username</label>
                <input name="sca_username" id="sca_username" placeholder="Enter your username">
            </div>

            <div class="form-group">
                <label for="sca_password">Password</label>
                <input type="password" name="sca_password" id="sca_password" placeholder="Enter your password">
            </div>
            <button class="submit-button">Login</button>
        </form>

        <a href="SCA-changepass.php" class="forgot-password">Forgot Password?</a>
        <a href="SCA-Register.php" class="forgot-password"><button>Register your Preschool</button></a>
    </main>
</body>

</html>