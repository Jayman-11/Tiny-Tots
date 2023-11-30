<?php

session_start();

$q1 = $q2 = $q3 = $q4 = $q5 = $q6 = $q7 = $q8 = $q9 = $q10 = "";

if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];

    // Ratings or Reviews Table
    $showRatings = "SELECT * from ratings 
                join users on ratings.user_id = users.user_id
                join preschool on ratings.ps_id = preschool.ps_id
                where ratings.user_id = '".$user_id."'";

    $showRatings_results = mysqli_query($conn, $showRatings);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showRatings_results === false) {
        echo mysqli_error($conn);
    } else {
        $showR = mysqli_fetch_all($showRatings_results, MYSQLI_ASSOC);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedbacks</title>
    <link rel="stylesheet" href="../css/view-details.css"> <!-- Add your custom stylesheet link here -->
    <style>
    .container1 {
        max-width: 800px;
        margin: 20px auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .center {
        text-align: center;
    }
    </style>
</head>

<body>
    <?php require 'include/Parent-Header.php'; ?>
    <div class="container">
        <a href="Parent-SubRev.php"><button class="buttons green">Submit New Reviews</button></a>
        <?php if (empty($showR)): ?>
        <h2>No Reviews found</h2>

        <?php else: ?><h2 class="center">List of Reviews</h2>
        <?php foreach ($showR as $rate): ?>
        <table class="info-table">
            <tr>
                <th>Preschool Name</th>
                <th>Rate Number</th>
                <th>Comments</th>
                <th>Actions</th>
            </tr>
            <tr>
                <td><?= $rate["school_name"] ?></td>
                <td><?= $rate["rating_number"] ?></td>
                <td><?php if (empty($rate["comments"])): ?> No Comments found
                    <?php else: echo $rate["comments"]?></td>
                <?php endif; ?>
                <td>
                    <a href="Parent-EdRev.php?ratings_id=<?= $rate[
                                "ratings_id"
                            ] ?>"><button class="buttons green">Edit Reviews</button></a>
                    <a href="Parent-DelRev.php?ratings_id=<?= $rate[
                                "ratings_id"
                            ] ?>"><button class="buttons green">Delete Reviews</button></a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
    </div>
</body>

</html>