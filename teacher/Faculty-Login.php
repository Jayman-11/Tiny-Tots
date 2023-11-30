<?php
require '../include/Connection.php';
session_start();
$conn = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['f_username'])) {
        $f_username = $_POST['f_username'];
        $f_password = $_POST['f_password'];

        $sql = "SELECT * FROM faculty 
        JOIN preschool ON faculty.ps_id = preschool.ps_id
        WHERE f_username='" . $f_username . "' 
        AND f_password='" . $f_password . "' 
        LIMIT 1;";

        $re = mysqli_query($conn, $sql);

        if (mysqli_num_rows($re) == 1) {
            $row = mysqli_fetch_assoc($re);
            if ($row['f_username'] === $f_username &&
                $row['f_password'] === $f_password) {
                $_SESSION['f_username'] = $row['f_username'];
                $_SESSION['faculty_id'] = $row['faculty_id'];
                header("Location: Faculty-AccMan.php");
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
    <title>Faculty Login Page</title>

    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>

<body>
    <?php require 'include/index-header.php'; ?>

    <main>
        <div class="title">
            <h2>Tiny Tots - Faculty</h2>
        </div>

        <?php if (!empty($error)) : ?>
        <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="post" class="login-form">
            <div class="form-group">
                <label for="f_username">Username</label>
                <input name="f_username" id="f_username" type="text" placeholder="Enter your username">
            </div>

            <div class="form-group">
                <label for="f_password">Password</label>
                <input type="password" name="f_password" id="f_password" placeholder="Enter your password">
            </div>

            <button class="submit-button">Login</button>
        </form>

        <a href="Faculty-changepass.php" class="forgot-password">Forgot Password?</a>
    </main>

</body>


</html>