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
    <h1>Messages</h1>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body pt-4">
        <h5 class="card-title">Communication from Lab Staff</h5>

        <table class="table table-hover">
          <thead>
            <tr>
              <th>From</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Date Sent</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "SELECT m.subject, m.message, m.created_at, u.full_name 
                      FROM messages m
                      JOIN users u ON m.sender_id = u.id
                      WHERE m.receiver_id = ? AND m.receiver_role = 'patient'
                      ORDER BY m.created_at DESC";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
            ?>
              <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><strong><?= htmlspecialchars($row['subject']) ?></strong></td>
                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td><?= $row['created_at'] ?></td>
              </tr>
            <?php endwhile; $stmt->close(); ?>
          </tbody>
        </table>

      </div>
    </div>
  </section>
</main>