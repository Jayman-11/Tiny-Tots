<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];
}
?>

<html>

<head>
    <title>Payment Form</title>
    <link rel="stylesheet" href="../css/edit-account.css">
</head>
<?php require 'include/Parent-Header.php'; ?>


<?php if (isset($_GET['error'])): ?>
<p><?php echo $_GET['error']; ?></p>
<?php endif ?>

<form class="edit-form" method="post" enctype="multipart/form-data">
    <h2>Payment Form</h2>
    <div>
        <label for="stud_fname">Student's First Name: </label>
        <input type="text" name="stud_fname" id="stud_fname" placeholder="Enter the first name of the student" required>
    </div>

    <div>
        <label for="stud_lname">Student's Last Name: </label>
        <input type="text" name="stud_lname" id="stud_lname" placeholder="Enter the last name of the student" required>
    </div>

    Payment for Preschool:
    <select name="ps_id">
        <option value="1">Children of Mary Academy of Dasmariñas Cavite</option>
        <option value="2">Raises Montessori Academe</option>
        <option value="3">Luke 19:4 Child Development Center Incorporated</option>
    </select>

    <div>
        <label for="total">Total Cost: </label>
        <input type="text" name="total" id="total" placeholder="Enter the total amount" required>
    </div>

    <div>
        <label for="amount_paid">Amount Paid: </label>
        <input type="text" name="amount_paid" id="amount_paid" placeholder="Enter the total amount paid" required>
    </div>

    Payment for:
    <select name="payment_for">
        <option value="Tuition">Tuition</option>
        <option value="Books">Books</option>
        <option value="Laboratory Fees">Laboratory Fees</option>
        <option value="Uniform">Uniform</option>
        <option value="Miscellaneous Fees">Miscellaneous Fees</option>
        <option value="Total Fees">Total Fees</option>
        <option value="Others">Others</option>
    </select><br>

    Form of Payment:
    <select name="FOP">
        <option value="EPayment">EPayment/Gcash</option>
        <option value="Cash">Cash</option>
    </select>

    <div>
        <label for="p_proof">Transaction Photo/Proof: </label>
        <input type="file" name="p_proof" id="p_proof" required>
    </div>

    <div>
        <label for="terms"></label>
        <input type="checkbox" name="terms" id="terms" value="Agree" required> I hereby certify that all information
        given below are true and correct to the
        best of my knowledge. And by completing the payment form with Tiny Tots, I understood and agree to the TinyTots
        Terms and conditions of payment processing.
    </div>
    <button>Submit Form</button>
</form>

</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $f_name=htmlentities($_POST['stud_fname']);
    $l_name=htmlentities($_POST['stud_lname']);
    $ps_id=htmlentities($_POST['ps_id']);

    $checkStud = "SELECT * FROM enroll 
    WHERE stud_fname ='$f_name' AND
    stud_lname = '$l_name' AND ps_id='$ps_id' LIMIT 1;";

    $checkStud_results = mysqli_query($conn, $checkStud);
    
    
    if (mysqli_num_rows($checkStud_results)==1) {
        $query = "SELECT * FROM enroll 
        WHERE stud_fname ='$f_name' AND
        stud_lname = '$l_name' AND ps_id='$ps_id' LIMIT 1;";
        $query = mysqli_query($conn, $query);
        $results = mysqli_fetch_assoc($query);
        $en_id = $results['en_id'];

        $img_name = $_FILES['p_proof']['name'];
        $img_size = $_FILES['p_proof']['size'];
        $tmp_name = $_FILES['p_proof']['tmp_name'];
        $error = $_FILES['p_proof']['error'];

        if ($error === 0) {
            if ($img_size > 5000000) {
                $em = "Sorry, your file is too large.";
                header("Location: Parent-Fees.php?error=$em");
            }else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png", "pdf"); 

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                    $img_upload_path = '../uploaded-image/'.$new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
        
                    $sql = "INSERT INTO payment (ps_id, user_id, en_id, FOP, payment_for, total, 
                    amount_paid, p_proof)
                            VALUES ('". $_POST['ps_id'] . "',
                                    '$user_id', '$en_id',
                                    '". $_POST['FOP'] . "',
                                    '". $_POST['payment_for'] . "',
                                    '". $_POST['total'] . "',
                                    '". $_POST['amount_paid'] . "',
                                    '$new_img_name')";
                        mysqli_query($conn, $sql);
                        echo "Your payment form has been submitted. Redirecting...";
                        echo "<script>window.location.href='Parent-Fees.php';</script>";
                }else {
                    $em = "You can't upload files of this type";
                    header("Location: Parent-Fees.php");
                    die();
                }}}
    } else {
        echo "No Record";
        echo mysqli_error($conn);
    }}

?>