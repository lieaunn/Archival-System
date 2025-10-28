<?php
require '../db/db.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();


if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $token = bin2hex(random_bytes(32));

    // ✅ Check if passwords match
    if ($password !== $confirm) {
        $error = "Passwords do not match!";
    } else {
        // ✅ Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $otp = (string)rand(100000, 999999);
        // ✅ Insert into DB
        $sql = "INSERT INTO users (fullname, email, password, otp, verified) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $fullname, $email, $hashed_password, $otp, $verified);

        $verified = 0;

        if ($stmt->execute()) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'no0namenoreply@gmail.com';
            $mail->Password   = 'ltgi zpru enpi bawe'; // 16-char App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('yourgmail@gmail.com', 'Abstract Archival');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Email Verification OTP';
            $mail->Body    = "Hello <b>$fullname</b>,<br><br>
                              Your OTP code is: <h2>$otp</h2><br>
                              Please enter this code to verify your email.";

            $mail->send();
            $_SESSION['email'] = $email;
            header("Location: verify.php");
            exit;
        } catch (Exception $e) {
            echo "OTP Email could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Registration failed." . $stmt->error;
    }
            }
        }
        

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="index.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css' rel='stylesheet'>
</head>
<body>
        <div class="regi-form">
        <div class="header">
            <h2> Register </h2>
        </div>

        <div class="reg-form">
            <form method="POST" action="register.php">
            <div class="input-field">
                <label for="fullname"><i class="fa-solid fa-user"></i> Full Name </label>
                <input type="text" id="fullname" name="fullname"
                pattern="[A-Za-z] +" title="Only letters and spaces are allowed" required>
            </div>

            <div class="input-field">
                <label for="email"><i class="fa-solid fa-envelope"></i> Email </label>
                <input type="text" name="email" required>
            </div>

            <div class="input-password">
                <label for="password"><i class="fa-solid fa-lock"></i> Password </label>
                <input type="password" name="password" id="password" required>
                <i class="fa-solid fa-eye toggle-password" toggle="#password"></i>
            </div>

            <div class="input-password">
                <label for="cpassword"><i class="fa-solid fa-lock"></i> Confirm Password </label>
                <input type="password" name="confirm" id="confirm-password" required>
                <i class="fa-solid fa-eye toggle-password" toggle="#confirm-password"></i>
            </div>

            <button class="log-btn" type="submit" name="register"> Register </button>

            <div class="p">
                <p> Already have an account? <a href="login.php"> Login here. </a> </p>
            </div>
            </form>
        </div>
    </div>
    
    <footer class="footer">
        <button class="open-modal-btn" onclick="openModal()">
            <i class="fa-solid fa-circle-info"></i><b> About Us</b></button>
        <p><b> Copyright&copy;2026. All Rights Reserved Aemilianum College Inc. </b></p>
   </footer>

   <div id="capstoneModal" class="modal-overlay">
  <div class="modal-content">
    <h2 style="margin-bottom: 15px; text-align: center;">Capstone Information</h2>

    <p><strong>Capstone Title:</strong> Digital Documentation and Archival System</p>

    <p><strong>Group Leader:</strong> Pauline Anne E. Patosa - <span>Researcher/System Developer</span></p>

    <p><strong>Member:</strong> Maria Isabelle D. Francisco - <span>Researcher</span></p>

    <p><strong>Adviser:</strong> Ms. Laila L. Delito - IT Instructor</p>

    <p><strong>Panelists:</strong></p>
    <ul>
      <li>Edlyn S. De La Cruz, LPT - IT Instructor</li>
      <li>Marilyn D. Berdin, MPA - Assistant Director for Administration</li>
      <li>Josefina R. Sarmiento, MIT, PhD - Dean / Research and Extension Coordinator</li>
      <li>Rev. Fr. Mandee N. Batac, CRS - Assistant Director for Finance </li>
    </ul>

    <p class="copyright">Copyright 2025</p>

    <button class="close-btn" onclick="closeModal()">Close</button>
  </div>
</div>

   <script>
    const toggleIcons = document.querySelectorAll('.toggle-password');

    toggleIcons.forEach(icon => {
    icon.addEventListener('click', function () {
      const input = document.querySelector(this.getAttribute('toggle'));
      if (input.type === "password") {
        input.type = "text";
        this.classList.remove("fa-eye");
        this.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        this.classList.remove("fa-eye-slash");
        this.classList.add("fa-eye");
      }
    });
  });

    document.getElementById('fullname').addEventListener('input', function () {
    this.value = this.value.replace(/[^A-Za-z ]/g, ''); 
  });

  function openModal() {
  document.getElementById("capstoneModal").style.display = "block";
  }

  function closeModal() {
  document.getElementById("capstoneModal").style.display = "none";
  }
    // Close when clicking outside the popup
    window.onclick = function(event) {
      let overlay = document.getElementById("capstoneModal");
      if (event.target === overlay) {
        closePopup();
      }
    }
</script>
</body>
</html>