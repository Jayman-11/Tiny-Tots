<?php

session_start();
if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    require '../include/Connection.php';
    $conn = getDB();

    //school admin information will be gathered
    $sca_id = $_SESSION["sca_id"];

        $sql = "SELECT * FROM scadmins WHERE sca_id='".$sca_id."'";
        $query = mysqli_query($conn, $sql);
        $results = mysqli_fetch_assoc($query);
        $sca_ps = $results['ps_id'];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $img_name = $_FILES['photo_name']['name'];
        $img_size = $_FILES['photo_name']['size'];
        $tmp_name = $_FILES['photo_name']['tmp_name'];
        $error = $_FILES['photo_name']['error'];
    
        if ($error === 0) {
            if ($img_size > 5000000) {
                $em = "Sorry, your file is too large.";
                header("Location: SCA-Register.php?error=$em");
            }else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
    
                $allowed_exs = array("jpg", "jpeg", "png", "pdf"); 
    
                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-PS-media", true).'.'.$img_ex_lc;
                    $img_upload_path = '../uploaded-image/'.$new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
    
                    // Insert into Database
                    $sql = "INSERT INTO ps_media (photo_name, ps_id)
                    VALUES ('$new_img_name', '$sca_ps')";
                    mysqli_query($conn, $sql);
                    echo "Redirecting...";
                    echo "<script >window.location.href='SCA-AccInfo.php';</script >";
                    // header("Refresh:2; url=SCA-AccInfo.php?");
                    exit; 
                }else {
                    $em = "You can't upload files of this type";
                    header("Location: SCA-AccInfo.php?error=$em");
                }
            }
        }else {
            $em = "unknown error occurred!";
            header("Location: SCA-AccInfo.php?error=$em");
        }
    
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Preschool Photos</title>
    <link rel="stylesheet" href="../css/view-details.css">
</head>

<body>
    <?php require 'include/SCA-Header.php'; ?><br>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <div>
                <label for="photo_name">Select File: </label>
                <input type="file" name="photo_name" id="photo_name" required>
            </div><br>
            <input type="Submit" value="Upload Photo" class="buttons green">
        </form>
</body>

</html>