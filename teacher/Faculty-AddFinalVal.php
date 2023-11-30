<?php

session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username']))
{
    require 'C:\xampp\htdocs\TinyTots\Connection.php';
    $conn = getDB();

    //Store the Faculty ID on a variable
    $faculty_id = $_SESSION["faculty_id"];
    $SR_Id=htmlentities($_GET['SR_ID']);

}
?>
<html>
    <title>Faculty - Student Management</title>
    <?php require 'C:\xampp\htdocs\TinyTots\Faculty-Header.php'; ?>

    <form method="POST">

    <div>
        <label for="t_present">Total Present: </label>
        <input type="text" name="t_present" id="t_present" placeholder="Enter the student's total present" required>
    </div>

    <div>
        <label for="t_absent">Total Absent: </label>
        <input type="text" name="t_absent" id="t_absent" placeholder="Enter the student's total absent" required>
    </div>

    <div>
        <label for="t_SCDays">Total Number of School Days: </label>
        <input type="text" name="t_SCDays" id="t_SCDays" placeholder="Enter the total number of school days" required>
    </div>

    
    <div>
        <label for="GWA">General Weighted Average: </label>
        <input type="text" name="GWA" id="GWA" placeholder="Enter the student's General Weighted Average (GWA)" required>
    </div>

    <input type="Submit" value="Submit Record">
    </form>
</html>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "UPDATE StudRec SET t_present = '". $_POST['t_present'] . "', 
    t_absent = '". $_POST['t_absent'] . "', t_SCDays = '".$_POST['t_SCDays']."',
    GWA = '". $_POST['GWA'] . "' WHERE SR_ID = $SR_Id";
    mysqli_query($conn, $sql);
	echo "Student Record has been submitted. Redirecting...";
    echo "<script>window.location.href='Faculty-StudRecInfo.php';</script>";
    } 
        
?>