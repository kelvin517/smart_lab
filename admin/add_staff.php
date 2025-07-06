<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $insert = mysqli_query($conn, "INSERT INTO users (full_name, email, phone, role, password)
                                   VALUES ('$name', '$email', '$phone', '$role', '$pass')");

    if ($insert) {
        header("Location: view_staff.php");
        exit();
    } else {
        echo "Failed to add staff.";
    }
}

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main id="main" class="main">
  <div class="pagetitle"><h1>Add Staff</h1></div>
  <section class="section">
    <form method="POST" class="form-control">
      <label>Full Name</label>
      <input type="text" name="full_name" required class="form-control mb-2">

      <label>Email</label>
      <input type="email" name="email" required class="form-control mb-2">

      <label>Phone</label>
      <input type="text" name="phone" class="form-control mb-2">

      <label>Role</label>
      <select name="role" class="form-control mb-2">
        <option value="doctor">Doctor</option>
        <option value="technician">Technician</option>
      </select>

      <label>Password</label>
      <input type="password" name="password" required class="form-control mb-3">

      <button type="submit" class="btn btn-primary">Add Staff</button>
    </form>
  </section>
</main>

<?php include 'includes/footer.php'; ?>