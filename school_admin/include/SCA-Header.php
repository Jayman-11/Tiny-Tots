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
            <a href="SCA-Home.php" class="nav-item">Home</a>
            <a href="SCA-Enroll.php" class="nav-item">Enrollment</a>
            <a href="SCA-STRec.php" class="nav-item"> Student Record</a>
            <a href="SCA-FAM.php" class="nav-item">Faculty Members</a>
            <a href="SCA-Fees.php" class="nav-item">Fees Management</a>
            <!-- <a href="SCA-Messages.php" class="nav-item">Messages</a> -->
            <a href="SCA-AccInfo.php" class="nav-item">Account Info</a>
            <a href="SCA-ViewRev.php" class="nav-item">View Reviews</a>


            <?php 
            $sca_id = $_SESSION["sca_id"];

            $sql = "SELECT * FROM scadmins WHERE sca_id='".$sca_id."'";
            $query = mysqli_query($conn, $sql);
            $results = mysqli_fetch_assoc($query);
        ?>

            <a class="nav-item"><?php echo $results['sca_first_name'] . " " . $results['sca_last_name']; ?></a>
            <a href="../include/Logout.php" class="nav-item">Logout</a>
        </nav>
    </header>

    <div class="main-content">
        <h2>Tiny Tots</h2>
        <h3>S.Y. 2022-2023</h3>

</body>

</html>