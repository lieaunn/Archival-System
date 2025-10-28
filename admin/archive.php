<?php
include '../db/db.php'; // adjust path if needed

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "UPDATE abstracts SET status='Archived' WHERE id='$id'";
    mysqli_query($conn, $query);
}

header("Location: ../admin/dashboard.php");
exit();
?>
