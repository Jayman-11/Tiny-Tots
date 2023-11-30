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
            <a href="WA-Home.php" class="nav-item">Home</a>
            <a href="WA-ChangeReq.php" class="nav-item">Preschool Account Management</a>

            <?php
            $wba_id = $_SESSION["wba_id"];

            $sql = "SELECT * FROM webadmins WHERE wba_id='$wba_id'";
            $query = mysqli_query($conn, $sql);
            $results = mysqli_fetch_assoc($query);

            $fullName = $results['wba_first_name'] . ' ' . $results['wba_last_name'];
            echo "<span class='nav-item' >$fullName</span>";
            ?>
            <a href="../include/Logout.php" class="nav-item logout">Logout</a>
        </nav>
    </header>

    <div class="main-content">
        <h1>Tiny Tots</h1>
        <h3>S.Y. 2022-2023</h3>
    </div>

</body>

</html>