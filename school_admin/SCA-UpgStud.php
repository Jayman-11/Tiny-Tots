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
                where StudRec.SR_ID = '".$SR_ID."'";

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Record</title>
    <link rel="stylesheet" href="../css/faculty-page.css">
</head>

<body>
    <?php require 'include/SCA-Header.php'; ?>
    <div class="container">
        <?php foreach ($showSTR as $STR): 
                $ps_id=$STR["ps_id"];
                $user_id=$STR["user_id"];
                $en_id=$STR["en_id"];
                ?>

        <form method="post">
            <select name="sr_grade_level">
                <option value="Nursery">Nursery</option>
                <option value="Kinder">Kinder</option>
                <option value="Prep">Prep</option>
            </select>
            <button class="btn btn-primary">Update Grade Level</button>
        </form>
        <?php endforeach ?>
    </div>
</body>

</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $AddSRec = "INSERT INTO StudRec (sr_grade_level, ps_id, user_id, en_id)
    VALUES ('".$_POST["sr_grade_level"]."', '$ps_id', '$user_id', '$en_id');";

    mysqli_query($conn, $AddSRec);
    echo "The student record has been updated. 
        Redirecting...";
        echo "<script >window.location.href='SCA-STRec.php';</script >";
    // header("Refresh:2; url=SCA-STRec.php");  
    exit();
}
?>