<?php
session_start();
include '../config/db.php';

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Admin Dashboard</h1>
  </div>

  <section class="dashboard">
    <div class="row">
      <!-- Total Staff -->
      <div class="col-lg-3 col-6">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Staff</h5>
            <?php
              $staffCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role != 'patient'"))['count'];
              echo "<h6>$staffCount Registered</h6>";
            ?>
          </div>
        </div>
      </div>

      <!-- Total Bookings -->
      <div class="col-lg-3 col-6">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Bookings</h5>
            <?php
              $bookingCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings"))['count'];
              echo "<h6>$bookingCount Tests</h6>";
            ?>
          </div>
        </div>
      </div>

      <!-- Total Feedback -->
      <div class="col-lg-3 col-6">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Feedback</h5>
            <?php
              $feedbackCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM feedback"))['count'];
              echo "<h6>$feedbackCount Responses</h6>";
            ?>
          </div>
        </div>
      </div>

      <!-- Total Inventory -->
      <div class="col-lg-3 col-6">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Inventory</h5>
            <?php
              $stockItems = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM inventory"))['count'];
              echo "<h6>$stockItems Items</h6>";
            ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
