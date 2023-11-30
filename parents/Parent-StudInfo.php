<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{

    require '../include/Connection.php';
    $conn = getDB();

    $user_id = $_SESSION["user_id"];

    // Student Record Table
    $showRecord = "SELECT * from StudRec 
    join users on StudRec.user_id = users.user_id
    join enroll on StudRec.en_id = enroll.en_id
    JOIN adviser on StudRec.adviser_ID = adviser.adviser_ID
    WHERE StudRec.user_id = '".$user_id."';";

    $sshowRecord_results = mysqli_query($conn, $showRecord);

    // If there is an connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($sshowRecord_results === false) {
        echo mysqli_error($conn);
    } else {
        $showR = mysqli_fetch_all($sshowRecord_results, MYSQLI_ASSOC);
    }
}

?>

<html>


<head>
    <title>Student Records</title>
    <link rel="stylesheet" href="../css/view-details.css">
    <?php require 'include/Parent-Header.php'; ?>
</head>


<body>
    <div class="container">
        <?php if (empty($showR)): ?>
        <h2>No Record Found</h2>

        <h1><?php else:  ?>
            <h2>Student Record (Grade and Attendance)</h2>
            <button onclick="window.print()" class="btn btn-primary">Print Records</button>

            <ul style="padding: 0">
                <?php foreach ($showR as $Rec): ?>
                <li style="list-style-type: none">
                    <table class="info-table" border="1">
                        <tr>

                            <td><strong>Student Name:</strong></td>
                            <td><?=$Rec["stud_fname"] ?> <?=$Rec["stud_lname"] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Section:</strong></td>
                            <td><?=$Rec["section"] ?></td>
                        </tr>
                        <tr>
                            <td><strong>School Year:</strong></td>
                            <td><?=$Rec["school_year"] ?></td>
                        </tr>
                        <tr>
                            <td><strong>General Weighted Average:</strong></td>
                            <td><?php if (empty($Rec["GWA"])): ?> No GWA found
                                <?php else: echo $Rec["GWA"]?>
                                <?php endif; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total Number of Present:</strong></td>
                            <td><?php if (empty($Rec["t_present"])): ?> No Record found
                                <?php else: echo $Rec["t_present"]?>
                                <?php endif; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total Number of Absent:</strong></td>
                            <td><?php if (empty($Rec["t_absent"])): ?> No Record found
                                <?php else: echo $Rec["t_absent"]?>
                                <?php endif; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total Number of School Days:</strong></td>
                            <td><?php if (empty($Rec["t_SCDays"])): ?> No Record found
                                <?php else: echo $Rec["t_SCDays"]?>
                                <?php endif; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Action</strong></td>
                            <td><a class="submit-button"
                                    href="Parent-DetStudInfo.php?SR_ID=<?= $Rec["SR_ID"] ?>"><button>View
                                        Details</button></a></td>
                        </tr>
                    </table>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>
    </div>
</body>

</html>