<?php

session_start();

$q1 = $q2 = $q3 = $q4 = $q5 = $q6 = $q7 = $q8 = $q9 = $q10 = "";

if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];
    $ratings_id=htmlentities($_GET['ratings_id']);

    // Ratings or Reviews Table
    $showRatings = "SELECT * from ratings 
                join users on ratings.user_id = users.user_id
                join preschool on ratings.ps_id = preschool.ps_id
                where ratings.ratings_id = '".$ratings_id."'";

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

    .button1 {
        background-color: red;
        border-radius: 4px;
        border: none;
        color: white;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        cursor: pointer;
    }

    .button2 {
        background-color: green;
        border-radius: 4px;
        border: none;
        color: white;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <?php require 'include/Parent-Header.php'; ?>
    <div class="container">
        <a href="Parent-ViewRev.php"><button class="buttons green">Back</button></a>
        <h2 class="center">Review Information</h2>


        <?php foreach ($showR as $rate): ?>
        <table class="info-table">
            <tr>
                <th>Preschool Name</th>
                <th>Rate Number</th>
                <th>Comments</th>
            </tr>
            <tr>
                <td><?= $rate["school_name"] ?></td>
                <td><?= $rate["rating_number"] ?></td>
                <td><?php if (empty($rate["comments"])): ?> No Comments found
                    <?php else: echo $rate["comments"]?></td>
                <?php endif; ?>
        </table>
        <?php endforeach; ?>

        <form method="post" class="container"><br>
            <div class="center">
                <p>Are you sure that you want to delete this review?</p>
                <button class="button1">Delete</button>
                <a class="button2" style="text-decoration:none" href="Parent-ViewRev.php">Cancel</a>
            </div>
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                if (isset($_GET['ratings_id'])) {

                    $sql = "SELECT * FROM ratings WHERE ratings_id='".$ratings_id."' LIMIT 1;";

                $results = mysqli_query($conn, $sql);

                if (mysqli_num_rows($results)==1) {
                    $sql_2 = "DELETE FROM ratings WHERE ratings_id='".$ratings_id."'";
                    mysqli_query($conn, $sql_2);
                    echo "The` rating has been removed. Redirecting...";
                    echo "<script >window.location.href='Parent-ViewRev.php';</script >";
                    // header("Refresh:2; url=Parent-ViewRev.php");  
                    exit();
                } else {
                    echo "Can't remove review. Redirecting...";
                    echo "<script >window.location.href='Parent-ViewRev.php';</script >";
                    // header("Refresh:2; url=Parent-ViewRev.php");  
                    exit();
                    } 
                    
                }
            }
        ?>

</body>

</html>