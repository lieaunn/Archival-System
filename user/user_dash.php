<?php
session_start();
include "../db/db.php";

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

   
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    die("User not logged in.");
}

// ✅ 1. COUNT UNREAD NOTIFICATIONS
$count_sql = "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND status = 'unread'";
$count_stmt = $conn->prepare($count_sql);
if (!$count_stmt) {
    die("Count query failed: " . htmlspecialchars($conn->error));
}
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_stmt->bind_result($notif_count);
$count_stmt->fetch();
$count_stmt->close();

// ✅ 2. FETCH RECENT NOTIFICATIONS (both Approved & Disapproved)
$sql = "
    SELECT id, message, status, created_at 
    FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 10
";
$notif_stmt = $conn->prepare($sql);
if (!$notif_stmt) {
    die("Notification query failed: " . htmlspecialchars($conn->error));
}
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notifications = $notif_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$notif_stmt->close();

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';
$dept = $_GET['department'] ?? '';

$sql = "SELECT id, title, author, year, department, file 
        FROM abstracts 
        WHERE status = 'Approved'";
$params = [];
$types = "";



if(!empty($search)) {
    $sql .= " AND (title LIKE ? OR author LIKE ?)";
    $types .= "ss";
    $like = "%" . $search . "%";
    $params[] = $like;
    $params[] = $like;
}

if (!empty($dept)) {
    $sql .= " AND department = ?";
    $types .= "s";
    $params[] = $dept;
}

if ($sort === 'az') {
    $sql .= " ORDER BY title ASC";
} elseif ($sort === 'za') {
    $sql .= " ORDER BY title DESC";
} else {
    $sql .= " ORDER BY year DESC";
}

$stmt3 = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt3, $types, ...$params);
}

mysqli_stmt_execute($stmt3);
$result = mysqli_stmt_get_result($stmt3);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="user.css">
</head>
<body>
     <aside class="sidebar">
        <h2>Welcome,  <?php echo $_SESSION['fullname']; ?> ! </h2>
        
            <ul id="menu">
                <li class="active" onclick="showContent('library')"><i class="fa-solid fa-bars"></i> Library</li>
                <li onclick="showContent('upload')"><i class="fa-solid fa-upload"></i> Upload Abstracts</li>
                <li onclick="showContent('setting')"><i class="fa-solid fa-gear"></i> Account Settings</li>
                <li onclick="window.location.href='logout.php'"><i class="fa-solid fa-right-from-bracket"></i> Logout </li>
            </ul>
    </aside>
    
    
    <div class="main-content">

       <div id="library" class="content-section" style="display: block;">
        
      <div class="notification-container">
  <div class="notification-bell" id="notifBell">
    <i class="fa fa-bell"></i>
    <?php if ($notif_count > 0): ?>
      <span class="badge"><?= htmlspecialchars($notif_count) ?></span>
    <?php endif; ?>
  </div>

  <div class="notification-dropdown" id="notifDropdown">
    <h4>Notifications</h4>
    <ul>
      <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $n): ?>
          <li class="<?= $n['status'] === 'unread' ? 'unread' : '' ?>">
            <?= htmlspecialchars($n['message']) ?><br>
            <small><?= htmlspecialchars($n['created_at']) ?></small>
          </li>
        <?php endforeach; ?>
      <?php else: ?>
        <li class="empty">No notifications yet.</li>
      <?php endif; ?>
    </ul>
  </div>
