<?php
include '../db/db.php';
session_start();

$user_id = $_SESSION['user_id'];
$conn->query("UPDATE notifications SET status = 'read' WHERE user_id = $user_id");
?>

