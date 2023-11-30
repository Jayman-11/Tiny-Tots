<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiny Tots</title>
    <link rel="stylesheet" href="../css/header-styles.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <a href="Parent-Home.php" class="nav-item">Home</a>
            <a href="Parent-Enroll.php" class="nav-item">Enrollment</a>
            <a href="Parent-StudInfo.php" class="nav-item">Student Info</a>
            <a href="Parent-Fees.php" class="nav-item">Fees/Payment</a>
            <a href="Parent-Meetings.php" class="nav-item">Meetings</a>
            <!-- <a href="Parent-Messages.php" class="nav-item">Messages</a> -->
            <a href="Parent-AccInfo.php" class="nav-item">Account Info</a>
            <a href="Parent-ViewRev.php" class="nav-item">Submit Reviews</a>

            <?php 
                $user_id = $_SESSION["user_id"];

                $sql = "SELECT * FROM users WHERE user_id='".$user_id."'";
                $query = mysqli_query($conn, $sql);
                $results = mysqli_fetch_assoc($query);
            ?>

            <a class="nav-item"><?php $n = $results['first_name'] . " " . $results['last_name']; 
            echo $n;  ?> </a>
            <a href="../include/Logout.php" class="nav-item">Logout</a>
        </nav>
    </header>
    <div class="main-content">
        <h1>Tiny Tots</h1>
        <h3>S.Y. 2022-2023</h3>
    </div>
</body>

</html>