<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../db/db.php");

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../log/index.php");
    exit();
}

$current_email = $_SESSION['email'];

/* ============================
   Update Fullname + Email
   ============================ */
if (isset($_POST['update_profile'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email is already taken by another user
    $check = "SELECT user_id FROM users WHERE email='$new_email' AND email <> '$current_email' LIMIT 1";
    $check_result = mysqli_query($conn, $check);

    if (!$check_result) {
        die("SQL Error (check email): " . mysqli_error($conn));
    }

    if (mysqli_num_rows($check_result) > 0) {
        die("❌ This email is already in use.");
    }

    // Update user
    $query = "UPDATE users SET fullname='$fullname', email='$new_email' WHERE email='$current_email'";
    if (mysqli_query($conn, $query)) {
        // ✅ Refresh session so sidebar updates instantly
        $_SESSION['name'] = $fullname;
        $_SESSION['email'] = $new_email;

        header("Location: user_dash.php?success=profile");
        exit();
    } else {
        die("Error updating profile: " . mysqli_error($conn));
    }
}

/* ============================
   Update Password
   ============================ */
if (isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password !== $confirm_password) {
        die("❌ New password and confirm password do not match.");
    }

    // Fetch current password from DB
    $query = "SELECT password FROM users WHERE email='$current_email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("SQL Error (select password): " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);

    // Verify current password
    if (!password_verify($current_password, $row['password'])) {
        die("❌ Current password is incorrect.");
    }

    // Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update DB
    $update = "UPDATE users SET password='$hashed_password' WHERE email='$current_email'";
    if (mysqli_query($conn, $update)) {
        header("Location: user_dash.php?success=password");
        exit();
    } else {
        die("Error updating password: " . mysqli_error($conn));
    }
}
?>
