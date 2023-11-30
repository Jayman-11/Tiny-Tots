<?php

session_start();
if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{ 
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();
    
    

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Preschool Information</title>
    <?php require 'include/SCA-Header.php'; ?>

    <link rel="stylesheet" href="../ss/meetings-management.css">
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

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .info-table th,
    .info-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }

    .info-table th {
        background-color: #2a9d8f;
        color: #ffffff;
    }

    .info-table thead th {
        background-color: #264653;
        color: #ffffff;
    }

    .info-table tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .info-table th:last-child,
    .info-table td:last-child {
        border-right: none;
    }
    </style>
</head>


<body>
    <div class="container">
        <form method="post" enctype="multipart/form-data" class="edit-form">
            <h2>Preschool Account Information - Edit</h2>
            <div>
                <label for="school_name">Preschool Name: </label>
                <input type="text" name="school_name" id="school_name" placeholder="Enter the preschool name" required>
            </div>

            <div>
                <label for="address">Preschool Address: </label>
                <input type="text" name="address" id="address" placeholder="Enter the preschool address" required>
            </div>

            <div>
                <label for="hours">Preschool Hours: </label>
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
                <label for="website">Preschool Website: </label>
                <input type="text" name="website" id="website" placeholder="Enter the preschool website" required>
            </div>

            <div>
                <label for="fees">Preschool Fees: </label>
                <input type="text" name="fees" id="fees" placeholder="Enter the preschool fees" required>
            </div>

            <div>
                <label for="ps_profile">Preschool Logo: </label>
                <input type="file" name="ps_profile" id="ps_profile" required>
            </div>

            <button>Submit Changes</button>
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
                    header("Location: SCA-AccInfo.php?error=$em");
                }else {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);
        
                    $allowed_exs = array("jpg", "jpeg", "png", "pdf"); 
        
                    if (in_array($img_ex_lc, $allowed_exs)) {
                        $new_img_name = uniqid("IMG-PS-", true).'.'.$img_ex_lc;
                        $img_upload_path = '../uploaded-image/'.$new_img_name;
                        move_uploaded_file($tmp_name, $img_upload_path);
        
                            // select the preschool admin information and the preschool data
                            $sql1 = "SELECT * FROM scadmins
                            JOIN preschool ON scadmins.ps_id = preschool.ps_id
                            WHERE scadmins.ps_id = preschool.ps_id limit 1;";
    
                            $query1 = mysqli_query($conn, $sql1);
    
                            if ($query1 === false) {
                                echo mysqli_error($conn);
                            } else {
                                $info1= mysqli_fetch_all($query1, MYSQLI_ASSOC);
                            }
    
                            // Select all the information of the SCA
                            $sql = "SELECT * FROM scadmins WHERE sca_id='".$sca_id."'";
                            $query = mysqli_query($conn, $sql);
                            $results = mysqli_fetch_assoc($query);
                            $sca_ps = $results['ps_id'];
    
                            // Insert to the Event Table
                                $sql_2 = "UPDATE preschool 
                                SET school_name='". $_POST['school_name'] . "',
                                address = '". $_POST['address'] . "',
                                hours = '". $_POST['hours'] . "',
                                phone_number = '". $_POST['phone_number'] . "',
                                email = '". $_POST['email'] . "',
                                website = '". $_POST['website'] . "',
                                fees = '". $_POST['fees'] . "', ps_profile = '$new_img_name',
                                status = 'Pending'
                                WHERE preschool.ps_id='".$sca_ps."'";
    
                                mysqli_query($conn, $sql_2);
                                echo "The preschool information has been changed. Redirecting...";
                                echo "<script>window.location.href='SCA-AccInfo.php';</script>";
                                exit();
                            }
                            else {
                                $em = "You can't upload files of this type";
                                header("Location: SCA-AccInfo.php?error=$em");
                            }
                        }
                    }
                }
        
        ?>

    </div>
</body>

</html>