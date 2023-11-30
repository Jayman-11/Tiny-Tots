<?php

session_start();
require '../include/Connection.php';
$conn = getDB();

if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])){
    $faculty_id = $_SESSION["faculty_id"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Change Password</title>
    <link rel="stylesheet" href="../css/password-change-styles.css">
</head>

<body>
    <header>
        <p><a href="Faculty-Login.php"><button>Back to previous page</button></a></p>
        <h2>Password Change </h2>
    </header>

    <form method="post" class="password-change-form">
        <div class="form-group">
            <label for="f_username">Username</label>
            <input name="f_username" id="f_username" placeholder="Enter your username" required>
        </div>

        <div class="form-group">
            <label for="f_fname">First Name</label>
            <input type="text" name="f_fname" id="f_fname" placeholder="Enter your first name" required>
        </div>

        <div class="form-group">
            <label for="f_lname">Last Name</label>
            <input type="text" name="f_lname" id="f_lname" placeholder="Enter your last name" required>
        </div>

        <div class="form-group">
            <label for="new_f_password">New Password</label>
            <input type="password" name="new_f_password" id="new_f_password" placeholder="Enter your new password"
                required>
        </div>

        <button type="submit" class="submit-button">Submit</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['f_username'])) {
            $f_username = htmlentities($_POST['f_username']);
            $f_fname = htmlentities($_POST['f_fname']);
            $f_lname = htmlentities($_POST['f_lname']);
            $password = htmlentities($_POST['new_f_password']);

            $sql = "SELECT * FROM faculty WHERE f_username='$f_username' 
            AND f_fname='$f_fname' 
            AND f_lname='$f_lname' LIMIT 1;";

            $results = mysqli_query($conn, $sql);

            if (mysqli_num_rows($results) == 1) {
                $sql_2 = "UPDATE faculty SET f_password='$password' WHERE f_username='$f_username' 
                AND f_fname='$f_fname' 
                AND f_lname='$f_lname';";
                mysqli_query($conn, $sql_2);
                echo "Your password has been changed. Redirecting to Login page";
                echo "<script>window.location.href='Faculty-Login.php';</script>";
                exit();
            } else {
                echo "Incorrect details, can't change password. Redirecting to Login page";
                header("Refresh:2; url=Faculty-Login.php");  
                exit();
            } 
        }
    }
    ?>

</body>

</html>