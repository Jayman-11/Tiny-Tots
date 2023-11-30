<?php

require '../include/Connection.php';
session_start();
$conn = getDB();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Admin Change Password</title>
    <link rel="stylesheet" href="../css/password-change-styles.css">

</head>

<body>
    <header>
        <p><a href="SCA-Login.php"><button>Back to previous page</button></a>
        <p>
        <h2>Password Change</h2>
    </header>
    <form method="post" class="password-change-form">
        <div>
            <label for="sca_username">Username</label>
            <input name="sca_username" id="sca_username" placeholder="Enter your username" required>
        </div>

        <div>
            <label for="sca_first_name">First Name</label>
            <input type="text" name="sca_first_name" id="sca_first_name" placeholder="Enter your first name" required>
        </div>

        <div>
            <label for="sca_last_name">Last Name</label>
            <input type="text" name="sca_last_name" id="sca_last_name" placeholder="Enter your last name" required>
        </div>

        <div>
            <label for="new_user_password">New Password</label>
            <input type="password" name="new_user_password" id="new_user_password" placeholder="Enter your new password"
                required>
        </div>

        <button type="submit" class="submit-button">Submit</button>
    </form>

    <?php

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST['sca_username'])) {
                
                $u_username=htmlentities($_POST['sca_username']);
                $f_name=htmlentities($_POST['sca_first_name']);
                $l_name=htmlentities($_POST['sca_last_name']);
                $password = htmlentities($_POST['new_user_password']);

                $sql = "SELECT * FROM scadmins WHERE sca_username='".$u_username."' AND sca_first_name='".$f_name."' 
                AND sca_last_name='".$l_name."' LIMIT 1;";

            $results = mysqli_query($conn, $sql);

            if (mysqli_num_rows($results)==1) {
                $sql_2 = "UPDATE scadmins SET sca_password='$password' WHERE sca_username='".$u_username."' AND sca_first_name='".$f_name."' 
                AND sca_last_name='".$l_name."'";
                mysqli_query($conn, $sql_2);
                echo "Your password has been changed. Redirecting to Login page";
                 echo "<script >window.location.href='SCA-Login.php';</script >";
                // header("Refresh:2; url=SCA-Login.php");  
                exit();
            } else {
                echo "Incorrect details, can't change password. Redirecting to Login page";
                 echo "<script >window.location.href='SCA-Login.php';</script >";
                // header("Refresh:2; url=SCA-Login.php");  
                exit();
            } } }

        ?>
</body>

</html>