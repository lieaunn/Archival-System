<?php
if (isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $filePath = "../user/uploads/" . $file;

    if (file_exists(__DIR__ . "../user/uploads/" . $file)) {
       header("Location: ../web/viewer.html?file=" . urlencode("../user/uploads/" . $file));
    } else {
        die("File not found.");
    }
} else {
    die("No file specified.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View PDF</title>
    <style>
        html, body {
            margin: 0;
            height: 100%;
            overflow: hidden;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <!-- PDF.js Viewer -->
    <iframe 
        src="../web/viewer.html?file=../user/uploads/<?php echo rawurlencode(basename($webPath)); ?>" 
        allowfullscreen>
    </iframe>
</body>
</html>
