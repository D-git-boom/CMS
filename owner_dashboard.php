<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT name FROM users WHERE user_id = $user_id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Owner Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Orbitron', sans-serif;
    }

    body {
      background: #0f0f1a;
      color: #f0f0f0;
    }

    .dashboard-container {
      max-width: 1200px;
      margin: auto;
      padding: 40px 20px;
    }

    .welcome {
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 40px;
      color: #0ff;
      text-shadow: 0 0 5px #0ff, 0 0 10px #0ff;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
    }

    .card {
      background: linear-gradient(145deg, #1e1e2e, #151526);
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 0 15px #00ffe1;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 0 25px #ff00ff, 0 0 35px #00ffe1;
    }

    .card i {
      font-size: 32px;
      margin-bottom: 12px;
      color: #ff00ff;
      text-shadow: 0 0 10px #ff00ff;
    }

    .card-title {
      font-size: 20px;
      font-weight: 600;
      color: #ffffff;
      text-shadow: 0 0 5px #00ffe1;
    }

    .logout {
      margin-top: 50px;
      text-align: right;
    }

    .logout a {
      background: #ff0040;
      color: white;
      padding: 12px 25px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: bold;
      text-shadow: 0 0 5px #fff;
      box-shadow: 0 0 10px #ff0040, 0 0 20px #ff0040;
      transition: background 0.3s ease;
    }

    .logout a:hover {
      background: #c40031;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <div class="welcome">👾 Welcome back, <strong><?= htmlspecialchars($user['name']); ?></strong>!</div>

    <div class="cards">
      <div class="card" onclick="window.location.href='view_complaints.php'">
        <i class="fas fa-inbox"></i>
        <div class="card-title">View Complaints</div>
      </div>

      <div class="card" onclick="window.location.href='resolved_complaints.php'">
        <i class="fas fa-check-circle"></i>
        <div class="card-title">Resolved Complaints</div>
      </div>

      <div class="card" onclick="window.location.href='reply_users.php'">
        <i class="fas fa-comment-dots"></i>
        <div class="card-title">Reply to Users</div>
      </div>

      <div class="card" onclick="window.location.href='my_profile.php'">
        <i class="fas fa-user-cog"></i>
        <div class="card-title">My Profile</div>
      </div>
    </div>

    <div class="logout">
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>
</body>
</html>
