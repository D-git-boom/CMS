<?php
session_start();
include 'db.php';

// Assuming you get complaint data from a form:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $complaint_text = $_POST['description']; // assuming form field name is "complaint_text"
    
    // Insert complaint into database
    $stmt = $conn->prepare("INSERT INTO complaints (user_id, complaint_text) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $complaint_text);
    
    if ($stmt->execute()) {
        // Successfully inserted
        $stmt->close();
    } else {
        // Handle insertion error if needed
        $stmt->close();
        echo "Error submitting complaint.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaint Submitted</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
    // Show SweetAlert after successful complaint submission
    Swal.fire({
        title: 'Success!',
        text: 'Your complaint has been submitted successfully!',
        icon: 'success',
        confirmButtonColor: '#00ffe0',
        background: '#1a1a2e',
        color: '#fff',
        confirmButtonText: 'Go to Dashboard'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'dashboard.php';
        }
    });
</script>

</body>
</html>
