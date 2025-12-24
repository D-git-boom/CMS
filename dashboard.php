<?php
session_start();
include 'db.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$user_name = $_SESSION['name'] ?? 'Guest';
$user_image = $_SESSION['profile_pic'] ?? 'default.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap');

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Orbitron', sans-serif;
      background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
      color: #f1f1f1;
      /* background-position: center; */

    }

    /* Preloader */
    #preloader {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: #0f0c29;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.5s ease;
    }

    .loader {
  font-size: 48px;
  display: inline-block;
  font-family: Arial, Helvetica, sans-serif;
  font-weight: bold;
  color: #FFF;
  letter-spacing: 2px;
  position: relative;
  box-sizing: border-box;
}
.loader::after {
  content: 'Loading';
  position: absolute;
  left: 0;
  top: 0;
  color: #263238;
  text-shadow: 0 0 2px #FFF, 0 0 1px #FFF, 0 0 1px #FFF;
  width: 100%;
  height: 100%;
  overflow: hidden;
  box-sizing: border-box;
  animation: animloader 0.5s linear infinite;
}

@keyframes animloader {
  0% {
    height: 100%;
  }
  100% {
    height: 0%;
  }
}
      

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      background: linear-gradient(to right, #ff0080, #7928ca);
      color: white;
      box-shadow: 0 0 20px #ff00c8;
    }

    .welcome {
      font-size: 22px;
      text-shadow: 0 0 8px #ff00c8;
    }

    .profile-pic {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid #00ffff;
      box-shadow: 0 0 10px #0ff;
      cursor: pointer;
    }

    .profile-pic img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .container {
      padding: 40px;
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .form-box, .recent-box {
      flex: 1;
      min-width: 300px;
      max-width: 500px;
      background: rgba(0, 0, 0, 0.6);
      border: 2px solid #0ff;
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 0 25px #00eaff;
    }

    h3 {
      color: #0ff;
      text-shadow: 0 0 6px #0ff;
      margin-bottom: 20px;
    }

    input[type="text"], textarea, input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      background: #111;
      color: #fff;
      border: 1px solid #0ff;
      border-radius: 8px;
      font-family: 'Orbitron', sans-serif;
    }

    button[type="submit"] {
      padding: 10px 24px;
      background: #00ffcc;
      border: none;
      color: #000;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 15px;
      box-shadow: 0 0 10px #00ffcc;
    }

    button[type="submit"]:hover {
      background: #0ff;
      color: #111;
    }

    .floating-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #ff00c8;
      color: white;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      font-size: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding-bottom: 5px;
      /* text-align: center; */
      box-shadow: 0 0 20px #ff00c8;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .floating-btn:hover {
      transform: scale(1.1);
    }

    ul {
      padding-left: 20px;
    }

    ul li {
      margin-bottom: 8px;
      border-left: 3px solid #0ff;
      padding-left: 10px;
      color: #ddd;
    }

    .hidden {
      display: none;
    }
  </style>
</head>
<body>

<!-- Preloader -->
<div id="preloader">
  <div class="loader">Loading</div>
</div>

<!-- Header -->
<header>
  <div class="welcome">Welcome, <?= htmlspecialchars($user_name) ?> 👾</div>
  <div class="profile-pic" title="My Profile" onclick="window.location.href='my_profile.php'">
  <img src="fetch_image.php" alt="Profile Pic">
  </div>
</header>

<!-- Main Content -->
<div class="container">
  <!-- Complaint Form -->
  <div class="form-box hidden" id="complaintForm">
    <h3>📝 File a Complaint</h3>
    <form action="submit_complaint.php" method="POST" enctype="multipart/form-data">
      <label>Subject:</label>
      <input type="text" name="subject" required><br><br>

      <label>Description:</label>
      <textarea name="description" rows="4" required></textarea><br><br>

      <label>Upload Photo:</label>
      <input type="file" name="photo" accept="image/*"><br><br>

      <button type="submit">🚀 Submit</button>
    </form>
  </div>

  <!-- Recent Complaints -->
  <div class="recent-box" id="recentComplaints">
    <h3>📋 Recent Complaints</h3>
    <ul>
      <li>Leaking pipe in washroom</li>
      <li>Wi-Fi not working properly</li>
      <!-- Dynamic complaints can be loaded here -->
    </ul>
  </div>
</div>

<!-- Floating + Button -->
<div class="floating-btn" title="Submit New Problem" onclick="toggleForm()">+</div>

<!-- Scripts -->
<script>
  // Preloader
  window.addEventListener('load', function () {
    const preloader = document.getElementById('preloader');
    preloader.style.opacity = '0';
    setTimeout(() => preloader.style.display = 'none', 500);
  });

  // Toggle Complaint Form
  function toggleForm() {
    const form = document.getElementById('complaintForm');
    form.classList.toggle('hidden');
  }
</script>

</body>
</html>
