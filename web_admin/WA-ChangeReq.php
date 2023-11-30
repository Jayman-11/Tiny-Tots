<?php
session_start();
if (isset($_SESSION['wba_id']) && isset($_SESSION['wba_username'])) {
    require '../include/Connection.php';
    $conn = getDB();

    $wba_id = $_SESSION["wba_id"];

    $info_sql = "SELECT * FROM webadmins
                WHERE wba_id='".$wba_id."'";

    $info_results = mysqli_query($conn, $info_sql);

    if ($info_results === false) {
        echo mysqli_error($conn);
    } else {
        $info= mysqli_fetch_all($info_results, MYSQLI_ASSOC);
    }

    $preschoolQuery = "SELECT * FROM preschool WHERE status ='Pending';";
    $preschoolResult = $conn->query($preschoolQuery);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preschool Management</title>
    <link rel="stylesheet" href="../css/view-details.css">
</head>

<body>

    <?php require 'include/WA-Header.php'; ?>
    <div class="container">
        <h2>Pending Preschool</h2>
        <table class="info-table">
            <thead>
                <tr>
                    <th>School Name</th>
                    <th>Action</th>
                </tr>
            </thead>

            <?php while ($preschoolRow = $preschoolResult->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $preschoolRow['school_name']; ?></td>
                <td>
                    <a style="text-decoration:none" href="WA-UpdPS.php?ps_id=<?= $preschoolRow["ps_id"] ?>">
                        <button>View Details</button>
                    </a>

                    <a  style="text-decoration:none" href="WA-DelPSR.php?ps_id=<?= $preschoolRow["ps_id"] ?>">
                         <button>Delete</button>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>