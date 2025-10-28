<?php
session_start();
include '../db/db.php';


if(!isset($conn) || $conn === null) {
    die("Database connection not found. Check your connection.");
}

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
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css' rel='stylesheet'>
</head>
<body>

    <!-- sidebar -->
    <aside class="sidebar">
        <h2> Admin Panel </h2>
            <ul id="menu">
                <li class="active" onclick="showContent('library')"><i class="fa-solid fa-bars"></i> Library</li>
                <li onclick="showContent('pending')"><i class="fa-solid fa-upload"></i> Pending Abstracts</li>
                <li onclick="showContent('manage')"><i class="fa-solid fa-list-check"></i> Manage Abstracts</li>
                <li onclick="showContent('archived')"><i class="fa-solid fa-box-archive"></i> Archived Abstracts</li>
                <li onclick="window.location.href='logout.php'"><i class="fa-solid fa-right-from-bracket"></i> Logout </li>
            </ul>
    </aside>

    <div class="main-content">
        <div class="section active" id="library" style="display:block;">
           
            <h1> Library </h1>
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
                                        </select>
                                </div>
                                    <button type="submit" class="apply-filter" id="applyBtn"> Apply Filter</button>
                            </div>
                    </div>  
                </div>
            </form>
         
                     <h2 style="padding-left: 18px; margin-bottom: -10px;"> List of Abstracts </h2>
                    
    
                <div class="table-container">
                  <table>
                    <thead>
                        <tr>
                            <th style="width:70px">ID</th>
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
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
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
            </form>
        </div>

        <div class="section" id="pending" style="display:none;">    
                <?php
                    include '../db/db.php'; // adjust path if needed

                    // fetch all pending abstracts
                    $sql = "SELECT * FROM abstracts WHERE status = 'Pending Review'";
                    $result = mysqli_query($conn, $sql);
                    ?>
         
            <h1> Pending Abstracts</h1>
                <div class="table-container">
                    <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th style="width: 60px;">Status</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
            echo "<td>" . htmlspecialchars($row['department']) . "</td>";
            echo "<td>" . $row['year'] . "</td>";
            echo "<td><a href='/archive/web/viewer.html?file=" . rawurlencode('http://localhost/archive/user/uploads/' . basename($row['file'])) . "' target='_blank'>
                        <button class='btn-view' style='background-color:#3498db; color:white; padding:8px 12px; 
                        border:none; margin-left: 3px; border-radius:6px; cursor:pointer;'>View</button>
                        </a></td>";
            echo "<td>
                    <form action='/archive/admin/approve.php' method='POST' style='display:inline;'>
                      <input type='hidden' name='id' value='" . $row['id'] . "'>
                      <button type='submit' class='btn-approve' style='background-color:#28a745; margin-left: 5px;  margin-bottom: 10px; color:white; padding:8px 12px; 
                   border:none; border-radius:6px; cursor:pointer;'>Approve</button>
                    </form>

                    <button type='button' class='btn-decline' style='background-color:#dc3545; color:white; padding:8px 12px; border:none; border-radius:6px; cursor:pointer;' 
                    onclick='openRejectModal(" . $row['id'] . ")'>Disapprove</button>
                  </td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='7' style='text-align:center;'>No pending abstracts</td></tr>";
        }
        ?>
                    </tbody>
                    </table>    
                </div>
        </div>

        <div id="rejectModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); 
            align-items:center; justify-content:center; z-index:9999;">
            <div style="background:#fff; padding:20px; border-radius:10px; width:400px; box-shadow:0 5px 20px rgba(0,0,0,0.3);">
                <h3>Disapprove Abstract</h3>
                <form action="decline.php" method="POST">
                <input type="hidden" name="id" id="reject_id">
                <label for="reason">Reason for disapproval:</label><br>
                <textarea name="reason" id="reason" required style="width:100%; height:100px; margin-top:6px; resize:none; padding:8px;"></textarea><br><br>
                <button type="submit" style="background-color:#dc3545; color:white; border:none; border-radius:6px; padding:8px 12px; cursor:pointer;">Submit</button>
                <button type="button" onclick="closeRejectModal()" style="background-color:#6c757d; color:white; border:none; border-radius:6px; padding:8px 12px; cursor:pointer;">Cancel</button>
                </form>
            </div>
        </div>

        <div class="section" id="manage" style="display:none;">
            <?php
                include '../db/db.php';

                // FETCH ONLY APPROVED ABSTRACTS
                $sql = "SELECT * FROM abstracts WHERE status = 'Approved'";
                $result = mysqli_query($conn, $sql);
                ?>
            <h1> Manage Abstracts</h1>
                <div class="table-container">
                    <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Year</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td style="width: 210px;"><?= $row['title'] ?></td>
                            <td><?= $row['author'] ?></td>
                            <td><?= $row['year'] ?></td>
                            <td style="width: 20px;"><?= $row['department'] ?></td>
                            <td style="width: 20px;"><?= $row['status'] ?></td>
                            <td class="actions">
                                <button class="btn-view" style="margin-bottom: 10px;"><a style="text-decoration:none; color: white; padding: 10px;" href="/archive/web/viewer.html?file=<?php echo 'http://localhost/archive/user/uploads/' . rawurlencode(basename($row['file'])); ?>" >View</a></button><br>
                                
                                <button class="btn-delete" style="margin-bottom: 10px;"> <a style="text-decoration:none; border-radius: 5px; padding-left: 20px; color: white; padding: 10px;" onclick="return confirm('Archive this abstract?')" href="archive.php?id=<?= $row['id'] ?>">Archive</a></button><br>    
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    </table> 
                </div>   
        </div>

        <div class="section" id="archived" style="display:none;">

         <?php
                include '../db/db.php';

                // FETCH ONLY APPROVED ABSTRACTS
                $sql = "SELECT * FROM abstracts WHERE status = 'Archived'";
                $result = mysqli_query($conn, $sql);
                ?>
            <h1>Archived Abstracts</h1>

            <table class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Author</th>
          <th>Year</th>
          <th>Department</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT * FROM abstracts WHERE status='Archived'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['title']}</td>";
            echo "<td>{$row['author']}</td>";
            echo "<td>{$row['year']}</td>";
            echo "<td>{$row['department']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td class='actions'>
                    <a href='/archive/web/viewer.html?file=" . rawurlencode(basename($row['file'])) . "' target='_blank'>
                      <button class='btn-view' style='background-color:#3498db; margin-bottom: 10px; color:white; padding:8px 12px; 
                      border:none; border-radius:6px; cursor:pointer;'>View</button><br>
                    </a>
                    <a href='restore.php?id={$row['id']}'><button class='btn-retrieve' style='background-color:#f0ad4e; color:white; padding:8px 12px; 
                      border:none; border-radius:6px; cursor:pointer;'> Retrieve </button>
                    </a>
                  </td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='7' style='text-align:center;'>No archived abstracts found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
        </div>
    </div>

   
<script>
     function showContent(sectionId) {
  // Hide all sections
    document.querySelectorAll('.section').forEach(sec => {
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

function openRejectModal(id) {
  document.getElementById('reject_id').value = id;
  document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
  document.getElementById('rejectModal').style.display = 'none';
}

</script>
</body>
</html>