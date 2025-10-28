<?php
include '../db/db.php';

// Get the abstract details for editing
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "SELECT * FROM abstracts WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $abstract = mysqli_fetch_assoc($result);
    } else {
        die("⚠️ Abstract not found in database.");
    }
} else {
    die("⚠️ Invalid request. No ID provided.");
}

// Update the abstract when form is submitted
if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $department = $_POST['department'];
    $status = $_POST['status'];

    $updateSql = "UPDATE abstracts 
                  SET title='$title', author='$author', year='$year', department='$department', status='$status' 
                  WHERE id=$id";
    
    if (mysqli_query($conn, $updateSql)) {
        header("Location: dashboard.php#manage");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Abstract</title><style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .form-container {
      background: #fff;
      padding: 25px 30px;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
      width: 400px;
    }
    .form-container h2 {
      margin-bottom: 20px;
      font-size: 22px;
      color: #333;
    }
    .form-container label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #555;
    }
    .form-container input,
    .form-container select {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }
    .form-container button {
      padding: 10px 18px;
      border: none;
      border-radius: 4px;
      font-size: 14px;
      cursor: pointer;
      margin-right: 10px;
    }
    .btn-update {
      background-color: #28a745;
      color: white;
    }
    .btn-cancel {
      background-color: #ccc;
      color: #333;
      text-decoration: none;
      padding: 10px 18px;
      border-radius: 4px;
    }
    .btn-update:hover {
      background-color: #218838;
    }
    .btn-cancel:hover {
      background-color: #aaa;
    }
  </style>

</head>
<body>
    <div class="form-container">
    <h2>Edit Abstract</h2>
    <form method="POST">
      <label for="title">Title:</label>
      <input type="text" id="title" name="title" value="<?= $abstract['title'] ?>" required>

      <label for="author">Author:</label>
      <input type="text" id="author" name="author" value="<?= $abstract['author'] ?>" required>

      <label for="year">Year:</label>
      <input type="number" id="year" name="year" value="<?= $abstract['year'] ?>" required>

      <label for="department">Department:</label>
      <select id="department" name="department" value="<?= $abstract['department'] ?>" required>
                                    <option value="">-- Select Department --</option>
                                    <option value="BSED-English">BS Education Major in English </option>
                                    <option value="BSED-SocStud">BS Education Major in Social Studies </option>
                                    <option value="BEED">Bachelor of Elementary Education</option>
                                    <option value="BSOA">BS Office Administration </option>
                                    <option value="BSIT"> BS Information Technology</option>
                                    <option value="BSECE">BS Electronics Engineering</option>
                                    <option value="ABCOMM"> AB Communication </option>
                                </select>

      <button type="submit" name="update" class="btn-update">Update</button>
      <a href="dashboard.php#manage" class="btn-cancel">Cancel</a>
    </form>
  </div>
</body>
</html>
