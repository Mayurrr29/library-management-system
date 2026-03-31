<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['login']) == 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $upload_dir = 'assets/img/profiles/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file = $_FILES['profile_image'];
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Use JPG, PNG, GIF, or WebP.']);
        exit;
    }

    if ($file['size'] > $max_size) {
        echo json_encode(['success' => false, 'message' => 'File too large. Max 5MB.']);
        exit;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'user_' . $_SESSION['stdid'] . '_' . time() . '.' . $ext;
    $dest = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $sid = $_SESSION['stdid'];
        $sql = "UPDATE tblstudents SET ProfileImage=:img WHERE StudentId=:sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':img', $filename, PDO::PARAM_STR);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query->execute();

        echo json_encode(['success' => true, 'image' => $dest]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed. Check folder permissions.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file received.']);
}
?>
