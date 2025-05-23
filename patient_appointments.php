<?php
include 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Cancel appointment if requested
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cancel_appointment'])) {
    $appointment_id = (int)$_POST['appointment_id'];

    $check = $conn->query("SELECT * FROM appointments WHERE id = '$appointment_id' AND patient_id = '$patient_id' AND status = 'pending'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE appointments SET status = 'cancelled' WHERE id = '$appointment_id'");
        $success = "Appointment cancelled successfully.";
    } else {
        $error = "You can only cancel your pending appointments.";
    }
}

// Fetch patient's appointments
$sql = "SELECT a.id, a.appointment_id, d.name AS doctor_name, d.specialization, a.appointment_date, a.appointment_time, a.status 
        FROM appointments a
        JOIN users d ON a.doctor_id = d.id
        WHERE a.patient_id='$patient_id'
        ORDER BY a.appointment_date DESC";

$appointments = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h2>My Appointments</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Doctor</th>
                <th>Specialization</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($appointments->num_rows > 0): ?>
                <?php while ($row = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['appointment_id']; ?></td>
                        <td><?= $row['doctor_name']; ?></td>
                        <td><?= $row['specialization']; ?></td>
                        <td><?= date("F j, Y", strtotime($row['appointment_date'])); ?></td>
                        <td><?= date("h:i A", strtotime($row['appointment_time'])); ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $row['status'] === 'approved' ? 'success' :
                                ($row['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                <?= ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'pending'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="appointment_id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="cancel_appointment" class="btn btn-outline-danger btn-sm" onclick="return confirm('Cancel this appointment?');">
                                        Cancel
                                    </button>
                                </form>
                            <?php elseif ($row['status'] === 'approved'): ?>
                                <a href="download_appointment.php?id=<?= $row['appointment_id']; ?>" class="btn btn-outline-secondary btn-sm" target="_blank">
                                    Download PDF
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No Action</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No appointments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
