<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT user_pic FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($imageData);
    $stmt->fetch();

    header("Content-Type: image/jpeg"); // or image/png depending on your storage
    echo $imageData;
} else {
    // Serve default image
    header("Content-Type: image/jpeg");
    readfile("uploads/default.jpg");
}

$stmt->close();
$conn->close();
