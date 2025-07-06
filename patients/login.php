<?php
session_start();
include '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, full_name, password FROM patients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['patient_id'] = $id;
            $_SESSION['patient_name'] = $name;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Email not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Login - Smart Laboratory</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    body {
      background-color: #002147;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
    }
    .login-box {
      background: #003366;
      padding: 40px;
      border-radius: 10px;
      width: 100%;
      max-width: 400px;
    }
    .form-control {
      background: transparent;
      border: none;
      border-bottom: 2px solid #fff;
      border-radius: 0;
      color: white;
    }
    .form-control::placeholder {
      color: #ccc;
    }
    .form-control:focus {
      background: transparent;
      box-shadow: none;
      border-color: #66bfff;
    }
    .login-box h2 {
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
      font-family: 'Georgia', serif;
    }
    .btn-login {
      background: #007bff;
      border: none;
    }
    .right-img {
      background: #002147;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .right-img img {
      max-width: 100%;
      height: auto;
    }
    .text-link {
      color: #66bfff;
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="container-fluid login-container">
  <div class="row w-100">
    <!-- Left: Login Form -->
    <div class="col-md-6 d-flex align-items-center justify-content-center">
      <div class="login-box">
        <div class="text-center mb-3">
          <img src="../assets/img/avatar.png" class="rounded-circle" width="80" alt="Avatar">
        </div>
        <h2>Patient Login</h2>

        <?php if ($error): ?>
          <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
          </div>

          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
          </div>

          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-login text-white">Login</button>
          </div>

          <div class="text-center">
            <p class="mb-1">
              Don't have an account? <a href="register.php" class="text-link"><strong>Register</strong></a>
            </p>
            <p class="mb-0">
              <a href="forgot_password.php" class="text-link">Forgot password?</a>
            </p>
          </div>
        </form>
      </div>
    </div>

    <!-- Right: Illustration -->
    <div class="col-md-6 right-img d-none d-md-flex">
      <img src="../assets/img/4e16d7162b915b8de14a767f1d15b26d520511d6.png" alt="Login Illustration">
    </div>
  </div>
</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>