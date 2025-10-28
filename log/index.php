<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thesis Archive</title>
    <link rel="stylesheet" href="index.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css' rel='stylesheet'>
</head>
<body>

    <div class="container">
        <div class="pic"> 
            <h1> Digital Research <br> Documentation <br>and Archival System </h1>
                <img src="aci logo.jpeg" class="logo" alt="Logo"> 
            
        </div>

            <p style="margin-top:-40px; margin-left: 10px; font-size: 18px;"><i> "Navigate your thesis journey with ease"</i> </p>
    
            <button type="submit" class="user-btn" onclick="window.location.href='login.php'"><i class="fa-solid fa-arrow-right-to-bracket"></i> Login </button> 

            <button type="submit" class="reg-btn" onclick="window.location.href='register.php'"><i class="fa-solid fa-user-plus"></i>  Register </button>
             
            <button type="submit" class="admin-btn" onclick="window.location.href='admin.php'"><i class="fa-solid fa-sign-in-alt"></i> Admin </button>
    </div>

   <footer class="footer">
        <button class="open-modal-btn" onclick="openModal()">
            <i class="fa-solid fa-circle-info"></i><b> About Us</b></button>
        <p><b> Copyright&copy;2026. All Rights Reserved Aemilianum College Inc. </b></p>
   </footer>

   <div id="capstoneModal" class="modal-overlay">
  <div class="modal-content">
    <h2 style="margin-bottom: 15px; text-align: center;">Capstone Information</h2>

    <p><strong>Capstone Title:</strong> Digital Research Documentation and Archival System for Aemilianum College Incorporated</p>

    <p><strong>Group Leader:</strong> Pauline Anne E. Patosa - <span>Researcher / System Developer</span></p>

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