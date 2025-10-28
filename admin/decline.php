<?php
include '../db/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $thesis_id = intval($_POST['id']);
    $reason = trim($_POST['reason'] ?? 'No reason provided.');

    // 1️⃣ Update abstract status to Rejected
    $update = $conn->prepare("UPDATE abstracts SET status = 'Rejected' WHERE id = ?");
    if (!$update) {
        die("Prepare failed (update): " . htmlspecialchars($conn->error));
    }
    $update->bind_param("i", $thesis_id);

    if ($update->execute()) {

        // 2️⃣ Get uploader user_id + title for notification message
        $stmt = $conn->prepare("SELECT user_id, title FROM abstracts WHERE id = ?");
        if (!$stmt) {
            die("Prepare failed (select): " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("i", $thesis_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $uploader_id = $row['user_id'];
            $title = $row['title'];

            // 3️⃣ Create disapproval message
            $msg = "Your abstract titled '{$title}' was disapproved. Reason: {$reason}. Please revise and resubmit.";

            // 4️⃣ Insert notification
            $notif = $conn->prepare("
                INSERT INTO notifications (user_id, message, status, created_at)
                VALUES (?, ?, 'unread', NOW())
            ");
            if (!$notif) {
                die("Notification prepare failed: " . htmlspecialchars($conn->error));
            }

            $notif->bind_param("is", $uploader_id, $msg);

            if ($notif->execute()) {
                echo "<script>
                        alert('Abstract disapproved and user notified successfully!');
                        window.location='dashboard.php#pending';
                      </script>";
            } else {
                echo "Error inserting notification: " . htmlspecialchars($notif->error);
            }
            $notif->close();
        } else {
            echo "No user found for that abstract ID.";
        }
        $stmt->close();
    } else {
        echo "Error executing update: " . htmlspecialchars($update->error);
    }

    $update->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
