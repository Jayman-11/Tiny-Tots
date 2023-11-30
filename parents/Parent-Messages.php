<?php

session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']))
{
    require '../include/Connection.php';
    $conn = getDB();
    $user_id = $_SESSION["user_id"];    

    // Display all the preschools that the parents can select to message
    $PSList = "SELECT * FROM preschool";
    $PSList_res = mysqli_query($conn, $PSList);

    // If there is a connection error, then echo the description of the  error
    // Else, store the results on a variable using mysqli_fetch_all
    if ($PSList_res === false) {
        echo mysqli_error($conn);
    } else {
        $showPSList = mysqli_fetch_all($PSList_res, MYSQLI_ASSOC);
    }

    // messages table
        $msg_sql = "SELECT messages.ps_id, infotext,  
        CONCAT(first_name, ' ', last_name) AS full_name,
        convo_at, message_id, messages.user_id
        FROM messages
        JOIN users ON messages.user_id = users.user_id
        UNION SELECT messages.ps_id, infotext, 
        CONCAT(sca_first_name, ' ', sca_last_name) AS sca_full_name, 
        convo_at, message_id, messages.sca_id
        FROM messages
        JOIN scadmins ON messages.sca_id = scadmins.sca_id
        WHERE messages.user_id = $user_id
        ORDER BY convo_at";

        $msg_sql_res = mysqli_query($conn, $msg_sql);

        // If there is an connection error, then echo the description of the  error
        // Else, store the results on a variable using mysqli_fetch_all
        if ($msg_sql_res === false) {
        echo mysqli_error($conn);
        } else {
        $msg = mysqli_fetch_all($msg_sql_res, MYSQLI_ASSOC);
        }

    // Add message to the message table (parent)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST["infotext"] == "") {
            $errors[] = "Content is required";
        }

        if (empty($errors)) {
            $sql = "INSERT INTO messages (infotext, ps_id, user_id)
                VALUES(?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt === false) {
                echo mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param(
                    $stmt,
                    "sii",
                    $_POST["infotext"],
                    $_POST["ps_id"],
                    $user_id

                );
                if (mysqli_stmt_execute($stmt)) {
                    $id = mysqli_insert_id($conn);
                    echo "<script>window.location.href='Parent-Messages.php';</script>";
                } else {
                    echo mysqli_stmt_error($stmt);
                }
            }
        }
    }
    }

?>
<html>
<title>Message with Preschools</title>
<?php require 'include/Parent-Header.php'; ?>
<h2>Preschools</h2>

<ul>

    <?php foreach ($showPSList as $PSL): ?>
    <li>
        <div>
            <p><?= $PSL["school_name"] ?></p>
        </div>
    </li>
    <?php foreach ($msg as $msg_text): ?>
    <?php if ($PSL["ps_id"] == $msg_text["ps_id"] 
            AND $user_id == $msg_text["user_id"]): ?>
    <li>
        <p><?= $msg_text["full_name"] ?></p>
        <p><?= htmlspecialchars($msg_text["infotext"]) ?></p>
        <p><?= $msg_text["convo_at"] ?></p>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>

    <form method="post">
        <div>
            <input type="text" name="infotext" placeholder="Enter your message.."></textarea><button>Add</button>
        </div>
        <input type="hidden" name="ps_id" value="<?= $PSL["ps_id"] ?>" />
    </form>

    <?php endforeach; ?>
</ul>

</html>