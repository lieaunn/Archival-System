<?php
include 'db.php'; // your database connection

$search = isset($_GET['search']) ? $_GET['search'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'all';

// Base SQL
$sql = "SELECT * FROM abstracts WHERE 1=1";

// Search filter
if(!empty($search)) {
    $search_param = "%{$search}%";
    $sql .= " AND name LIKE ?";
}

// Department filter
if($department != 'all') {
    $sql .= " AND department = ?";
}

// Sort filter
if($sort == 'a-z') {
    $sql .= " ORDER BY name ASC";
}

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if(!empty($search) && $department != 'all') {
    $stmt->bind_param("ss", $search_param, $department);
} elseif(!empty($search)) {
    $stmt->bind_param("s", $search_param);
} elseif($department != 'all') {
    $stmt->bind_param("s", $department);
}

// Execute
$stmt->execute();
$result = $stmt->get_result();

// Display results
if($result && $result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['name']."</td>
                <td>".$row['department']."</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No records found.";
}
?>
