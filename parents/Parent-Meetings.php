<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];

    //Meetings Table
    $showMeetings = "SELECT * from meetings 
    JOIN preschool ON meetings.ps_id = preschool.ps_id
    JOIN users on meetings.user_id = users.user_id
    JOIN faculty on meetings.faculty_id = faculty.faculty_id
    WHERE meetings.user_id='".$user_id."'";
    $showMeetings_results = mysqli_query($conn, $showMeetings);

    // If there is a connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($showMeetings_results === false) {
        echo mysqli_error($conn);
    } else {
        $showM = mysqli_fetch_all($showMeetings_results, MYSQLI_ASSOC);
    }

}

?>
<html>
<title>Meetings Management</title>
<?php require 'include/Parent-Header.php'; ?>
<link rel="stylesheet" href="../css/meetings-management.css">

<?php if (empty($showM)): ?>
<div class="container">
    <h2 class="page-title">No Meeting Found</h2>
    <a href="Parent-SetMeet.php" class="approve-button" style="text-decoration:none">Set Meeting</a>

</div>
<?php else: ?>
<div class="container">
    <h2 class="page-title">List of Scheduled Meetings</h2>
    <a href="Parent-SetMeet.php" class="approve-button" style="text-decoration:none">Set Meeting</a>

    <ul class="meetings-list">
        <?php foreach ($showM as $meet): ?>
        <li class="meeting-item">
            <table class="meeting-table">
                <tr>
                    <td class="info-label">Topic:</td>
                    <td><?= $meet["meet_topic"] ?></td>
                </tr>
                <tr>
                    <td class="info-label">Description:</td>
                    <td><?= $meet["meet_desc"] ?></td>
                </tr>
                <tr>
                    <td class="info-label">Faculty Name:</td>
                    <td><?= $meet["f_fname"] ?> <?= $meet["f_lname"] ?></td>
                </tr>
                <tr>
                    <td class="info-label">When:</td>
                    <td><?= $meet["meeting_date"] ?> <?= $meet["meeting_time"] ?></td>
                </tr>
                <tr>
                    <td class="info-label">Meeting Status:</td>
                    <td><?= $meet["meetstatus"] ?></td>
                </tr>
                <tr>
                    <td class="info-label">Meeting Setup:</td>
                    <td><?= $meet["setup"] ?></td>
                </tr>
                <tr>
                    <td class="info-label">Zoom Link:</td>
                    <td>
                        <?php if ($meet["meetstatus"] == "Requested"): ?>
                        No Zoom link available at the moment.
                        <?php else: ?>
                        <a target="_blank" href="<?= $meet["ps_zoom"] ?>">Link</a>
                        <?php endif; ?>
                    </td>
                </tr>

                <td class="info-label">Action:</td>
                <td><a href="Parent-DelMeet.php?meetings_id=<?= $meet["meetings_id"] ?>">
                        <button class="delete-button">Delete Meeting</button></a>
                    <a href="Parent-EdMeet.php?meetings_id=<?= $meet["meetings_id"] ?>">
                        <button class="approve-button">Update Meeting Info</button></a>
                </td>
                </tr>
            </table>



            <?php endforeach; ?>
            <?php endif; ?><br>
            <p>
    </ul>

</html>