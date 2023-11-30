<?php

session_start();

$q1 = $q2 = $q3 = $q4 = $q5 = $q6 = $q7 = $q8 = $q9 = $q10 = "";

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $sca_id = $_SESSION["sca_id"];
   
    
    $showRate = "SELECT * from ratings 
                join users on ratings.user_id = users.user_id
                join preschool on ratings.ps_id = preschool.ps_id
                where  ratings.ps_id = '$sca_id'";

    $showRate_results = mysqli_query($conn, $showRate);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showRate_results === false) {
        echo mysqli_error($conn);
    } else {
        $showR = mysqli_fetch_all($showRate_results, MYSQLI_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="../css/view-details.css"> <!-- Add your custom stylesheet link here -->
</head>

<body>
    <?php require 'include/SCA-Header.php'; ?>
    <div class="container">
        <?php if (empty($showR)): ?><h2>No Reviews found</h2>

        <?php else: ?><h2>Submitted Feedbacks</h2>
        <?php foreach ($showR as $rate): ?>
        <table class="info-table">
            <tr>
                <th>Name of User</th>
                <th>Preschool</th>
                <th>Rating Number</th>
                <th>Comments</th>
            </tr>
            <tr>
                <td><?= $rate["first_name"] ?> <?= $rate["last_name"] ?></td>
                <td><?= $rate["school_name"] ?></td>
                <td><?= $rate["rating_number"] ?></td>
                <td><?php if (empty($rate["comments"])): ?> No Comments found
                    <?php else: $rate["comments"]?></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
    </div>
</body>

</html>