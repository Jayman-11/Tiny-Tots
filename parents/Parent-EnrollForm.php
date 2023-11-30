<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Enrollment Form</title>
    <?php require 'include/Parent-Header.php'; ?>
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

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .info-table th,
    .info-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }

    .info-table th {
        background-color: #2a9d8f;
        color: #ffffff;
    }

    .info-table thead th {
        background-color: #264653;
        color: #ffffff;
    }

    .info-table tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .info-table th:last-child,
    .info-table td:last-child {
        border-right: none;
    }

    .profile-picture {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        /* Adjust the margin as needed */
        border-radius: 50%;
        overflow: hidden;
        /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
    }

    .profile-picture img {
        width: 200px;
        /* Adjust the size as needed */
        height: 200px;
        /* Adjust the size as needed */
        object-fit: cover;
        border-radius: 50%;
    }

    .input {
        width: 15px;
        height: 15px;
        padding: 0;
        margin: 0;
        vertical-align: bottom;
        position: relative;
        top: -1px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2 style="text-align: center;">Enrollment Form</h2>

        <form method="post" enctype="multipart/form-data" class="edit-form">

            <div>
                <label> Enrollment ID:</label> <input type="text" value="<?php echo uniqid ('0EID'); ?>" name="enrollID"
                    id="enrollID" readonly>
            </div>

            <div>
                <label for="stud_fname">Student's First Name</label>
                <input type="text" name="stud_fname" id="stud_fname" placeholder="Enter the student's first name"
                    required>
            </div>

            <div>
                <label for="stud_lname">Student's Last Name</label>
                <input type="text" name="stud_lname" id="stud_lname" placeholder="Enter the student's last name"
                    required>
            </div>

            <label> Enrolling for Preschool:</label>
            <select name="ps_id">
                <?php 
                
                // Get all the list of the adviser that is registered on the preschool
                $sql1 = "SELECT * FROM preschool WHERE status = 'Registered';";
        
                $sql1_results = mysqli_query($conn, $sql1);

                // If there is an connection error, then echo the description of the  error
                // Else, store the results on a variable using mysqli_fetch_all
                if ($sql1_results === false) {
                    echo mysqli_error($conn);
                } else {
                    $showPS = mysqli_fetch_all($sql1_results, MYSQLI_ASSOC);
                }
                
                foreach ($showPS as $preschool) : $p_id = $preschool["ps_id"]?>
                <option value="<?php echo $p_id?>"><?php echo $preschool["school_name"]?> </option>
                <?php endforeach; ?>
        
            </select>


            <div>
                <label for="year_level">Year Level</label>
                <input type="text" name="year_level" id="year_level" placeholder="Enter the year level of the student"
                    required>
            </div>

            <div>
                <label for="stud_DOB">Student's Date of Birth</label>
                <input type="date" name="stud_DOB" id="stud_DOB" placeholder="Select the Date of Birth" required>
            </div>

            <div>
                <label for="sy">School Year</label>
                <input type="text" name="sy" id="sy" placeholder="Enter the school year" required>
            </div>

            <label>Learner Status:</label>
            <select name="lrn_status">
                <option value="No LRN">No LRN</option>
                <option value="With LRN">With LRN</option>
                <option value="Returning/Balik-Aral">Returning/Balik-Aral</option>
            </select>

            <div>
                <label for="last_gradelevel">Last Grade Level</label>
                <input type="text" name="last_gradelevel" id="last_gradelevel"
                    placeholder="Enter the last grade level of the student (put N/A if not applicable)" required>
            </div>

            <div>
                <label for="last_sy">Last School Year</label>
                <input type="text" name="last_sy" id="last_sy"
                    placeholder="Enter the last school year of the student (put N/A if not applicable)" required>
            </div>

            <div>
                <label for="last_school_name">School Name</label>
                <input type="text" name="last_school_name" id="last_school_name"
                    placeholder="Enter the name of the last school" required>
            </div>

            <div>
                <label for="stud_age">Student Age</label>
                <input type="text" name="stud_age" id="stud_age" placeholder="Enter the age of the student" required>
            </div>

            <label>Student's Sex</label>
            <div>
                <input class="input" type="radio" name="stud_sex" value="Female">Female
                <input class="input" type="radio" name="stud_sex" value="Male">Male
            </div>
            <div>
                <label for="e_email">Email Address</label>
                <input type="text" name="e_email" id="e_email" placeholder="Enter the email address" required>
            </div>

            <label>Belonging to Indigenous People (IP):</label>
            <div>
                <input class="input" type="radio" name="IP" value="Yes">Yes
                <input class="input" type="radio" name="IP" value="No">No
            </div>

            <label>For Learners with Special Education Needs:</label>
            <input class="input" type="radio" name="SP" value="Yes">Yes
            <input class="input" type="radio" name="SP" value="No">No

            <div>
                <label for="stud_address">Student Address</label>
                <input type="text" name="stud_address" id="stud_address" placeholder="Enter the student's address"
                    required>
            </div>

            <div>
                <label for="father_name">Father Name:</label>
                <input type="text" name="father_name" id="father_name" placeholder="Enter the father's name">
            </div>

            <div>
                <label for="f_contact">Contact Number:</label>
                <input type="text" name="f_contact" id="f_contact" placeholder="Enter the father's contact number">
            </div>

            <div>
                <label for="mother_name">Mother Name:</label>
                <input type="text" name="mother_name" id="mother_name" placeholder="Enter the mother's name">
            </div>

            <div>
                <label for="m_contact">Contact Number:</label>
                <input type="text" name="m_contact" id="m_contact" placeholder="Enter the mother's contact number">
            </div>

            <div>
                <label for="enrollment_date">Enrollment Date</label>
                <input type="date" name="enrollment_date" id="enrollment_date" placeholder="Select the enrollment date"
                    required>
            </div>

            <div>
                <label for="PSA_file">PSA Birth Certicate: </label>
                <input type="file" name="PSA_file" id="PSA_file" required>
            </div>

            <div>
                <label for="details">Additional Details: </label>
                <input type="text" name="details" id="details" placeholder="Enter additional details">
            </div>


            <br>
            <div>
                <label for="terms">
                    <input class="input" type="checkbox" name="terms" id="terms" value="Agree" required> I hereby
                    certify that all information given below are true and correct to the
                    best of my knowledge and I allow the Department of Education to use my child's details to create
                    and/or update his/her learner profile in the Learner
                    Information System. The information herein shall be treated as confidential in compliance with the
                    Data Privacy Act of 2012.*Click OK and continue
                    filling-out the form if you AGREE; disregard the use of this Form if you DISAGREE.
                </label>
            </div>
            <button>Register</button>

        </form>

    </div>
</body>

</html>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $ps_id = $_POST['ps_id'];

    $img_name = $_FILES['PSA_file']['name'];
	$img_size = $_FILES['PSA_file']['size'];
	$tmp_name = $_FILES['PSA_file']['tmp_name'];
	$error = $_FILES['PSA_file']['error'];

    if ($error === 0) {
		if ($img_size > 5000000) {
			$em = "Sorry, your file is too large.";
		    header("Location: Parent-Enroll.php?error=$em");
		}else {
			$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
			$img_ex_lc = strtolower($img_ex);

			$allowed_exs = array("jpg", "jpeg", "png", "pdf"); 

			if (in_array($img_ex_lc, $allowed_exs)) {
				$new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
				$img_upload_path = '../uploaded-image/'.$new_img_name;
				move_uploaded_file($tmp_name, $img_upload_path);
    
                $sql = "INSERT INTO enroll (enrollID, stud_fname, stud_lname, ps_id, year_level, stud_DOB, sy, lrn_status, last_gradelevel, last_sy, last_school_name, stud_age, stud_sex, e_email, 
                IP, SP, stud_address, father_name, f_contact, mother_name, m_contact, enrollment_date, PSA_file, user_id, details)
                        VALUES ( '". $_POST['enrollID'] . "',
                                '". $_POST['stud_fname'] . "',
                                '". $_POST['stud_lname'] . "',
                                '$ps_id',
                                '". $_POST['year_level'] . "',
                                '". $_POST['stud_DOB'] . "',
                                '". $_POST['sy'] . "',
                                '". $_POST['lrn_status'] . "',
                                '". $_POST['last_gradelevel'] . "',
                                '". $_POST['last_sy'] . "',
                                '". $_POST['last_school_name'] . "',
                                '". $_POST['stud_age'] . "',
                                '". $_POST['stud_sex'] . "',
                                '". $_POST['e_email'] . "',
                                '". $_POST['IP'] . "',
                                '". $_POST['SP'] . "',
                                '". $_POST['stud_address'] . "',
                                '". $_POST['father_name'] . "',
                                '". $_POST['f_contact'] . "',
                                '". $_POST['mother_name'] . "',
                                '". $_POST['m_contact'] . "',
                                '". $_POST['enrollment_date'] . "',
                                '$new_img_name',
                                '$user_id',
                                '". $_POST['details'] . "')";
                    mysqli_query($conn, $sql);
				    echo "Your enrollment form has been submitted. Redirecting...";
                    echo "<script>window.location.href='Parent-Enroll.php';</script>";
			}else {
				$em = "You can't upload files of this type";
		        header("Location: Parent-Enroll.php");
                die();
            }}}}
?>