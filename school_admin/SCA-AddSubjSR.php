<?php
session_start();

if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $SR_ID=htmlentities($_GET['SR_ID']);

    // Student Record Table
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
    <title>Add Subjects for Students</title>
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

<?php foreach ($showSTR as $STR): ?>
<p>Student Name: <?= $STR["stud_fname"] ?> <?= $STR["stud_lname"] ?></p>
<?php endforeach; ?>

<form method="post" class="edit-form">
    <select name="subject_id">
        
        <?php 
            
            // Get all the list of the adviser that is registered on the preschool
            $sql1 = "SELECT *
            FROM subjects 
            join preschool on subjects.ps_id = preschool.ps_id
            JOIN faculty ON subjects.faculty_id = faculty.faculty_id
            WHERE subjects.ps_id='".$sca_id."';";
    
            $sql1_results = mysqli_query($conn, $sql1);

            // If there is an connection error, then echo the description of the  error
            // Else, store the results on a variable using mysqli_fetch_all
            if ($sql1_results === false) {
                echo mysqli_error($conn);
            } else {
                $showSub = mysqli_fetch_all($sql1_results, MYSQLI_ASSOC);
            }
            
           foreach ($showSub as $subject) : $s_id = $subject["subject_id"];
           ?>
        <option value="<?php echo $s_id?>"><?php echo $subject["subjects"]?></option>
        <?php $F_ID=$subject['faculty_id']; ?> <?php endforeach; ?>
        <input type="hidden" value="<?php echo $F_ID ?>" name="faculty_id" id="faculty_id"/>
        
    </select>
    <button class="submit-button">Add Subject</button>
</form>

</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $subject_id = $_POST['subject_id'];
    $faculty_id = $_POST['faculty_id'];

    $AddSub = "INSERT INTO stud_per_subject (SR_ID, subject_id, faculty_id)
    VALUES ('$SR_ID', '$subject_id', '$faculty_id');";

    mysqli_query($conn, $AddSub);
    echo "The subject has been added on the student record. 
        Redirecting...";
    echo "<script >window.location.href='SCA-STRec.php';</script >";        
    // header("Refresh:2; url=SCA-STRec.php");  
    exit();
}
?>