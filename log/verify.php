<?php
require '../db/db.php';
session_start();

if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];
    $email = $_SESSION['email'];

    $sql = "SELECT * FROM users WHERE email = ? AND otp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update = "UPDATE users SET verified = 1, otp = NULL WHERE email = ?";
        $stmt2 = $conn->prepare($update);
        $stmt2->bind_param("s", $email);
        $stmt2->execute();

        echo "<script>
                alert('Email verified successfully! Please login.');
                window.location.href = 'login.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Email Verification</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: #335c92ff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .verify-container {
      background: #fff;
      width: 400px;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
    }

    .verify-container h2 {
      margin-bottom: 15px;
      color: #333;
    }

    .verify-container p {
      font-size: 14px;
      color: #666;
      margin-bottom: 25px;
    }

    .otp-input {
      letter-spacing: 10px;
      font-size: 22px;
      padding: 10px;
      text-align: center;
      width: 200px;
      border: 2px solid #ddd;
      border-radius: 10px;
      outline: none;
      margin-bottom: 20px;
      transition: border-color 0.3s;
    }

    .otp-input:focus {
      border-color: #4e73df;
    }

    button {
      background: #4e73df;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #3757c9;
    }

  </style>
</head>
<body>
  <div class="verify-container">
    <i class="fa-solid fa-envelope-circle-check fa-3x" style="color:#4e73df; margin-bottom:15px;"></i>
    <h2>Email Verification</h2>
    <p>We sent a 6-digit OTP to your email. Please enter it below to verify your account.</p>
    
    <form method="post">
      <input type="text" name="otp" class="otp-input" maxlength="6" placeholder="------" required>
      <br>
      <button type="submit" name="verify"><i class="fa-solid fa-check"></i> Verify</button>
    </form>
  </div>
</body>
</html>
