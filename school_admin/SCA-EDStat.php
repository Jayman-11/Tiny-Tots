<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $en_id=htmlentities($_GET['en_id']);

        // Enrollment Table
        $showEnroll = "SELECT * from enroll 
        join preschool on enroll.ps_id = preschool.ps_id
        join users on enroll.user_id = users.user_id
        where enroll.en_id = '".$en_id."'";

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
    <title>Update Enrollment</title>
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

    .profile-picture {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        /* Adjust the margin as needed */
        border-radius: 50%;
        overflow: hidden;
        /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
    }

    .profile-picture img {
        width: 200px;
        /* Adjust the size as needed */
        height: 200px;
        /* Adjust the size as needed */
        object-fit: cover;
        border-radius: 50%;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Update Enrollment Status</h2>
        <button onclick="window.print()" class="btn btn-primary">Print Enrollment Form</button>
        <?php foreach ($showE as $enroll): ?>

        <form method="post" class="edit-form">

            <?php
                        $preschool_id = $enroll['ps_id'];
                        $user_id = $enroll['user_id'];
                        $enroll_id = $enroll['en_id'];
                    ?>
            <input type="hidden" value="<?php $preschool_id ?>" name="ps_id" id="ps_id" />
            <input type="hidden" value="<?php $user_id ?>" name="user_id" id="user_id" />
            <input type="hidden" value="<?php $enroll_id ?>" name="en_id" id="en_id" />

            <label>Status</label><select name="en_status">
                <option value="Pending">Pending</option>
                <option value="Enrolled">Enrolled</option>
                <option value="Denied">Denied</option>
                <option value="Pending Withdrawal">Pending Withdrawal</option>
                <option value="Withdrawn">Withdrawn</option>
            </select>
            <button>Update Status</button>
        </form>

        <table class="info-table">
            <tr>
                <th>Attribute</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Enrollment ID</td>
                <td><?= $enroll["enrollID"] ?></td>
            </tr>
            <tr>
                <td>Student Name</td>
                <td><?= $enroll["stud_fname"] ?> <?= $enroll["stud_lname"] ?></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><?= $enroll["stud_DOB"] ?></td>
            </tr>
            <tr>
                <td>School Year</td>
                <td><?= $enroll["sy"] ?></td>
            </tr>
            <tr>
                <td>Learner Status</td>
                <td><?= $enroll["lrn_status"] ?></td>
            </tr>
            <tr>
                <td>Year Level</td>
                <td><?= $enroll["year_level"] ?></td>
            </tr>
            <tr>
                <td>last Grade Level</td>
                <td><?= $enroll["last_gradelevel"] ?></td>
            </tr>
            <tr>
                <td>Last School Year</td>
                <td><?= $enroll["last_sy"] ?></td>
            </tr>
            <tr>
                <td>School Name</td>
                <td><?= $enroll["school_name"] ?></td>
            </tr>
            <tr>
                <td>Student Age</td>
                <td><?= $enroll["stud_age"] ?></td>
            </tr>
            <tr>
                <td>Student Sex</td>
                <td><?= $enroll["stud_sex"] ?></td>
            </tr>
            <tr>
                <td>IP:</td>
                <td><?= $enroll["IP"] ?></td>
            </tr>
            <tr>
                <td>SP</td>
                <td><?= $enroll["SP"] ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?= $enroll["e_email"] ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?= $enroll["stud_address"] ?></td>
            </tr>
            <tr>
                <td>Father Name</td>
                <td><?= $enroll["father_name"] ?></td>
            </tr>


            <tr>
                <td>Father Contact Number</td>
                <td><?= $enroll["f_contact"] ?></td>
            </tr>
            <tr>
                <td>Mother Name</td>
                <td><?= $enroll["mother_name"] ?></td>
            </tr>
            <tr>
                <td>Mother Contact Number</td>
                <td><?= $enroll["m_contact"] ?></td>
            </tr>
            <tr>
                <td>Enrollment Date</td>
                <td><?= $enroll["enrollment_date"] ?></td>
            </tr>
            <tr>
                <td>Details</td>
                <td><?php if (empty($enroll["details"])): ?> No Details found
                    <?php else: $enroll["details"]?></td>
                <?php endif; ?>
            </tr>
            <?php 
                $sca_id = $_SESSION["sca_id"];

                $sql = "SELECT * FROM scadmins WHERE sca_id='".$sca_id."'";
                $query = mysqli_query($conn, $sql);
                $results = mysqli_fetch_assoc($query);
                $en_id = $enroll['en_id'];

                $sql1 = "SELECT PSA_file FROM enroll WHERE en_id='".$en_id."'";
                $res = mysqli_query($conn,  $sql1);

                if (mysqli_num_rows($res) > 0) {
                    while ($enroll = mysqli_fetch_assoc($res)) {  ?>

            <?php if (empty($enroll['PSA_file'])): ?> No PSA Photo submitted
            <div class="profile-picture">
                <?php else: $enroll['PSA_file']?>
                <img src="../uploaded-image/<?=$enroll['PSA_file']?>" alt="Profile Picture">
                <?php endif; ?>

                <?php } }?>


                <?php endforeach; ?>
                <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_GET['en_id'])) {

                    $en_status=htmlentities($_POST['en_status']);
                    $sql = "SELECT * FROM enroll WHERE en_id='".$en_id."' LIMIT 1;";

                    $results = mysqli_query($conn, $sql);

                if (mysqli_num_rows($results)==1) {
                    $sql_2 = "UPDATE enroll SET en_status='".$en_status."'
                    WHERE en_id='".$en_id."'";
                    mysqli_query($conn, $sql_2);

                    $sql3 = "SELECT * FROM enroll WHERE en_id='".$en_id."' LIMIT 1;";

                    $results1 = mysqli_query($conn, $sql3);
                    $val = mysqli_fetch_assoc($results1);
                    $en_status = $val['en_status']; 
                    
                    if ($en_status == 'Enrolled'){
                    $addToStudRecT = "INSERT INTO StudRec (ps_id, user_id, en_id)
                    VALUES ('$preschool_id', '$user_id', '$enroll_id');";
                    mysqli_query($conn, $addToStudRecT);
                    echo "The enrollment form has been changed and the student record has been created. 
                    Redirecting...";
                    echo "<script>window.location.href='SCA-Enroll.php';</script>";
                    exit();
                    } else {

                    echo "The enrollment form has been changed. Redirecting...";
                    echo "<script>window.location.href='SCA-Enroll.php';</script>";
                    exit();
                }
                } else {
                    echo "Can't change details. Redirecting...";
                    echo "<script>window.location.href='SCA-Enroll.php';</script>"; 
                    exit();
                    } 
                }
            }
        ?>
            </div>
</body>
<html>