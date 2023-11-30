<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email=htmlentities($_POST['email']);
        $password = htmlentities($_POST['user_password']);

        $sql = "SELECT email, user_password FROM users WHERE email='".$email."' AND user_password='".$password."'";

        $results = mysqli_query($conn, $sql);

        if (mysqli_num_rows($results)==1) {
            $sql1 = "DELETE FROM users WHERE user_id='".$user_id."'";
            mysqli_query($conn, $sql1);
            echo "Your account has been deleted. Redirecting...";
            header("Refresh:2; url=index.php?success=Your account has been removed.");
            exit; 
           
        } else {
            echo "Incorrect details, can't delete account. Redirecting..";
            header("Refresh:2; url=Parent-AccInfo.php");  
            exit();
        } 
        }
 
    }

?>

<html>
<title>Parent - Delete Account</title>
<link rel="stylesheet" href="../css/meetings-management.css">
<?php require 'include/Parent-Header.php'; ?>

<body>
    <div class="container">
        <form class="edit-form" method="post">
            <div>
                <label for="email">Email</label>
                <input type="text" name="email" id="email" placeholder="Enter your email" required>
            </div>

            <div>
                <label for="user_password">Password</label>
                <input type="password" name="user_password" id="user_password" placeholder="Enter your password"
                    required>
            </div>


            <p>Are you sure that you want to delete your account?</p>

            <button>Delete</button>
            <a href="Parent-AccInfo.php">Cancel</a>
        </form>
    </div>
</body>

</html>