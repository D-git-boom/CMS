<?php
$correct_secret = "khufiya123";
$secret_verified = false;
$show_invalid_alert = false;

// Check if the secret was previously verified via session (optional improvement)
session_start();
if (isset($_SESSION['secret_verified']) && $_SESSION['secret_verified'] === true) {
    $secret_verified = true;
}

// Only check secret code when secret_code is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['secret_code'])) {
    if ($_POST['secret_code'] === $correct_secret) {
        $_SESSION['secret_verified'] = true;
        $secret_verified = true;
    } else {
        $show_invalid_alert = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f7f7f7;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 30px;
            margin: 60px auto;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            
            /* align-items: center; */
            /* display: flex; */
        }
        .container h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        .form-group {
            position: relative;
            margin-bottom: 25px;
        }
        .form-group input {
            width: 100%;
            padding: 14px ;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: none;
            outline: none;
            padding-right: 0px;
            max-width: 97%;
        }
        .form-group label {
            position: absolute;
            top: 14px;
            left: 12px;
            background: white;
            padding: 0 5px;
            color: #aaa;
            transition: 0.3s ease;
            pointer-events: none;
        }
        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            top: -9px;
            left: 8px;
            font-size: 12px;
            color: #4CAF50;
        }
        .form-group i.toggle-eye {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #999;
    }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: none;
            background: #4CAF50;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }
        button:hover {
            background: #43a047;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if (!$secret_verified): ?>
        <h2>🔒 Enter Secret Code</h2>
        <form method="POST" autocomplete="off">
            <div class="form-group">
                <input type="text" name="secret_code" required placeholder=" " />
                <label>Secret Code</label>
            </div>
            <button type="submit">Verify</button>
        </form>
    <?php else: ?>
        <h2>🛡️ Owner Registration</h2>
        <form method="POST" action="owner_register_handler.php" autocomplete="off">
            <div class="form-group">
                <input type="text" name="name" required placeholder=" " />
                <label>Owner Name</label>
            </div>
            <div class="form-group">
                <input type="email" name="email" required placeholder=" " />
                <label>Email</label>
            </div>
            <div class="form-group">
                <input type="password" name="password" required placeholder=" " />
                <label>Password</label>
                <i class="fas fa-eye toggle-eye" onclick="togglePassword('reg-password', this)"></i>
            </div>
            <div class="form-group">
                <input type="text" name="hostel_name" required placeholder=" " />
                <label>Hostel Name</label>
            </div>
            <div class="form-group">
                <input type="text" name="hostel_address" required placeholder=" " />
                <label>Hostel Address</label>
            </div>
            <button type="submit">Register as Owner</button>
        </form>
    <?php endif; ?>
</div>

<?php if ($show_invalid_alert): ?>
<script>
    window.onload = function () {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Secret Code',
            text: 'Please try again!'
        }).then(() => {
            window.location.href = 'owner_register.php';
        });
    };
</script>
<?php endif; ?>

<?php if ($secret_verified && isset($_POST['secret_code'])): ?>
<script>
    window.onload = function () {
        Swal.fire({
            icon: 'success',
            title: 'Secret Code Verified',
            text: 'You may now register your hostel.'
        });
    };
</script>
<?php endif; ?>

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
