<?php
// Connect to DB
include 'db.php'; // Include your database connection file

// Registration logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $hostel_id = $_POST['hostel_id'];

  // Check if email already exists
  $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    echo "<script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Email Already Registered',
                    text: 'Try logging in or use a different email.'
                });
            }, 100);
        </script>";
  } else {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, hostel_id) VALUES (?, ?, ?, 'user' , ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $hostel_id);
    if ($stmt->execute()) {
      echo "<script>
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful!',
                        text: 'Redirecting to login page...',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                }, 100);
            </script>";
    } else {
      echo "<script>
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.'
                    });
                }, 100);
            </script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Register | Complaint System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #8e44ad, #3498db);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .form-box {
      background: white;
      padding: 40px;
      border-radius: 20px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2);
      animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
      from {
        transform: translateY(-30px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .form-box h2 {
      margin-bottom: 30px;
      text-align: center;
      color: #333;
    }

    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group input {
      width: 100%;
      padding: 10px 0px 12px 12px;
      border: 1px solid #ccc;
      border-radius: 10px;
      outline: none;
      font-size: 16px;
     
      
    }

    .input-group label {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: white;
      padding: 0 5px;
      color: #aaa;
      transition: 0.3s;
      pointer-events: none;
    }

    .input-group input:focus+label,
    .input-group input:not(:placeholder-shown)+label {
      top: -10px;
      left: 10px;
      font-size: 12px;
      color: #3498db;
    }

    .input-group i.toggle-eye {
      position: absolute;
      right: 2px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #999;
    }

    button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      background: #3498db;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #2980b9;
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
      color: #555;
    }

    .login-link a {
      color: #3498db;
      text-decoration: none;
    }

    .minimal-select {
      width: 100%;
      padding: 10px 12px;
      font-size: 16px;
      border: 1.5px solid #ccc;
      border-radius: 6px;
      background-color: #fff;
      background-image: url("data:image/svg+xml,%3Csvg fill='%23666' height='16' viewBox='0 0 24 24' width='16' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 10px center;
      background-size: 16px;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      cursor: pointer;
      transition: border-color 0.2s ease;
    }

    .minimal-select:focus {
      border-color: #5c6bc0;
      outline: none;
    }
  </style>
</head>

<body>

  <div class="form-box">
    <h2>Create Account</h2>
    <form method="post" autocomplete="off">
      <div class="input-group">
        <input type="text" name="name" required placeholder=" " />
        <label>Name</label>
      </div>

      <div class="input-group">
        <input type="email" name="email" required placeholder=" " />
        <label>Email</label>
      </div>

      <div class="input-group">
        <input type="password" name="password" id="reg-password" required placeholder=" " />
        <label>Password</label>
        <i class="fas fa-eye toggle-eye" onclick="togglePassword('reg-password', this)"></i>
      </div>

      <select name="hostel_id" class="minimal-select" required>
        <option value="" disabled>-- Select Hostel --</option>
        <?php
        include 'db.php'; // Include your database connection file
        $hostels = $conn->query("SELECT hostel_id, name FROM hostels");
        while ($row = $hostels->fetch_assoc()) {
          echo "<option value='{$row['hostel_id']}'>{$row['name']}</option>";
        }
        ?>
      </select><br><br>

      <button type="submit">Register</button>
      <div class="login-link">
        Already have an account? <a href="login.php">Login</a>
      </div>
    </form>
  </div>

  <script>
    function togglePassword(id, el) {
      const input = document.getElementById(id);
      if (input.type === "password") {
        input.type = "text";
        el.classList.remove("fa-eye");
        el.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        el.classList.remove("fa-eye-slash");
        el.classList.add("fa-eye");
      }
    }
  </script>

</body>

</html>