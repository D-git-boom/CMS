<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Fetch email and get correct user details including role
    $stmt  = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row     = $result->fetch_assoc();
        $hashed  = $row['password'];

        if (password_verify($password, $hashed)) {
            // Set sessions
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role']    = $row['role'];
            $_SESSION['name']    = $row['name'];
            $_SESSION['email']   = $row['email'];

            // Redirect based on role automatically
            $redirectPage = 'dashboard.php'; // Default for normal user

            if ($row['role'] === 'owner') {
                $redirectPage = 'owner_dashboard.php';
            } elseif ($row['role'] === 'admin') {
                $redirectPage = 'admin_dashboard.php';
            }

            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: 'Welcome back, " . htmlspecialchars($row['name']) . "',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '$redirectPage';
                    });
                }, 100);
            </script>";
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Incorrect Password',
                        text: 'Please try again!'
                    });
                }, 100);
            </script>";
        }
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'User Not Found',
                    text: 'Please register first.'
                });
            }, 100);
        </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Complaint System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #2980b9);
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
            box-shadow: 0 20px 30px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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
            padding: 12px 0px 12px 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            outline: none;
            font-size: 16px;
            max-length: 50;
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

        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
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
            background: #2471a3;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            color: #555;
        }

        .register-link a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Login</h2>
    <form method="post" autocomplete="off">
        <div class="input-group">
            <input type="email" name="email" required placeholder=" " />
            <label>Email</label>
        </div>

        <div class="input-group">
            <input type="password" name="password" id="login-password" required placeholder=" " />
            <label>Password</label>
            <i class="fas fa-eye toggle-eye" onclick="togglePassword('login-password', this)"></i>
        </div>

        <button type="submit">Login</button>
        <div class="register-link">
            New user? <a href="register.php">Register here</a>
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
