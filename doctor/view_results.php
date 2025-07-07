<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit;
}

$doctor_name = $_SESSION['doctor_name'];
$success = $error = '';

// Handle manual result upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_result'])) {
    $booking_id = intval($_POST['booking_id']);

    if (isset($_FILES['result_file']) && $_FILES['result_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['result_file']['tmp_name'];
        $file_name = basename($_FILES['result_file']['name']);
        $destination = '../uploads/' . $file_name;

        if (move_uploaded_file($file_tmp, $destination)) {
            $update = $conn->prepare("UPDATE bookings SET result_file = ?, status = 'Completed' WHERE id = ?");
            $update->bind_param("si", $file_name, $booking_id);

            if ($update->execute()) {
                $success = "Result file uploaded successfully.";
            } else {
                $error = "Failed to update result in database.";
            }
            $update->close();
        } else {
            $error = "Failed to move uploaded file.";
        }
    } else {
        $error = "Please select a valid result file.";
    }
}
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Completed Test Results</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">View Results</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body pt-4">
        <h5 class="card-title">Patient Test Results</h5>

        <?php if ($success): ?>
          <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <table class="table datatable">
          <thead>
            <tr>
              <th>Patient</th>
              <th>Test Type</th>
              <th>Date</th>
              <th>Status</th>
              <th>Result</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "
              SELECT b.*, p.full_name 
              FROM bookings b 
              JOIN patients p ON b.patient_id = p.id 
              ORDER BY b.created_at DESC
            ";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()):
              $file_path = "../uploads/" . $row['result_file'];
              $file_exists = !empty($row['result_file']) && file_exists($file_path);
            ?>
              <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['test_type']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                  <?php if ($row['status'] === 'Completed'): ?>
                    <span class="badge bg-success">Completed</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark"><?= $row['status'] ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($file_exists): ?>
                    <a href="<?= $file_path ?>" target="_blank" class="btn btn-sm btn-primary">View</a>
                  <?php else: ?>
                    <!-- Upload form -->
                    <form method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                      <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                      <input type="file" name="result_file" accept=".pdf,.jpg,.png,.doc,.docx" required>
                      <button type="submit" name="upload_result" class="btn btn-sm btn-success">Upload</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

      </div>
    </div>
  </section>
</main>