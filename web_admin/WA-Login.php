<?php

require '../include/Connection.php';
session_start();
$conn = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['wba_username'])) {

        $wba_username = $_POST['wba_username'];
        $wba_password = $_POST['wba_password'];

        $sql = "SELECT * FROM webadmins WHERE wba_username='" . $wba_username . "' 
        AND wba_password='" . $wba_password . "' LIMIT 1;";

        $re = mysqli_query($conn, $sql);

        if (mysqli_num_rows($re) == 1) {
            $row = mysqli_fetch_assoc($re);
            if ($row['wba_username'] === $wba_username &&
                $row['wba_password'] === $wba_password) {
                $_SESSION['wba_username'] = $row['wba_username'];
                $_SESSION['wba_id'] = $row['wba_id'];
                header("Location: WA-Home.php");
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Admin Login Page</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php require 'include/index-header.php'; ?>

    <main>


        <form method="post" class="login-form">
            <h2 class="title">Tiny Tots - Website Admin</h2>

            <?php if (!empty($error)): ?>
            <p class="error"><?= $error ?></p>
            <?php endif; ?>

            <div class="form-group">
                <label for="wba_username">Username</label>
                <input name="wba_username" id="wba_username" type="text" placeholder="Enter your username" required>
            </div>

            <div class="form-group">
                <label for="wba_password">Password</label>
                <input type="password" name="wba_password" id="wba_password" placeholder="Enter your password" required>
            </div>

            <button type="submit">Login</button>

        </form>

        <a href="WA-changepass.php" class="forgot-password">Forgot Password?</a>
    </main>
</body>

</html>