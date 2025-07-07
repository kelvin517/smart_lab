<?php
session_start();
require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Fetch from `users` where role is 'doctor'
        $stmt = $conn->prepare("SELECT id, full_name, password, must_change_password FROM users WHERE email = ? AND role = 'doctor'");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $full_name, $hashed_password, $must_change_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    $_SESSION['doctor_id'] = $id;
                    $_SESSION['doctor_name'] = $full_name;

                    if ($must_change_password) {
                        header("Location: change_password.php?first_time=1");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit;
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "No doctor account found with that email.";
            }

            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}
?>

<!-- Frontend: Login Page -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Login - Smart Laboratory</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    body {
      background: url('../assets/img/26807.jpg') no-repeat center center fixed;
      background-size: cover;
    }
    .login-card {
      backdrop-filter: blur(6px);
      background-color: rgba(255, 255, 255, 0.95);
    }
  </style>
</head>
<body>

<main>
  <div class="container">
    <section class="section login min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="card col-lg-5 login-card shadow">
        <div class="card-body">
          <div class="pt-4 pb-2 text-center">
            <img src="../assets/img/avatar-doctor.png" alt="Doctor Avatar" class="rounded-circle mb-3" width="80">
            <h5 class="card-title pb-0 fs-4">Doctor Login</h5>
            <p class="text-muted small">Enter your credentials to access your dashboard</p>
          </div>

          <?php if ($error): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
          <?php endif; ?>

          <form method="POST" class="row g-3 needs-validation" novalidate>
            <div class="col-12">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-12">
              <label for="password" class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-12 text-center">
              <button class="btn btn-primary w-100" type="submit">Login</button>
            </div>

            <div class="col-12 text-center">
              <p class="small mb-0">Forgot password? <a href="reset_password.php">Reset here</a></p>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
</main>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>