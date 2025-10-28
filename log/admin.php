<?php
session_start();
include '../db/db.php';

if (isset($_POST['admin_log'])) {
    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ? LIMIT 1");
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // If you are storing plain text passwords (not recommended)
        if ($row['password'] === $password) {
            $_SESSION['admin_log'] = $admin_id;
            header("Location: /archive/admin/dashboard.php");
            exit;
        }

        // If you are storing hashed passwords (recommended)
        /*
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_loggedin'] = $admin_id;
            header("Location: dashboard.php");
            exit;
        }
        */
    }

    // If login fails
    echo "<script>alert('Invalid admin ID or password');</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Thesis Archive</title>
    <link rel="stylesheet" href="index.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css' rel='stylesheet'>
</head>
<body>
     <div class="ad-form">
        <div class="header">
            <h2> Admin Login </h2>
        </div>

        <div class="admin-form">
            <form method="POST" action="">
            <div class="input-field">
                <label for="username"><i class="fa-solid fa-id-card"></i> Admin ID </label>
                <input type="text" name="admin_id" required>
            </div>

            <div class="input-password">
                <label for="password"><i class="fa-solid fa-lock"></i> Password </label>
                <input type="password" name="password" id="password" required>
                <i class="fa-solid fa-eye toggle-password" id="toggle"></i>
            </div>

            <button class="log-btn" name="admin_log" type="submit"> Login </button>
            </form>
            <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
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