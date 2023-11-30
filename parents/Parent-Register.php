<?php

require '../include/Connection.php';
session_start();
$conn = getDB();

?>

<html>

<head>
    <title>Parent Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f8f9fa;
    }

    main {
        max-width: 600px;
        margin: 0 auto;
        background-color: #ffffff;
        padding: 20px;
        margin-top: 50px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form {
        margin-top: 20px;
    }

    .form-check label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group {
        margin-bottom: 15px;
    }
    </style>
</head>

<body>
    <?php require 'include/index-header.php'; ?>
    <main>
        <h2 class="text-center">Register</h2>

        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_GET['error']; ?>
        </div>
        <?php endif ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" name="first_name" id="first_name"
                    placeholder="Enter your first name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" name="last_name" id="last_name"
                    placeholder="Enter your last name" required>
            </div>

            <div class="form-group">
                <label class="form-check-label">Sex:</label>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="sex" value="Female">Female
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="sex" value="Male">Male
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" id="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" class="form-control" name="phone_number" id="phone_number"
                    placeholder="Enter your phone number" required>
            </div>

            <div class="form-group">
                <label for="user_username">Username</label>
                <input type="text" class="form-control" name="user_username" id="user_username"
                    placeholder="Enter your username" required>
            </div>

            <div class="form-group">
                <label for="user_password">Password</label>
                <input type="password" class="form-control" name="user_password" id="user_password"
                    placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <label for="pp">Profile Picture</label>
                <input type="file" class="form-control-file" name="pp" id="pp" required>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="terms" id="terms" value="Agree" required>
                <label class="form-check-label" for="terms">By completing the registration, I understood and agree to
                    the TinyTots Terms of Use and Privacy Statement.</label>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>

        </form>
    </main>
</body>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $img_name = $_FILES['pp']['name'];
	$img_size = $_FILES['pp']['size'];
	$tmp_name = $_FILES['pp']['tmp_name'];
	$error = $_FILES['pp']['error'];

    if ($error === 0) {
		if ($img_size > 5000000) {
			$em = "Sorry, your file is too large.";
		    header("Location: Parent-Register.php?error=$em");
		}else {
			$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
			$img_ex_lc = strtolower($img_ex);

			$allowed_exs = array("jpg", "jpeg", "png", "pdf"); 

			if (in_array($img_ex_lc, $allowed_exs)) {
				$new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
				$img_upload_path = '../uploaded-image/'.$new_img_name;
				move_uploaded_file($tmp_name, $img_upload_path);

				// Insert into Database
				$sql = "INSERT INTO users (first_name, last_name, sex, email, phone_number, user_username, user_password, pp)
            VALUES ( '". $_POST['first_name'] . "',
                 '". $_POST['last_name'] . "',
                 '". $_POST['sex'] . "',
                 '". $_POST['email'] . "',
                 '". $_POST['phone_number'] . "',
                 '". $_POST['user_username'] . "',
                 '". $_POST['user_password'] . "',
                 '$new_img_name')";
				mysqli_query($conn, $sql);
				echo "You are now registered. Redirecting to Login Page...";
				echo "<script >window.location.href='Parent-Login.php';</script >";
                // header("Refresh:2; url=Parent-Login.php?success=Your account has been created successfully");
                exit; 
			}else {
				$em = "You can't upload files of this type";
		        header("Location: Parent-Register.php?error=$em");
			}
		}
	}else {
		$em = "unknown error occurred!";
		header("Location: Parent-Register.php?error=$em");
	}

}
?>

</html>