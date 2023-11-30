<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{

    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];

    $en_id=htmlentities($_GET['en_id']);

    // Enroll Table
    $showEnroll = "SELECT * from enroll 
    JOIN preschool ON enroll.ps_id = preschool.ps_id
    JOIN users on enroll.user_id = users.user_id
    WHERE enroll.en_id='".$en_id."'";
    $showEnroll_results = mysqli_query($conn, $showEnroll);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showEnroll_results === false) {
        echo mysqli_error($conn);
    } else {
        $showE = mysqli_fetch_all($showEnroll_results, MYSQLI_ASSOC);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Enrollment Management</title>
    <?php require 'include/Parent-Header.php'; ?>
    <link rel="stylesheet" href="../css/view-details.css">
</head>

<body>
    <div class="container">
        <h2 style="text-align: center;">Upload Student Witdrawal Form</h2>

        <form method="post" enctype="multipart/form-data">
            <div style="text-align: center;">
                <label for="WD_Form">Select your file: </label>
                <input type="file" name="WD_Form" id="WD_Form" required>
            </div><br>
            <input type="Submit" value="Upload Withdrawal Form" class="buttons green">
        </form>
    </div>
</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $img_name = $_FILES['WD_Form']['name'];
    $img_size = $_FILES['WD_Form']['size'];
    $tmp_name = $_FILES['WD_Form']['tmp_name'];
    $error = $_FILES['WD_Form']['error'];

    if ($error === 0) {
        if ($img_size > 5000000) {
            $em = "Sorry, your file is too large.";
            header("Location: Parent-Enroll.php?error=$em");
        }else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png", "pdf", "docx"); 

            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-SWF-", true).'.'.$img_ex_lc;
                $img_upload_path = 'uploaded-image/'.$new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);

                $sql = "SELECT * FROM users WHERE user_id='".$user_id."' LIMIT 1;";

                $results = mysqli_query($conn, $sql);
            
                if (mysqli_num_rows($results)==1) {
                    $sql_2 = "UPDATE enroll SET WD_Form='$new_img_name',
                    en_status = 'Pending Withdrawal'
                    WHERE en_id = '".$en_id."'";
                    mysqli_query($conn, $sql_2);
                    echo "The Student Withdrawal Form has been submitted. Redirecting...";
                    echo "<script >window.location.href='Parent-Enroll.php';</script >";
                    // header("Refresh:2; url=Parent-Enroll.php");  
                    exit();
                } else {
                    echo "Can't upload student withdrawal form. Redirecting..";
                    echo "<script >window.location.href='Parent-Enroll.php';</script >";
                    // header("Refresh:2; url=Parent-Enroll.php");  
                    exit();
                    }

            }
        }
    }
}

?>