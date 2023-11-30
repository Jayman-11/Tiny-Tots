<?php

require '../include/Connection.php';
session_start();
$conn = getDB();
?>

<html>
<link rel="stylesheet" href="../css/password-change-styles.css">
<p><a href="Parent-Login.php"><button>Back to previous page</button></a>
<p>
<header>
    <title>Parent Change Password</title>
    <h2>Password Change </h2>
</header>

<body>
    <form method="post" class="password-change-form">
        <div>
            <label for="user_username">Username</label>
            <input name="user_username" id="user_username" placeholder="Enter your username" required>
        </div>

        <div>
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" placeholder="Enter your first name" required>
        </div>

        <div>
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" placeholder="Enter your last name" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="Enter your email" required>
        </div>

        <div>
            <label for="new_user_password">New Password</label>
            <input type="password" name="new_user_password" id="new_user_password" placeholder="Enter your new password"
                required>
        </div>

        <button>Submit</button>
    </form>
</body>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['user_username'])) {
            
            $u_username=htmlentities($_POST['user_username']);
            $f_name=htmlentities($_POST['first_name']);
            $l_name=htmlentities($_POST['last_name']);
            $email=htmlentities($_POST['email']);
            $password = htmlentities($_POST['new_user_password']);

            $sql = "SELECT * FROM users WHERE user_username='".$u_username."' AND first_name='".$f_name."' 
            AND last_name='".$l_name."' AND email='".$email."' LIMIT 1;";

        $results = mysqli_query($conn, $sql);

        if (mysqli_num_rows($results)==1) {
            $sql_2 = "UPDATE users SET user_password='$password' WHERE user_username='".$u_username."' AND first_name='".$f_name."' 
            AND last_name='".$l_name."' AND email='".$email."'";
            mysqli_query($conn, $sql_2);
            echo "Your password has been changed. Redirecting to Login page";
            header("Refresh:2; url=Parent-Login.php");  
            exit();
        } else {
            echo "Incorrect details, can't change password. Redirecting to Login page";
            header("Refresh:2; url=Parent-Login.php");  
            exit();
        } 
        }
        }

    ?>

</html>