</div>


            <h1 style="text-align: center;">Library</h1>

            <form method="GET" id="searchForm">
                <h3 class="h3"> Search abstracts here </h3>
                <div class="search-section">
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" id="searchInput" name="search" placeholder="Enter title, or author..."
                        value="<?php htmlspecialchars($search ?? ''); ?>">
                    </div>

                    <div class="filter-container">
                        <button type="button" id="filterBtn" class="btn-filter"> Filter </button>
                            <div id="filterDropdown" class="filter-dropdown">
                                <div class="filter-group">
                                    <label for=""> Sort </label>
                                    <select name="sort" id="sortOption">
                                        <option value=""> Deafult </option>
                                        <option value="az" <?= $sort === 'az' ? 'selected' : '' ?> > A-Z </option>
                                        <option value="za" <?= $sort === 'za' ? 'selected' : '' ?> > Z-A </option>
                                    </select>
                                </div>
                                
                                <div class="filter-group">
                                    <label for=""> Department </label>
                                    <select name="department" id="deptOption">
                                        <option value=""> All </option>
                                        <option value="BEED" <?= $dept === 'BEED' ? 'selected' : '' ?> > BEED </option>
                                        <option value="BSED-English" <?= $dept === 'BSED-English' ? 'selected' : '' ?>> BSED- English </option>
                                        <option value="BSED-SocStud" <?= $dept === 'BSED-SocStud' ? 'selected' : '' ?> > BSED-SocStud </option>
                                        <option value="BSOA" <?= $dept === 'BSOA' ? 'selected' : '' ?> > BSOA </option>
                                        <option value="BSECE" <?= $dept === 'BSECE' ? 'selected' : '' ?> > BSECE </option>
                                        <option value="BSIT" <?= $dept === 'BSIT' ? 'selected' : '' ?> > BSIT </option>
                                        <option value="ABCOMM" <?= $dept === 'ABCOMM' ? 'selected' : '' ?> > ABCOMM </option>
                                    </select></thead>
                                </div>
                                <button type="submit" class="apply-filter" id="applyBtn"> Apply Filter</button>
                            </div>
                    </div>
                </div>
            </form>
  
                    <div class="table-container">
                    <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th style="width:150px">Author</th>
                            <th style="width:90px">Department</th>
                            <th style="width:70px">Year</th>
                            <th style="width:80px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                    echo "<td>";
                    if (!empty($row['file'])) {
                        $filePath = 'http://localhost/archive/user/uploads/' . basename($row['file']);
                        echo '<a href="/archive/web/viewer.html?file=' . $filePath . '" target="_blank"><button class="btn-view" style="background: #3498db; border: none; padding: 10px;
                    border-radius: 6px; cursor: pointer; color: white; margin-left: 10px;"> View</button></a>';
                    } else {
                        echo '<span style="color:red;">No file uploaded</span>';
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No approved abstracts found.</td></tr>";
            }

            // Close statements and connection
            
            ?>
                    </tbody>
                    </table>   
            </div>
        </div>

        <div id="upload" class="content-section" style="display: none;">
            <div class="upload-section">
                <form action="upload_abstracts.php" method="POST" enctype="multipart/form-data">
                        <div class="upload-sec">
                            <h1>Upload Abstract</h1>      
                            <div class="input-group">
                                <label>Title</label>
                                <input type="text" id="title" name="title" placeholder="Enter thesis title" required>
                            </div>
                    
                            <div class="input-group">
                                <label>Author</label>
                                <input type="text" id="author" name="author" placeholder="Enter author name" required>
                            </div>

                            <div class="input-group">
                                <label>Department</label>
                                <select id="department" name="department" required>
                                    <option value="">-- Select Department --</option>
                                    <option value="BSED-English">BS Education Major in English </option>
                                    <option value="BSED-SocStud">BS Education Major in Social Studies </option>
                                    <option value="BEED">Bachelor of Elementary Education</option>
                                    <option value="BSOA">BS Office Administration </option>
                                    <option value="BSIT"> BS Information Technology</option>
                                    <option value="BSECE">BS Electronics Engineering</option>
                                    <option value="ABCOMM"> AB Communication </option>
                                </select>
                            </div>
                    
                            <div class="input-group">
                                <label> Published Year</label>
                                <input type="number" name="year" placeholder="e.g. 2026" required>
                            </div>

                            <div class="input-group">
                                <label>Upload File (PDF)</label>
                                <input type="file" name="file" accept="application/pdf" required>
                            </div>
                    
                            <button type="submit" name="upload" class="btn-upload">Upload</button>
                        </div>  
                </form>
                    
            </div>
        </div>

        <div id="setting" class="content-section" style="display: none;">
            
                    <h1>Account Settings</h1>
                <div class="settings-content">
                    <div class="tab-header">
                        <h3>Settings Menu</h3>
                        <button class="tab-link active" onclick="openTab(event, 'profile')">Personal Information</button>
                        <button class="tab-link" onclick="openTab(event, 'security')">Security</button>
                    </div>

                        <div class="tab-content active" id="profile">
                            <form method="POST" action="update_account.php">
                          
                                <h3>Update Personal Information</h3>
                                <div class="form-group">
                                    <label for="fullname">Full Name</label>
                                    <input type="text" id="fullname" name="fullname" placeholder="Enter new full name" required>
                                </div>

                                <div class="form-group">
                                    <label for="fullname">Email</label>
                                    <input type="email" id="email" name="email" placeholder="Enter new email" required>
                                </div>

                                <button type="submit" name="update_profile" class="btn-save">Save Changes</button>
                            </form>
                        </div>

                        <div class="tab-content" id="security">
                            <form method="POST" action="update_account.php">
                                
                                <h3>Update Your Password</h3>
                                <div class="form-group">
                                    <label for="password">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" placeholder="Enter your current password" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Confirm Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                                </div>

                                <button type="submit" name="update_password" class="btn-save">Update Password</button>
                            </form>
                        </div>
                </div>
        </div>
    
    </div>

    <div id="loginPopup" class="popup-overlay">
        <div class="popup-content">
            <h2>Welcome back!</h2>
            <p>You have successfully logged in.</p>
            <button id="closePopup">OK</button>
        </div>
    </div>
        
   <script>
    function showContent(sectionId) {
  // Hide all sections
    document.querySelectorAll('.content-section').forEach(sec => {
    sec.style.display = 'none';
    });

  // Show selected section
    document.getElementById(sectionId).style.display = 'block';

  // Update active sidebar
    document.querySelectorAll('.sidebar li').forEach(li => {
    li.classList.remove('active');
    });
    event.target.classList.add('active');
}

 function openTab(evt, tabId) {
  // Hide all tab contents
  const contents = document.querySelectorAll('.tab-content');
  contents.forEach(c => c.classList.remove('active'));

  // Remove active state from all buttons
  const buttons = document.querySelectorAll('.tab-link');
  buttons.forEach(b => b.classList.remove('active'));

  // Show the clicked tab
  document.getElementById(tabId).classList.add('active');
  evt.currentTarget.classList.add('active');
}

        function toggleFilter() {
      const menu = document.getElementById("filterMenu");
      menu.style.display = menu.style.display === "block" ? "none" : "block";
    }

    function applySearch() {
      const query = document.getElementById("searchInput").value.toLowerCase();
      const rows = document.querySelectorAll(".library tbody tr");

      rows.forEach(row => {
        const title = row.cells[0].textContent.toLowerCase();
        const desc = row.cells[4].textContent.toLowerCase();
        row.style.display = (title.includes(query) || desc.includes(query)) ? "" : "none";
      });
    }

    function applyFilter() {
      const type = document.getElementById("sort").value;
      const status = document.getElementById("department").value;
      const rows = document.querySelectorAll(".library tbody tr");

      rows.forEach(row => {
        const matchSort = (sort === "All" || row.dataset.type === sort);
        const matchDep = (department === "All" || row.dataset.status === department);

        row.style.display = (matchSort && matchDep) ? "" : "none";
      });

      toggleFilter();
    }

    // Close dropdown if clicking outside
    document.addEventListener("click", function(event) {
      const menu = document.getElementById("filterMenu");
      const button = document.querySelector(".filter-btn");
      if (!menu.contains(event.target) && !button.contains(event.target)) {
        menu.style.display = "none";
      }
    });

