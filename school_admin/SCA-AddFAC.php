<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $faculty_id=htmlentities($_GET['faculty_id']);

}
?>

<html lang="en">

<head>
    <title>Add as Adviser</title>
    <?php require 'include/SCA-Header.php'; ?>
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
        <form method="post" class="edit-form">
            <div>
                <label for="school_year">School Year: </label>
                <input type="text" name="school_year" id="school_year" placeholder="Enter school year" required>
            </div>

            <div>
                <label for="section">Section Name: </label>
                <input type="text" name="section" id="section" placeholder="Enter section name" required>
            </div>

            <select name="grade_level">
                <option value="Nursery">Nursery</option>
                <option value="Kinder">Kinder</option>
                <option value="Prep">Prep</option>
            </select>

            <button class="submit-button">Add as Adviser</button>
        </form>
    </div>
</body>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql_2 = "INSERT INTO adviser (faculty_id, school_year, section, grade_level)
    VALUES ('$faculty_id', '".$_POST["school_year"]."', '".$_POST["section"]."', '".$_POST["grade_level"]."')";
    mysqli_query($conn, $sql_2);
    echo "The faculty member has been added as an adviser. Redirecting...";
    echo "<script >window.location.href='SCA-FAM.php';</script >";
    // header("Refresh:2; url=SCA-FAM.php");  
    exit();
    } 

?>