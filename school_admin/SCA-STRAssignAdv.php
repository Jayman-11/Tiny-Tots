<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $SR_ID=htmlentities($_GET['SR_ID']);

    // Enrollment Table
    $showStudRec = "SELECT * from StudRec 
                join preschool on StudRec.ps_id = preschool.ps_id
                join users on StudRec.user_id = users.user_id
                left join adviser on StudRec.adviser_ID = adviser.adviser_ID
                join enroll on StudRec.en_id = enroll.en_id
                where StudRec.ps_id = '".$sca_id."' 
                AND StudRec.SR_ID = '$SR_ID' LIMIT 1";

    $showStudRec_results = mysqli_query($conn, $showStudRec);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showStudRec_results === false) {
        echo mysqli_error($conn);
    } else {
        $showSTR = mysqli_fetch_all($showStudRec_results, MYSQLI_ASSOC);
    }
}
?>

<html lang="en">

<head>
    <title>School Admin - Student Record</title>
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
    <?php foreach ($showSTR as $STR): ?>
    <div class="container">
        <p>Student Name: <?= $STR["stud_fname"] ?> <?= $STR["stud_lname"] ?></p>
        <?php endforeach; ?>


        <form method="post" class="edit-form">
            <select name="adviser_ID">
                <?php 
                
                // Get all the list of the adviser that is registered on the preschool
                $sql1 = "SELECT * FROM adviser 
                JOIN faculty ON adviser.faculty_id = faculty.faculty_id
                WHERE faculty.ps_id='".$sca_id."';";
        
                $sql1_results = mysqli_query($conn, $sql1);

                // If there is an connection error, then echo the description of the  error
                // Else, store the results on a variable using mysqli_fetch_all
                if ($sql1_results === false) {
                    echo mysqli_error($conn);
                } else {
                    $showFL = mysqli_fetch_all($sql1_results, MYSQLI_ASSOC);
                }
                
                foreach ($showFL as $faculty) : $f_id = $faculty["adviser_ID"]?>
                <option value="<?php $f_id?>"><?php echo $faculty["f_fname"]?>
                    <?php echo $faculty["f_lname"]?></option>
                <?php endforeach; ?>
            </select>
            <button class="submit-button">Add as Adviser</button>
        </form>
    </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $AddSecAdv = "UPDATE StudRec 
    SET adviser_ID =$f_id
    WHERE SR_ID = $SR_ID LIMIT 1;";

    mysqli_query($conn, $AddSecAdv);
    echo "The section and adviser has been assigned. 
        Redirecting...";
        echo "<script >window.location.href='SCA-STRec.php';</script >";
        // header("Refresh:2; url=SCA-STRec.php");  
        exit();
}
?>