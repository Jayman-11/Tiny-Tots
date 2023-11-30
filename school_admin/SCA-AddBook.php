<?php

session_start();
if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    //school admin information will be gathered
    $sca_id = $_SESSION["sca_id"];
    $ps_id=htmlentities($_GET['ps_id']);

}
?>

<html lang="en">

<head>
    <title>Add Book Information</title>
    <?php require 'include/SCA-Header.php'; ?><br>
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
    </style>
</head>

<body>
    <div>
        <form method="POST" class="edit-form">
            <div>
                <label for="b_name">Book Title: </label>
                <input type="text" name="b_name" id="b_name" placeholder="Enter the book title" required>
            </div>

            <div>
                <label for="b_author">Book Author: </label>
                <input type="text" name="b_author" id="b_author" placeholder="Enter the book author" required>
            </div>

            <div>
                <label for="b_publisher">Book Publisher: </label>
                <input type="text" name="b_publisher" id="b_publisher"
                    placeholder="Enter the publisher company of the book" required>
            </div>

            <div>
                <label for="subject_name">Book Subject: </label>
                <input type="text" name="subject_name" id="subject_name" placeholder="Enter the subject of the book"
                    required>
            </div>
            <button class="submit-button">Add Book Details</button>
        </form>
    </div>

    <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $Addbook = "INSERT INTO ps_books (b_name, b_author, b_publisher, subject_name, ps_id)
                VALUES ('". $_POST['b_name'] ."', '". $_POST['b_author'] ."', '". $_POST['b_publisher'] ."', '". $_POST['subject_name'] ."','$ps_id');";

                mysqli_query($conn, $Addbook);
                echo "The book information has been added on the record. 
                    Redirecting...";
                    echo "<script >window.location.href='SCA-AccInfo.php';</script >";
                // header("Refresh:2; url=SCA-AccInfo.php");  
                exit();
            }
        ?>
</body>

</html>