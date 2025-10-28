<?php
session_start();
include '../db/db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1) {
        $users = $result->fetch_assoc();

        if(password_verify($password, $users['password'])) {
            $_SESSION['user_id'] = $users['user_id'];
            $_SESSION['fullname'] = $users['fullname'];
            $_SESSION['email'] = $users['email'];
            echo "<script>
                sessionStorage.setItem('showLoginPopup', 'true');
                window.location.href = '/archive/user/user_dash.php';
            </script>";

            exit();
        } else {
            $error = "Invalid password";
        } 
    } else {
        $error = "User not found";
    } 
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="index.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css' rel='stylesheet'>
</head>
<body>
    <div class="login-form">
        <div class="header">
            <h2> Login </h2>
        </div>

        <div class="log-form">
            <form method="POST">
            <div class="input-field">
                <label for="email"><i class="fa-solid fa-envelope"></i> Email </label>
                <input type="text" name="email" required>
            </div>

            <div class="input-password">   
                <label for="password"> <i class="fa-solid fa-lock"></i> Password </label>
                <input type="password" id="password" name="password" required> 
                <i class="fa-solid fa-eye toggle-password" id="toggle"></i>
            </div>
            

            <button class="log-btn" type="submit" name="login"> Login </button>

            <div class="p">
                <p> Don't have an account? <a href="register.php"> Register here. </a> </p>
            </div>
            </form>
            <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        </div>

    </div>

    <footer class="footer">
        <button class="open-modal-btn" onclick="openModal()">
            <i class="fa-solid fa-circle-info"></i><b> About Us</b></button>
        <p><b> Copyright&copy;2026. All Rights Reserved Aemilianum College Inc. </b>></p>
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
        const password = document.getElementById("password");
        const toggle = document.getElementById("toggle");

        toggle.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

      // switch between eye and eye-slash
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
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