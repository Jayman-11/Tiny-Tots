<?php

require '../include/Connection.php';
session_start();
$conn = getDB();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Preschool Registration</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
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
    <?php require 'include/index-Header.php'; ?>
    <form method="post" enctype="multipart/form-data" class="edit-form">
        <div class="title">
            <h2>Register Your Preschool</h2>
        </div>
        <div>
            <label for="school_name">Preschool Name: </label>
            <input type="text" name="school_name" id="school_name" placeholder="Enter the preschool name" required>
        </div>

        <div>
            <label for="address">Preschool Address: </label>
            <input type="text" name="address" id="address" placeholder="Enter the preschool address" required>
        </div>

        <div>
            <label for="hours">Preschool Hours (ex: 10:00 AM - 04:400 PM): </label>
            <input type="text" name="hours" id="hours" placeholder="Enter the preschool hours" required>
        </div>

        <div>
            <label for="phone_number">Preschool Phone Number: </label>
            <input type="text" name="phone_number" id="phone_number" placeholder="Enter the preschool phone number"
                required>
        </div>

        <div>
            <label for="email">Preschool Email: </label>
            <input type="text" name="email" id="email" placeholder="Enter the preschool email" required>
        </div>

        <div>
            <label for="website">Website: </label>
            <input type="text" name="website" id="website" placeholder="Enter the preschool website" required>
        </div>

        <div>
            <label for="accreditation">Accreditation: </label>
            <input type="text" name="accreditation" id="accreditation" placeholder="Enter the preschool accreditation"
                required>
        </div>

        <div>
            <label for="fees">Fees: </label>
            <input type="text" name="fees" id="fees" placeholder="Enter the preschool fees" required>
        </div>

        <div>
            <label for="ps_zoom">Preschool Zoom Link Account (Recurring): </label>
            <input type="text" name="ps_zoom" id="ps_zoom" placeholder="Enter the preschool zoom link" required>
        </div>

        <div>
            <label for="ps_profile">Preschool Logo: </label>
            <input type="file" name="ps_profile" id="ps_profile" required>
        </div>

        <button>Register</button>
    </form>
    <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $img_name = $_FILES['ps_profile']['name'];
                    $img_size = $_FILES['ps_profile']['size'];
                    $tmp_name = $_FILES['ps_profile']['tmp_name'];
                    $error = $_FILES['ps_profile']['error'];

                    if ($error === 0) {
                        if ($img_size > 5000000) {
                            $em = "Sorry, your file is too large.";
                            header("Location: SCA-Register.php?error=$em");
                        }else {
                            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                            $img_ex_lc = strtolower($img_ex);

                            $allowed_exs = array("jpg", "jpeg", "png", "pdf"); 

                            if (in_array($img_ex_lc, $allowed_exs)) {
                                $new_img_name = uniqid("IMG-PS", true).'.'.$img_ex_lc;
                                $img_upload_path = 'uploaded-image/'.$new_img_name;
                                move_uploaded_file($tmp_name, $img_upload_path);

                                // Insert into Database
                                $sql = "INSERT INTO preschool (school_name, address, hours, 
                                phone_number, email, website, accreditation, fees, ps_zoom, 
                                ps_profile)
                                VALUES ( '". $_POST['school_name'] . "',
                                '". $_POST['address'] . "',
                                '". $_POST['hours'] . "',
                                '". $_POST['phone_number'] . "',
                                '". $_POST['email'] . "',
                                '". $_POST['website'] . "',
                                '". $_POST['accreditation'] . "',
                                '". $_POST['fees'] . "',
                                '". $_POST['ps_zoom'] . "',
                                '$new_img_name')";
                                mysqli_query($conn, $sql);
                                echo "Redirecting...";
                                header("Refresh:2; url=SCA-SCARegister.php?");
                                exit; 
                            }else {
                                $em = "You can't upload files of this type";
                                header("Location: SCA-Register.php?error=$em");
                            }
                        }
                    }else {
                        $em = "unknown error occurred!";
                        header("Location: SCA-Register.php?error=$em");
                    }

                }
            ?>
</body>

</html>