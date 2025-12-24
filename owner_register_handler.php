<?php
ob_start();
session_start(); // Important to access $_SESSION

$conn = new mysqli("localhost", "root", "", "complaint_system");

// Block if secret was not verified
if (!isset($_SESSION['secret_verified']) || $_SESSION['secret_verified'] !== true) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        title: 'Unauthorized!',
        text: 'You have not verified the secret code.',
        icon: 'error'
    }).then(() => {
        window.location.href = 'owner_register.php';
    });
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name           = $_POST['name'];
    $email          = $_POST['email'];
    $password       = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $hostel_name    = $_POST['hostel_name'];
    $hostel_address = $_POST['hostel_address'];

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'owner')");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        $owner_id = $stmt->insert_id;

        // Insert into hostels table
        $stmt2 = $conn->prepare("INSERT INTO hostels (name, address, owner_id) VALUES (?, ?, ?)");
        $stmt2->bind_param("ssi", $hostel_name, $hostel_address, $owner_id);

        if ($stmt2->execute()) {
            $hostel_id = $stmt2->insert_id;

            // Update user's hostel_id
            $stmt3 = $conn->prepare("UPDATE users SET hostel_id = ? WHERE user_id = ?");
            $stmt3->bind_param("ii", $hostel_id, $owner_id);
            $stmt3->execute();

            // Clear session verification
            unset($_SESSION['secret_verified']);

            // Success alert and redirect
            echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                Swal.fire({
                    title: 'Registration Successful!',
                    text: 'Owner and Hostel Registered Successfully!',
                    icon: 'success',
                    confirmButtonText: 'Go to Dashboard'
                }).then(() => {
                    window.location.href = 'owner_dashboard.php';
                });
                </script>
            </body>
            </html>";
            exit;
        } else {
            echo "Hostel insert failed: " . $stmt2->error;
        }
    } else {
        echo "User insert failed: " . $stmt->error;
    }
}
?>
