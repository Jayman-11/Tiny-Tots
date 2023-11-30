<?php

session_start();
if (isset($_SESSION['sca_id']) && isset($_SESSION['sca_username']))
{
    $sca_id = $_SESSION["sca_id"];
    require '../include/Connection.php';
    $conn = getDB();

    $sca = "SELECT * from scadmins where sca_id ='".$sca_id."'";
    $query = mysqli_query($conn, $sca);
    $results = mysqli_fetch_assoc($query);
    $sca_ps = $results['ps_id'];

     // Display all the preschools that the parents can select to message
     $PSList = "SELECT * FROM preschool WHERE ps_id = $sca_ps";
     $PSList_res = mysqli_query($conn, $PSList);
 
     // If there is a connection error, then echo the description of the  error
     // Else, store the results on a variable using mysqli_fetch_all
     if ($PSList_res === false) {
         echo mysqli_error($conn);
     } else {
         $showPSList = mysqli_fetch_all($PSList_res, MYSQLI_ASSOC);
     }

     // messages table
     $msg_sql = "SELECT messages.ps_id, infotext,  CONCAT(first_name, ' ', last_name) AS full_name,
                convo_at, message_id
                FROM messages
                JOIN users ON messages.user_id = users.user_id
                UNION SELECT messages.ps_id, infotext, CONCAT(sca_first_name, ' ', sca_last_name) AS sca_full_name, convo_at, message_id
                FROM messages
                JOIN scadmins ON messages.sca_id = scadmins.sca_id
                WHERE messages.ps_id = $sca_ps
                ORDER BY convo_at";

        $msg_sql_res = mysqli_query($conn, $msg_sql);

        // If there is an connection error, then echo the description of the  error
        // Else, store the results on a variable using mysqli_fetch_all
        if ($msg_sql_res === false) {
        echo mysqli_error($conn);
        } else {
        $msg = mysqli_fetch_all($msg_sql_res, MYSQLI_ASSOC);
        }

        
    // Add message to the message table (scadmins)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST["infotext"] == "") {
            $errors[] = "Content is required";
        }

        if (empty($errors)) {

            $sql = "INSERT INTO messages (infotext, sca_id, ps_id )
                VALUES(?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt === false) {
                echo mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param(
                    $stmt,
                    "sii",
                    $_POST["infotext"],
                    $sca_id,
                    $sca_ps

                );
                if (mysqli_stmt_execute($stmt)) {
                    $id = mysqli_insert_id($conn);
                    echo "<script>window.location.href='SCA-Messages.php';</script>";
                } else {
                    echo mysqli_stmt_error($stmt);
                }
            }
        }
    }
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Message with Parents</title>
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

    .message-container {
        max-width: 400px;
        margin: 20px auto;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .message {
        padding: 8px 12px;
        margin-bottom: 8px;
        border-radius: 8px;
        max-width: 70%;
    }

    /* Sender message style */
    .sender {
        background-color: #DCF8C6;
        align-self: flex-end;
    }
    </style>
</head>

<body>
    <div class="container">
        <?php if (empty($msg)): ?>
        <h2>No Message Found</h2>

        <?php else: ?>
        <h2>Messages</h2>

        <div class="message-container">
            <?php foreach ($showPSList as $PSL): ?>
            <?php foreach ($msg as $msg_text): ?>

            <?php if ($PSL["ps_id"] == $msg_text["ps_id"]): ?>
            <div class="sender">
                <h3><?= $msg_text["full_name"] ?> </h3>
                <p><?= htmlspecialchars($msg_text["infotext"]) ?></p>
                <p><?= $msg_text["convo_at"] ?></p>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>

            <?php endforeach; ?>
            <form method="post">
                <div>
                    <input type="text" name="infotext" placeholder="Enter your message..">
                    <input type="submit" value="send"></button>
                </div>
                <input type="hidden" name="ps_id" value="<?= $msg_text["ps_id"] ?>" />
            </form>
            <?php endif; ?>
        </div>
</body>

</html>