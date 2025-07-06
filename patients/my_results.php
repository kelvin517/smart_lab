<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit;
}

$patient_id = $_SESSION['patient_id'];
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>My Lab Results</h1>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body pt-4">
        <h5 class="card-title">Available Results</h5>

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Test Type</th>
              <th>Status</th>
              <th>Preferred Date</th>
              <th>Result File</th>
              <th>Uploaded On</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT test_type, status, preferred_date, result_file, created_at 
                    FROM bookings 
                    WHERE patient_id = ? AND result_file IS NOT NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()):
            ?>
              <tr>
                <td><?= htmlspecialchars($row['test_type']) ?></td>
                <td><span class="badge bg-success"><?= htmlspecialchars($row['status']) ?></span></td>
                <td><?= htmlspecialchars($row['preferred_date']) ?></td>
                <td>
                  <a href="../uploads/<?= $row['result_file'] ?>" target="_blank" class="btn btn-sm btn-info">Download</a>
                </td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
              </tr>
            <?php endwhile; $stmt->close(); ?>
          </tbody>
        </table>

      </div>
    </div>
  </section>
</main>