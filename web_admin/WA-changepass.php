<?php
session_start();
require '../include/Connection.php';
$conn = getDB();

if (isset($_SESSION['wba_id']) && isset($_SESSION['wba_username'])){
    $wba_id = $_SESSION["wba_id"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Admin Change Password</title>
    <link rel="stylesheet" href="../css/password-change-styles.css">
</head>

<body>
    <header>
        <p><a href="WA-Login.php" class="back-button"><button>Back to previous page</button></a></p>
        <h2>Password Change </h2>
    </header>

    <form method="post" class="password-change-form">
        <div class="form-group">
            <label for="wba_username">Username</label>
            <input name="wba_username" id="wba_username" type="text" placeholder="Enter your username" required>
        </div>

        <div class="form-group">
            <label for="wba_first_name">First Name</label>
            <input type="text" name="wba_first_name" id="wba_first_name" placeholder="Enter your first name" required>
        </div>

        <div class="form-group">
            <label for="wba_last_name">Last Name</label>
            <input type="text" name="wba_last_name" id="wba_last_name" placeholder="Enter your last name" required>
        </div>

        <div class="form-group">
            <label for="new_wba_password">New Password</label>
            <input type="password" name="new_wba_password" id="new_wba_password" placeholder="Enter your new password"
                required>
        </div>

        <button type="submit">Submit</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['wba_username'])) {
            $wba_username = htmlentities($_POST['wba_username']);
            $wba_first_name = htmlentities($_POST['wba_first_name']);
            $wba_last_name = htmlentities($_POST['wba_last_name']);
            $password = htmlentities($_POST['new_wba_password']);

            $sql = "SELECT * FROM webadmins WHERE wba_username='$wba_username' 
            AND wba_first_name='$wba_first_name' 
            AND wba_last_name='$wba_last_name' LIMIT 1;";

            $results = mysqli_query($conn, $sql);

            if (mysqli_num_rows($results) == 1) {
                $sql_2 = "UPDATE webadmins SET wba_password='$password' WHERE wba_username='$wba_username' 
                AND wba_first_name='$wba_first_name'
                AND wba_last_name='$wba_last_name';";
                mysqli_query($conn, $sql_2);
                echo "Your password has been changed. Redirecting to Login page";
                echo "<script>window.location.href='WA-Login.php';</script>";
                exit();
            } else {
                echo "Incorrect details, can't change password. Redirecting to Login page";
                echo "<script>window.location.href='WA-Login.php';</script>";
                exit();
            } 
        }
    }
    ?>

</body>

</html>