<?php
session_start();

if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require '../include/Connection.php';
    $conn = getDB();
    $faculty_id = $_SESSION["faculty_id"];

}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty - Edit Account Information</title>
    <link rel="stylesheet" href="../css/edit-account.css">

</head>

<body>
    <?php require 'include/Faculty-Header.php'; ?>

    <h2 class="form-title">Faculty - Edit Account Information</h2>
    <form class="edit-form" method="post" enctype="multipart/form-data">

        <div>
            <label for="f_fname">First Name: </label>
            <input type="text" name="f_fname" id="f_fname" placeholder="Enter faculty's first name" required>
        </div>

        <div>
            <label for="f_lname">Last Name: </label>
            <input type="text" name="f_lname" id="f_lname" placeholder="Enter faculty's last name" required>
        </div>

        <div>
            <label for="f_DOB">Date of Birth: </label>
            <input type="date" name="f_DOB" id="f_DOB" placeholder="Enter faculty's date of birth" required>
        </div>

        <div>
            <label for="f_address">Address: </label>
            <input type="text" name="f_address" id="f_address" placeholder="Enter faculty's address" required>
        </div>

        Marital Status: <select name="f_m_status">
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Others">Others</option>
        </select>

        <div>
            <label for="f_nationality">Nationality: </label>
            <input type="text" name="f_nationality" id="f_nationality" placeholder="Enter faculty's nationality"
                required>
        </div>

        <div>
            <label for="f_religion">Religion: </label>
            <input type="text" name="f_religion" id="f_religion" placeholder="Enter faculty's religion" required>
        </div>

        Gender: <select name="f_gender">
            <option value="Female">Female</option>
            <option value="Male">Male</option>
        </select>

        <div>
            <label for="f_licenseNo">License No: </label>
            <input type="text" name="f_licenseNo" id="f_licenseNo" placeholder="Enter faculty's license number"
                required>
        </div>

        <div>
            <label for="f_subject">Subject: </label>
            <input type="text" name="f_subject" id="f_subject" placeholder="Enter faculty's subject" required>
        </div>

        <div>
            <label for="f_phonenumber">Phone Number: </label>
            <input type="text" name="f_phonenumber" id="f_phonenumber" placeholder="Enter faculty's phone number"
                required>
        </div>

        <div>
            <label for="f_email">Email: </label>
            <input type="text" name="f_email" id="f_email" placeholder="Enter faculty's email" required>
        </div>

        <div>
            <label for="f_username">Username: </label>
            <input type="text" name="f_username" id="f_username" placeholder="Enter faculty's username" required>
        </div>

        <div>
            <label for="f_password">Password: </label>
            <input type="text" name="f_password" id="f_password" placeholder="Enter faculty's password" required>
        </div>

        <div>
            <label for="f_profile">Faculty Proile: </label>
            <input type="file" name="f_profile" id="f_profile" required>
        </div>

        <button class="submit-button">Update</button>
    </form>
        <?php 
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $img_name = $_FILES['f_profile']['name'];
                $img_size = $_FILES['f_profile']['size'];
                $tmp_name = $_FILES['f_profile']['tmp_name'];
                $error = $_FILES['f_profile']['error'];
        
                if ($error === 0) {
                    if ($img_size > 5000000) {
                        $em = "Sorry, your file is too large.";
                        header("Location: Parent-Register.php?error=$em");
                    } else {
                        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                        $img_ex_lc = strtolower($img_ex);
        
                        $allowed_exs = array("jpg", "jpeg", "png", "pdf");
        
                        if (in_array($img_ex_lc, $allowed_exs)) {
                            $new_img_name = uniqid("IMG-FA", true) . '.' . $img_ex_lc;
                            $img_upload_path = '../uploaded-image/'.$new_img_name;
                            move_uploaded_file($tmp_name, $img_upload_path);
        
                            // Insert to the Event Table
                            $UpdFac = "UPDATE faculty SET f_fname='" . $_POST['f_fname'] . "',
                            f_lname='" . $_POST['f_lname'] . "', f_DOB='" . $_POST['f_DOB'] . "',
                            f_address='" . $_POST['f_address'] . "', f_m_status='" . $_POST['f_m_status'] . "',
                            f_nationality='" . $_POST['f_nationality'] . "', f_religion='" . $_POST['f_religion'] . "',
                            f_gender='" . $_POST['f_gender'] . "', f_licenseNo='" . $_POST['f_licenseNo'] . "',
                            f_subject='" . $_POST['f_subject'] . "', f_phonenumber='" . $_POST['f_phonenumber'] . "',
                            f_email='" . $_POST['f_email'] . "', f_username='" . $_POST['f_username'] . "',
                            f_status='Pending', f_profile='$new_img_name'
                            WHERE faculty_id = '$faculty_id' LIMIT 1;";
        
                            mysqli_query($conn, $UpdFac);
                            echo "Your changes for the account have been submitted. Redirecting...";
                            echo "<script>window.location.href='Faculty-AccMan.php';</script>";
                            exit;
                        }
                    }
                }
            }
        ?>
</body>

</html>