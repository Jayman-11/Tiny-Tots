<?php

session_start();
if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();
    // Events Table
    $showFaculty = "SELECT * from faculty 
    inner join scadmins on faculty.sca_id = scadmins.sca_id
    inner join preschool on faculty.ps_id = preschool.ps_id
    WHERE faculty.sca_id = '".$sca_id."'";

    $showFaculty_results = mysqli_query($conn, $showFaculty);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showFaculty_results === false) {
        echo mysqli_error($conn);
    } else {
        $showF = mysqli_fetch_all($showFaculty_results, MYSQLI_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Faculty Member Management</title>
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
        <?php if (empty($showF)): ?>
        <h2>No Faculty Members are Registered</h2>

        <?php else: ?>
        <h2>List of Registered Faculty Staff</h2>
        <button onclick="window.print()" class="btn btn-primary">Print Faculty Staff Information</button>
        <a href="SCA-AddFAM.php"><button>Register Faculty</button></a><br>

        <?php foreach ($showF as $fac): ?>
        <div><br>
            <table class="info-table"><br>
                <header>
                    <?php 
                            $sql = "SELECT f_profile FROM faculty 
                            join scadmins on faculty.sca_id = scadmins.sca_id
                            WHERE faculty_id='".$fac["faculty_id"]."'";
                            $res = mysqli_query($conn,  $sql);

                            if (mysqli_num_rows($res) > 0) {
                                while ($faculty = mysqli_fetch_assoc($res)) {  ?>

                    <div class="profile-picture">
                        <img src="../uploaded-image/<?=$faculty['f_profile']?>" alt="Profile Picture">
                    </div>
                    <?php } }?>
                </header>

                <tr>
                    <th>Attribute</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>Faculty ID</td>
                    <td><?= $fac["fac_id"] ?></td>
                </tr>
                <tr>
                    <td>Full Name</td>
                    <td><?= $fac["f_fname"] ?> <?= $fac["f_lname"] ?></td>
                </tr>
                <tr>
                    <td>Faculty Date of Birth</td>
                    <td><?= $fac["f_DOB"] ?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><?= $fac["f_address"] ?></td>
                </tr>
                <tr>
                    <td>Marital Status</td>
                    <td><?= $fac["f_m_status"] ?></td>
                </tr>
                <tr>
                    <td>Nationality</td>
                    <td><?= $fac["f_nationality"] ?></td>
                </tr>
                <tr>
                    <td>Religion</td>
                    <td><?= $fac["f_religion"] ?></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><?= $fac["f_gender"] ?></td>
                </tr>
                <tr>
                    <td>Lincese No</td>
                    <td><?= $fac["f_licenseNo"] ?></td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td><?= $fac["f_subject"] ?></td>
                </tr>

                <tr>
                    <td>Phone Number</td>
                    <td><?= $fac["f_phonenumber"] ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?= $fac["f_email"] ?>/td>
                </tr>
                <tr>
                    <td>School Name</td>
                    <td><?= $fac["school_name"] ?></td>
                </tr>
                <tr>
                    <td>Registered by Admin</td>
                    <td><?= $fac["sca_first_name"] ?> <?= $fac["sca_last_name"] ?></td>
                </tr>
                <tr>
                    <td>Actions</td>
                    <td>
                        <a href="SCA-EdFAC.php?faculty_id=<?= $fac[
                                            "faculty_id"
                                        ] ?>"><button>Edit Details</button></a>
                        <a href="SCA-DelFAC.php?faculty_id=<?= $fac[
                                            "faculty_id"
                                        ] ?>"><button>Delete Faculty</button></a>
                        <a href="SCA-AddFAC.php?faculty_id=<?= $fac[
                                            "faculty_id"
                                        ] ?>"><button>Add Faculty as Adviser</button></a>
                    </td>
                </tr>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>