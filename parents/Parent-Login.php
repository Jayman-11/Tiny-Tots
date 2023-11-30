<?php

session_start();
require '../include/Connection.php';
$conn = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['user_username'])) {
    
        $u_username=$_POST['user_username'];
        $u_password=$_POST['user_password'];

        $sql = "SELECT * FROM users WHERE user_username='".$u_username."' 
        AND user_password='".$u_password."' LIMIT 1;";

    $re = mysqli_query($conn, $sql);

    if (mysqli_num_rows($re) === 1) {
        $row = mysqli_fetch_assoc($re);
        if ($row['user_username'] === $u_username && $row['user_password'] === $u_password){
            $_SESSION['user_username'] = $row['user_username'];
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: Parent-Home.php");
            exit();
        }
    } else {
        $error = "Incorrect Login Credentials";
    } 
    }
    }

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>

<body>
    <?php require 'include/index-header.php'; ?>
    <main>
        <title>Parent Login Page</title>



        <h2>Tiny Tots - Parents</h2>

        <?php if (!empty($error)): ?>
        <p><?= $error ?></p>
        <?php endif; ?>

        <form method="post" class="login-form">

            <div>
                <label for="user_username">Username</label>
                <input name="user_username" id="user_username" placeholder="Enter your username">
            </div>

            <div>
                <label for="user_password">Password</label>
                <input type="password" name="user_password" id="user_password" placeholder="Enter your password">
            </div>

            <button>Login</button>
            <div>

            </div>

        </form>

        <a href="Parent-Changepass.php" class="forgot-password">Forgot Password?</a>
        <a href="Parent-Register.php" class="forgot-password"><button>Create new Account</button></a>
    </main>
</body>

</html>