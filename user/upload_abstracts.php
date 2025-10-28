<?php
include '../db/db.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['upload'])) {
    $user_id = $_SESSION['user_id'];
    $title      = $_POST['title'];
    $author     = $_POST['author'];
    $department = $_POST['department'];
    $year       = $_POST['year'];
    $fullname   = $_SESSION['fullname']; // must be set at login
    $file     = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $target   = "uploads/" . basename($file);

    // ✅ generate file hash for duplicate checking
    $fileHash = md5_file($tmp_name);

    // ✅ check in database if same file already exists
    $check = $conn->prepare("SELECT id FROM abstracts WHERE pdf_hash = ?");
    $check->bind_param("s", $fileHash);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('❌ This PDF has already been uploaded!'); window.location='user_dash.php';</script>";
        exit;
    }

    // ✅ move file only if not duplicate
    if (move_uploaded_file($tmp_name, $target)) {
        $original_name = $file;

        $sql = "INSERT INTO abstracts (user_id, fullname, title, author, department, year, file, original_name, pdf_hash, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending Review')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssss", $user_id, $fullname, $title, $author, $department, $year, $target, $original_name, $fileHash);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Abstract submitted! Please wait for 7 working days for approval.'); window.location='user_dash.php';</script>";
        } else {
            echo "Database Error: " . $stmt->error;
        }
    } else {
        echo "Error uploading file.";
    }
}
?>
