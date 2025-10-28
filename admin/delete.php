<?php
include '../db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete query
    $sql = "DELETE FROM abstracts WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect back after deletion
        header("Location: dashboard.php#manage");
        exit;
    } else {
        echo "Error deleting record.";
    }
} else {
    echo "Invalid request.";
}
?>
