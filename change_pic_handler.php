<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

if (isset($_POST['delete_pic'])) {
    // Delete profile picture
    $stmt = $conn->prepare("UPDATE users SET user_pic = NULL WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    // Optional: Redirect with success param
    header("Location: my_profile.php?deleted=1");
    exit;
}

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $imageData = file_get_contents($_FILES['profile_pic']['tmp_name']);

    $stmt = $conn->prepare("UPDATE users SET user_pic = ? WHERE user_id = ?");
    $stmt->bind_param("bi", $imageData, $user_id); // Use 'b' for BLOB, 'i' for int
    $stmt->send_long_data(0, $imageData);
    $stmt->execute();
    $stmt->close();

    header("Location: my_profile.php?updated=1");
    exit;
}

header("Location: my_profile.php?error=1");
exit;
?>
