<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit;
}

$doctor_name = $_SESSION['doctor_name'];
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Doctor Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div>

  <section class="section dashboard">
    <div class="row">

      <!-- Welcome Card -->
      <div class="col-12">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Welcome, Dr. <?= htmlspecialchars($doctor_name) ?> 👋</h5>
            <p class="card-text">This is your Smart Laboratory dashboard. From here, you can view assigned patients, appointments, messages, and manage test results.</p>
          </div>
        </div>
      </div>

      <!-- Quick Stats (Optional: Customize based on features) -->
      <div class="col-md-4">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Today's Appointments</h5>
            <span class="fs-3 text-primary">--</span>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Pending Results</h5>
            <span class="fs-3 text-warning">--</span>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Messages</h5>
            <span class="fs-3 text-success">--</span>
          </div>
        </div>
      </div>

    </div>
  </section>
</main>