document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('searchInput');
    const filterBtn = document.getElementById('filterBtn');
    const filterDropdown = document.getElementById('filterDropdown');
    const sortOption = document.getElementById('sortOption');
    const deptOption = document.getElementById('deptOption');
    const applyFilter = document.getElementById('applyFilter');
    const searchForm = document.getElementById('searchForm');

    filterBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        filterDropdown.classList.toggle('active');
    });

    filterDropdown.addEventListener('click', (e) => e.stopPropagation());

    document.addEventListener('click', () => {
        filterDropdown.classList.remove('active');
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            searchForm.submit();
        }
    });

    sortOption.addEventListener('change', () => form.submit());
    deptOption.addEventListener('change', () => form.submit());
});
    
document.getElementById('notifBell').addEventListener('click', function() {
  const dropdown = document.getElementById('notifDropdown');
  dropdown.classList.toggle('active');

  // Mark notifications as read
  document.getElementById('notifBell').addEventListener('click', () => {
  fetch('mark_notifications_read.php', { method: 'POST' });
});
});

document.addEventListener('DOMContentLoaded', function() {
  const popup = document.getElementById('loginPopup');
  if (sessionStorage.getItem('showLoginPopup') === 'true') {
    popup.style.display = 'flex';
    sessionStorage.removeItem('showLoginPopup');
  }

  document.getElementById('closePopup').addEventListener('click', function() {
    popup.style.display = 'none';
  });
});
</script>
</body>
</html>