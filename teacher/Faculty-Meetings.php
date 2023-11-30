<?php
session_start();
if (isset($_SESSION['faculty_id']) && isset($_SESSION['f_username'])) {
    require '../include/Connection.php';
    $conn = getDB();
    $faculty_id = $_SESSION["faculty_id"];

    // Meetings Table
    $showMeetings = "SELECT * from meetings 
    JOIN preschool ON meetings.ps_id = preschool.ps_id
    JOIN users on meetings.user_id = users.user_id
    JOIN faculty on meetings.faculty_id = faculty.faculty_id
    WHERE meetings.faculty_id='".$faculty_id."'";
    $showMeetings_results = mysqli_query($conn, $showMeetings);

    // If there is a connection error, then echo the description of the error
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
<?php require 'include/Faculty-Header.php'; ?>
<link rel="stylesheet" href="../css/meetings-management.css">

<?php if (empty($showM)): ?>
<div class="container">
    <h2 class="page-title">No Meeting Request Found</h2>
    <a href="Faculty-SetMeeting.php"><button style="text-decoration:none" class="approve-button">Set Meeting</a>
    </button>
</div>
<?php else: ?>
<div class="container">
    <h2 class="page-title">List of Scheduled Meetings</h2>
    <a href="Faculty-SetMeeting.php" style="text-decoration:none" class="approve-button">Set Meeting</a>

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

            </table>
            <div colspan="2" class="action-buttons">
                <a href="FacultyEdMeet.php?meetings_id=<?= $meet["meetings_id"] ?>" style="text-decoration:none" class="approve-button">
                    Approve or Edit Request
                </a>
                <a href="Faculty-DelMeet.php?meetings_id=<?= $meet["meetings_id"] ?>" style="text-decoration:none" class="delete-button">
                    Delete Meeting
                </a>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

</html>