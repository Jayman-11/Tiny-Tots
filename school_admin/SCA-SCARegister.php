<?php

session_start();
require '../include/Connection.php';
$conn = getDB();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register - School Admin</title>
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
    <?php require 'include/index-Header.php'; ?><br>
    <div>
        <form method="post" class="edit-form">
            <div>
                <label for="sca_first_name">First Name</label>
                <input type="text" name="sca_first_name" id="sca_first_name" placeholder="Enter your first name"
                    required>
            </div>

            <div>
                <label for="sca_last_name">Last Name</label>
                <input type="text" name="sca_last_name" id="sca_last_name" placeholder="Enter your last name" required>
            </div>

            <div>
                <label for="sca_username">Username</label>
                <input type="text" name="sca_username" id="sca_username" placeholder="Enter your username" required>
            </div>


            <div>
                <label for="sca_password">Password</label>
                <input type="text" name="sca_password" id="sca_password" placeholder="Enter your password" required>
            </div>

            <select name="ps_id">
                <?php 
                    
                    // Get all the list of the adviser that is registered on the preschool
                    $sql1 = "SELECT * FROM preschool;";
            
                    $sql1_results = mysqli_query($conn, $sql1);

                    // If there is an connection error, then echo the description of the  error
                    // Else, store the results on a variable using mysqli_fetch_all
                    if ($sql1_results === false) {
                        echo mysqli_error($conn);
                    } else {
                        $showPSL = mysqli_fetch_all($sql1_results, MYSQLI_ASSOC);
                    }
                    
                foreach ($showPSL as $PS) : $ps_id = $PS["ps_id"]?>
                <option value="<?php $ps_id?>"><?php echo $PS["school_name"]?></option>
                <?php endforeach; ?>
            </select>
            <br><button class="submit-button">Register</button>
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_POST['sca_first_name'])) {

                    $f_name=htmlentities($_POST['sca_first_name']);
                    $l_name=htmlentities($_POST['sca_last_name']);
                    $username = htmlentities($_POST['sca_username']);
                    $password = htmlentities($_POST['sca_password']);

                    $sql_2 = "INSERT INTO scadmins (sca_username, sca_password, sca_first_name, sca_last_name, ps_id)
                    VALUES ('$username', '$password', '$f_name', '$l_name', '$ps_id');";
                    mysqli_query($conn, $sql_2);

                    echo "Your registration reqest has been submitted to our admins, and this will be reviewed. 
                    Please wait for the confirmation email once it was approved. Redirecting...";
                    header("Refresh:2; url=index.php");  
                    exit();

                }
            }
            ?>

    </div>
</body>

</html>