<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    $user_id = $_SESSION["user_id"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $img_name = $_FILES['pp']['name'];
        $img_size = $_FILES['pp']['size'];
        $tmp_name = $_FILES['pp']['tmp_name'];
        $error = $_FILES['pp']['error'];

        if ($error === 0) {
            if ($img_size > 5000000) {
                $em = "Sorry, your file is too large.";
                header("Location: Parent-AccInfo.php?error=$em");
            }else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
    
                $allowed_exs = array("jpg", "jpeg", "png", "pdf"); 
    
                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-USP-", true).'.'.$img_ex_lc;
                    $img_upload_path = '../uploaded-image/'.$new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
        
        $f_name=htmlentities($_POST['first_name']);
        $l_name=htmlentities($_POST['last_name']);
        $sex=htmlentities($_POST['sex']);
        $email=htmlentities($_POST['new_email']);
        $ph_number=htmlentities($_POST['new_phone_number']);
        $username = htmlentities($_POST['new_user_username']);

        $sql = "SELECT * FROM users WHERE user_id='".$user_id."' LIMIT 1;";

    $results = mysqli_query($conn, $sql);

    if (mysqli_num_rows($results)==1) {
        $sql_2 = "UPDATE users SET first_name = '$f_name', last_name = '$l_name',sex='".$sex."', 
        email='$email', phone_number='$ph_number', user_username='$username', pp='$new_img_name'
        WHERE user_id = '".$user_id."'";
        mysqli_query($conn, $sql_2);
        echo "Your information has been changed. Redirecting to Account Information page";
        echo "<script >window.location.href='Parent-AccInfo.php';</script >";
        // header("Refresh:2; url=Parent-AccInfo.php");  
        exit();
    } else {
        echo "Can't change details. Redirecting to Account Information page";
        header("Refresh:2; url=Parent-AccInfo.php");  
        exit();
        } 
    }
}}}
}

?>

<html>
<title>Edit Account Information</title>
<link rel="stylesheet" href="../css/styles.css">
<?php require 'include/Parent-Header.php'; ?>


<body>

    <main>
        <h2 style="text-align: center;">Edit Account Information</h2>
        <div class="container">
            <a href="Parent-AccInfo.php">
                <button>Back</button></a>
            <form method="post" form="edit-form" enctype="multipart/form-data">
                <div>
                    <label for="first_name">First Name: </label>
                    <input type="text" name="first_name" id="first_name" placeholder="Enter your first name" required>
                </div>

                <div>
                    <label for="last_name">Last Name: </label>
                    <input type="text" name="last_name" id="last_name" placeholder="Enter your last name" required>
                </div>

                Sex:
                <input type="radio" name="sex" value="Female">Female
                <input type="radio" name="sex" value="Male">Male

                <div>
                    <label for="new_email">New/Preferred Email: </label>
                    <input type="text" name="new_email" id="new_email" placeholder="Enter your new/preferred email"
                        required>
                </div>

                <div>
                    <label for="new_phone_number">New/Preferred Phone Number: </label>
                    <input type="text" name="new_phone_number" id="new_phone_number"
                        placeholder="Enter your new/preferred phone number" required>
                </div>

                <div>
                    <label for="new_user_username">New/Preferred Username: </label>
                    <input type="text" name="new_user_username" id="new_user_username"
                        placeholder="Enter your new/preferred username" required>
                </div>

                <div>
                    <label for="pp">Profile Picture: </label>
                    <input type="file" name="pp" id="pp" required>
                </div>

                <button>Save</button>
            </form>
        </div>
    </main>
</body>

</html>