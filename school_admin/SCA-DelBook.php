<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $psb_id=htmlentities($_GET['psb_id']);

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preschool Book Details</title>
    <?php require 'include/SCA-Header.php'; ?><br>
    <link rel="stylesheet" href="../css/meetings-management.css">
</head>

<body>
    <div class="container">
        <h2 class="page-title">Delete Book Details</h2>
        <form method="post" enctype="multipart/form-data" class="edit-form">
            <p>Are you sure that you want to delete this book information?</p>
            <button class="approve-button">Delete</button>
            <a href="SCA-AccInfo.php" class="delete-button">Cancel</a>

        </form>
    </div>
</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_GET['psb_id'])) {

        $sql = "SELECT * FROM ps_books WHERE psb_id='".$psb_id."' LIMIT 1;";

    $results = mysqli_query($conn, $sql);

    if (mysqli_num_rows($results)==1) {
        $sql_2 = "DELETE FROM ps_books WHERE psb_id='".$psb_id."'";
        mysqli_query($conn, $sql_2);
        echo "The book information has been removed. Redirecting...";
        echo "<script >window.location.href='SCA-AccInfo.php';</script >";
        // header("Refresh:2; url=SCA-AccInfo.php");  
        exit();
    } else {
        echo "Can't remove book information. Redirecting...";
        echo "<script >window.location.href='SCA-AccInfo.php';</script >";
        // header("Refresh:2; url=SCA-AccInfo.php");  
        exit();
        } 
        
    }
}

?>