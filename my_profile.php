<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email,role, user_pic FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $role, $user_pic);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Profile </title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background-color: #0f0f1c;
            color: #fff;
            margin: 0;
            padding: 40px;
            background-image: linear-gradient(to right top, #0f0f1c, #161623, #1c1c2a, #232331, #292938);
        }

        .profile-container {
            display: flex;
            background: rgba(30, 30, 50, 0.9);
            border-radius: 16px;
            box-shadow: 0 0 25px #00ffe0;
            padding: 30px;
            max-width: 1000px;
            margin: auto;
            border: 2px solid #00ffe0;
        }

        .left-panel {
            width: 35%;
            text-align: center;
            border-right: 2px solid #00ffe0;
            padding-right: 25px;
        }

        .right-panel {
            flex: 1;
            padding-left: 30px;
        }

        img.profile-pic {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #00ffe0;
            margin-bottom: 20px;
            box-shadow: 0 0 12px #00ffe0;
        }

        .btn {
            padding: 10px 16px;
            font-size: 14px;
            border: none;
            background-color: #00ffe0;
            color: #0f0f1c;
            cursor: pointer;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #00ccbb;
            transform: scale(1.05);
        }

        .delete-btn {
            background-color: #ff0055;
            color: #fff;
        }

        .delete-btn:hover {
            background-color: #cc0044;
        }

        .info-row {
            margin-bottom: 25px;
            border-bottom: 1px dashed #00ffe0;
            padding-bottom: 10px;
        }

        .info-row label {
            font-weight: bold;
            color: #00ffe0;
            font-size: 14px;
        }

        .info-row span {
            font-size: 18px;
            display: block;
            margin-top: 5px;
        }

        h2,
        h3 {
            color: #00ffe0;
        }

        input[type="file"] {
            margin-top: 10px;
            border: 2px solid #00ffe0;
            border-radius: 6px;
            padding: 5px;
            background-color: transparent;
            color: #fff;
        }

        input[type="file"]::file-selector-button {
            background-color: #00ffe0;
            color: #0f0f1c;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 6px;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #00ccbb;
        }

        #deleteBtn {
            padding: 10px 18px;
            font-size: 16px;
            font-weight: bold;
            background-color: #e53935;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 12px;
        }

        #deleteBtn:hover {
            background-color: #c62828;
        }

        .bottom-buttons {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            gap: 15px;
            z-index: 999;
        }

        .logout-btn {
            background-color: #ff0055;
            color: #fff;
        }

        .logout-btn:hover {
            background-color: #cc0044;
        }

        a {
  text-decoration: none;
}
    </style>
</head>

<body>

    <div class="profile-container">
        <div class="left-panel">
            <h3>Profile Picture</h3>
            <?php if ($user_pic): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($user_pic); ?>" class="profile-pic"
                    alt="Profile Picture">
            <?php else: ?>
                <img src="https://via.placeholder.com/160x160?text=No+Image" class="profile-pic" alt="Profile Picture">
            <?php endif; ?>

            <form action="change_pic_handler.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_pic" accept="image/*" required>
                <button type="submit" class="btn">Change Pic</button>
            </form>

            <form id="deleteForm" action="change_pic_handler.php" method="POST">
                <input type="hidden" name="delete_pic" value="1">
                <button type="button" id="deleteBtn" style="background-color: red; color: white;">Delete Pic</button>
            </form>

        </div>

        <div class="right-panel">
            <h2>Welcome, <?= htmlspecialchars($name); ?></h2>

            <div class="info-row">
                <label>Name:</label>
                <span><?= htmlspecialchars($name); ?></span>
            </div>

            <div class="info-row">
                <label>Email:</label>
                <span><?= htmlspecialchars($email); ?></span>
            </div>

            <div class="info-row">
                <label>Password:</label>
                <span>••••••••</span>
            </div>

            <div class="info-row">
                <label>Your Role:</label>
                <span><?= htmlspecialchars($role); ?></span>
            </div>
        </div>
    </div>

    <div class="bottom-buttons">
        <a href="dashboard.php" class="btn" title="good choice😀">← Go to Dashboard</a>
        <a href="logout.php" class="btn logout-btn" title="You want to Go?🥹">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('deleteBtn').addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete your profile picture!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if (isset($_GET['updated'])): ?>
            Swal.fire({
                title: 'Success!',
                text: 'Profile picture updated successfully.',
                icon: 'success',
                confirmButtonColor: '#00ffe0'
            });
        <?php elseif (isset($_GET['deleted'])): ?>
            Swal.fire({
                title: 'Deleted!',
                text: 'Profile picture deleted successfully.',
                icon: 'info',
                confirmButtonColor: '#00ffe0'
            });
        <?php elseif (isset($_GET['error'])): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Something went wrong. Try again.',
                icon: 'error',
                confirmButtonColor: '#ff0055'
            });
        <?php endif; ?>
    </script>


</body>

</html>