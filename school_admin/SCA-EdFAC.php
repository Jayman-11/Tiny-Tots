<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = 'getDB'();

    $faculty_id=htmlentities($_GET['faculty_id']);

    $showEvent = "SELECT * from ((faculty 
    inner join scadmins on faculty.sca_id = scadmins.sca_id) 
    inner join preschool on faculty.ps_id = preschool.ps_id) 
    WHERE faculty.faculty_id='".$faculty_id."'";

    $showEvent_results = mysqli_query($conn, $showEvent);

    if ($showEvent_results === false) {
        echo mysqli_error($conn);
    } else {
        $showE = mysqli_fetch_all($showEvent_results, MYSQLI_ASSOC);  
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<html lang="en">

<head>
    <title>Edit - Faculty Information</title>
    <?php require 'include/SCA-Header.php'; ?>
    <link rel="stylesheet" href="../css/meetings-management.css">
    <style>
    .edit-form {
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .edit-form div {
        margin-bottom: 15px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #2a9d8f;
    }

    input,
    select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <form method="post" enctype="multipart/form-data" class="edit-form">
        <h2>Edit - Faculty Information</h2>
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

        <label>Marital Status</label><select name="f_m_status">
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

        <label>Gender</label><select name="f_gender">
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
            <label for="f_profile">Faculty Profile: </label>
            <input type="file" name="f_profile" id="f_profile" required>
        </div>

        <button class="submit-button">Submit Changes</button>
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
                        header("Location: SCA-FAM.php?error=$em");
                    } else {
                        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                        $img_ex_lc = strtolower($img_ex);

                        $allowed_exs = array("jpg", "jpeg", "png", "pdf");

                        if (in_array($img_ex_lc, $allowed_exs)) {
                            $new_img_name = uniqid("IMG-PS-", true) . '.' . $img_ex_lc;
                            $img_upload_path = '../uploaded-image/' . $new_img_name;
                            move_uploaded_file($tmp_name, $img_upload_path);

                            if (isset($_GET['faculty_id'])) {

                                $sql = "SELECT * FROM faculty WHERE faculty_id='" . $faculty_id . "'";

                                $results = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($results) == 1) {

                                    $sql_2 = "UPDATE faculty 
                                    SET f_profile='$new_img_name',
                                    f_fname='" . $_POST['f_fname'] . "', 
                                    f_lname='" . $_POST['f_lname'] . "',
                                    f_DOB='". $_POST['f_DOB'] ."',  
                                    f_address='". $_POST['f_address'] ."',  
                                    f_m_status='". $_POST['f_m_status'] ."',  
                                    f_nationality='". $_POST['f_nationality'] ."',  
                                    f_religion='". $_POST['f_religion'] ."',  
                                    f_gender='". $_POST['f_gender'] ."',  
                                    f_licenseNo='". $_POST['f_licenseNo'] ."',  
                                    f_subject='". $_POST['f_subject'] ."',  
                                    f_phonenumber='". $_POST['f_phonenumber'] ."',  
                                    f_email='". $_POST['f_email'] ."'
                                    WHERE faculty_id='" . $faculty_id . "'";
                                    mysqli_query($conn, $sql_2);

                                    echo "<br>The faculty information has been changed. Redirecting...";
                                    echo "<script >window.location.href='SCA-FAM.php';</script >";
                                    // header("Refresh:2; url=SCA-FAM.php");
                                    exit();
                                } else {
                                    echo "Can't change details. Redirecting to Account Information page";
                                    echo "<script >window.location.href='SCA-FAM.php';</script >";
                                    // header("Refresh:2; url=SCA-FAM.php");
                                    exit();
                                }
                            }
                        }
                    }
                }
            }
        ?>

</body>

</html>