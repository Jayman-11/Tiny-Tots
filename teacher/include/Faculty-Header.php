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
            <a href="Faculty-AccMan.php" class="nav-item">My Profile/Home</a>
            <a href="Faculty-StudRecInfo.php" class="nav-item">View Student Information</a>
            <a href="Faculty-Meetings.php" class="nav-item">Meetings Management</a>

            <?php 
        $faculty_id = $_SESSION["faculty_id"];
        $sql = "SELECT * FROM faculty WHERE faculty_id='".$faculty_id."'";
        $query = mysqli_query($conn, $sql);
        $results = mysqli_fetch_assoc($query);
    ?>
            <a class="nav-item"><?php echo $results['f_fname'] . ' ' . $results['f_lname']; ?></a>
            <a href="../include/Logout.php" class="nav-item logout">Logout</a>
        </nav>
    </header>

    <div class="main-content">
        <h1>Tiny Tots</h1>
        <h3>S.Y. 2022-2023</h3>

    </div>

</body>

</html>