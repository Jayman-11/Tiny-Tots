<?php

require '../include/Connection.php';
session_start();
$conn = getDB();

$PF_ps_id=htmlentities($_GET['ps_id']);

//Retrieve all the information of the registered preschool
$PS_info = "SELECT * FROM preschool 
WHERE ps_id = '$PF_ps_id' LIMIT 1;";
$PS_info_results = mysqli_query($conn, $PS_info);

    if ($PS_info_results === false) {
        echo mysqli_error($conn);
    } else {
        $showPSI = mysqli_fetch_all($PS_info_results, MYSQLI_ASSOC);
    }

//Retrieve the list of faculty members associated with Preschool
$PSF_info = "SELECT * FROM faculty
JOIN preschool ON faculty.ps_id = preschool.ps_id 
WHERE faculty.ps_id = '$PF_ps_id'";
$PSF_info_results = mysqli_query($conn, $PSF_info);

    if ($PSF_info_results === false) {
        echo mysqli_error($conn);
    } else {
        $showPSIF = mysqli_fetch_all($PSF_info_results, MYSQLI_ASSOC);
    }

//Retrieve the list of sbujects for the preschool
$PSS_info = "SELECT * FROM subjects
JOIN preschool ON subjects.ps_id = preschool.ps_id 
JOIN faculty ON subjects.faculty_id = faculty.faculty_id
WHERE subjects.ps_id = '$PF_ps_id'";
$PSS_info_results = mysqli_query($conn, $PSS_info);

    if ($PSS_info_results === false) {
        echo mysqli_error($conn);
    } else {
        $showPSIS = mysqli_fetch_all($PSS_info_results, MYSQLI_ASSOC);
    }

//Retrieve the book information of the preschool
$PSB_info = "SELECT * FROM ps_books
JOIN preschool ON ps_books.ps_id = preschool.ps_id 
WHERE ps_books.ps_id = '$PF_ps_id'";
$PSB_info_results = mysqli_query($conn, $PSB_info);

    if ($PSB_info_results === false) {
        echo mysqli_error($conn);
    } else {
        $showPSIB = mysqli_fetch_all($PSB_info_results, MYSQLI_ASSOC);
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preschool Information</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Change to direct the page to the previous page/search function  -->
    <?php require 'include/index-header.php'; ?>
</head>

<body>
    <div class="container">
        <h1 class="mt-4 text-center">Preschool Information</h1>
        <br>
        <a href="Parent-Login.php" class="btn btn-primary btn-lg d-block mx-auto mb-4">Enroll Now</a>


        <?php if (empty($showPSIF)): ?>
        <h3>No available Information</h3><br>
        <?php else : ?>
        <?php foreach ($showPSI as $PSI): ?>
        <div class="mb-4 text-center">
            <h2><?= $PSI["school_name"] ?></h2>
            <img src="../uploaded-image/<?=$PSI['ps_profile']?>" alt="Preschool Logo" class="img-fluid rounded"
                style="max-width: 200px;">
            <p><?= $PSI["address"] ?></p>
            <p><?= $PSI["phone_number"] ?> | <?= $PSI["hours"] ?></p>
            <p><?= $PSI["email"] ?></p>
        </div>

        <!-- Exciting Details Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title text-center">Welcome to <?= $PSI["school_name"] ?>, where learning is an
                    adventure!</h2>

                <p class="card-text text-center">Our preschool is dedicated to providing a nurturing environment for
                    young minds to
                    explore, learn, and grow. With a team of passionate educators and a curriculum designed to inspire
                    curiosity, your child will embark on a journey of discovery.</p>
                <p class="card-text text-center">Join us in shaping a bright future for your little one!</p>
            </div>
        </div>

        <!-- Vision and Mission Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title text-center">Vision</h2>
                <p class="card-text text-center">To be a leading preschool that fosters creativity, critical thinking,
                    and a love
                    for
                    learning in every child.</p>
                <h2 class="card-title text-center">Mission</h2>
                <p class="card-text text-center">Our mission is to provide a safe and supportive environment that
                    encourages
                    exploration, curiosity, and the development of social and cognitive skills. We aim to instill a
                    lifelong
                    love for learning in each child through engaging activities and a well-rounded curriculum.</p>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>


        <?php if (empty($showPSIF)): ?>
        <h3>No available Faculty Member Info</h3><br>
        <?php else : ?>
        <h2 class="mb-4">Faculty Members</h2>
        <div class="row">
            <?php foreach ($showPSIF as $PSIF): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="../uploaded-image/<?=$PSIF['f_profile']?>" alt="Teacher Photo" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><?= $PSIF["f_fname"] ?> <?= $PSIF["f_lname"] ?></h5>
                        <p class="card-text"><?= $PSIF["f_subject"] ?></p>
                        <p class="card-text"><?= $PSIF["f_address"] ?></p>
                        <p class="card-text"><?= $PSIF["f_email"] ?> | <?= $PSIF["f_phonenumber"] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($showPSIS)): ?>
        <h3>No available subjects</h3><br>
        <?php else : ?>
        <h2 class="mb-4">Preschool Subjects</h2>
        <div class="row">
            <?php foreach ($showPSIS as $PSIS): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= $PSIS["subjects"] ?></h5>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($showPSIB)): ?>
        <h3>No available book details</h3><br>
        <?php else : ?>
        <h2 class="mb-4">Books</h2>
        <div class="row">
            <?php foreach ($showPSIB as $PSIB): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= $PSIB["b_name"] ?></h5>
                        <p class="card-text"><?= $PSIB["b_author"] ?></p>
                        <p class="card-text"><?= $PSIB["b_publisher"] ?></p>
                        <p class="card-text"><?= $PSIB["subject_name"] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php // Use this section to retrieve all the media 
    // that was associated with the preschool
    $sql = "SELECT * FROM ps_media 
    JOIN preschool ON ps_media.ps_id = preschool.ps_id
    WHERE ps_media.ps_id='".$PF_ps_id."'";
    $res = mysqli_query($conn,  $sql);

    if (mysqli_num_rows($res) > 0): ?>
        <h2 class="mb-4">Additional Photos</h2>
        <div class="row">
            <?php while ($ps_media = mysqli_fetch_assoc($res)): ?>
            <div class="col-md-4">
                <img src="uploaded-image/<?=$ps_media['photo_name']?>" alt="Additional Photo" class="img-fluid mb-4">
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <h3>No available photos</h3>
        <?php endif; ?>

        <!-- Include Bootstrap JS and Popper.js -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </div>
</body>

</html>