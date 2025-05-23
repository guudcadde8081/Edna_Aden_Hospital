<?php
include 'config/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$status_filter = $_GET['status'] ?? 'pending';
$search = $conn->real_escape_string($_GET['search'] ?? '');

// SQL query based on selected status and search
$where_status = "";
if ($status_filter === 'approved' || $status_filter === 'pending' || $status_filter === 'cancelled') {
    $where_status = "a.status = '$status_filter'";
}

$where_search = "";
if (!empty($search)) {
    $where_search = "(p.name LIKE '%$search%' OR d.name LIKE '%$search%')";
}

$where_clause = "";
if ($where_status && $where_search) {
    $where_clause = "WHERE $where_status AND $where_search";
} elseif ($where_status) {
    $where_clause = "WHERE $where_status";
} elseif ($where_search) {
    $where_clause = "WHERE $where_search";
}

$sql_appointments = "SELECT a.id, a.appointment_id, p.name AS patient_name, d.name AS doctor_name, a.appointment_date, a.appointment_time, a.status 
                     FROM appointments a
                     JOIN users p ON a.patient_id = p.id
                     JOIN users d ON a.doctor_id = d.id
                     $where_clause
                     ORDER BY a.appointment_date DESC";

$appointments = $conn->query($sql_appointments);
?>

<h2>Manage Appointments</h2>

<!-- Filter Navigation -->
<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link <?= $status_filter == 'pending' ? 'active' : ''; ?>" href="admin_dashboard.php?page=appointments&status=pending">Pending</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $status_filter == 'approved' ? 'active' : ''; ?>" href="admin_dashboard.php?page=appointments&status=approved">Approved</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $status_filter == 'cancelled' ? 'active' : ''; ?>" href="admin_dashboard.php?page=appointments&status=cancelled">Cancelled</a>
    </li>
</ul>

<!-- Search Bar -->
<form method="GET" class="mb-3">
    <input type="hidden" name="page" value="appointments">
    <input type="hidden" name="status" value="<?= htmlspecialchars($status_filter); ?>">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by Patient or Doctor Name" value="<?= htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-outline-primary">Search</button>
    </div>
</form>

<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>Appointment ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($appointment = $appointments->fetch_assoc()): ?>
            <tr>
                <td><?= $appointment['appointment_id']; ?></td>
                <td><?= $appointment['patient_name']; ?></td>
                <td><?= $appointment['doctor_name']; ?></td>
                <td><?= $appointment['appointment_date']; ?> at <?= date("h:i A", strtotime($appointment['appointment_time'])); ?></td>
                <td>
                    <span class="badge bg-<?= 
                        $appointment['status'] == 'approved' ? 'success' : 
                        ($appointment['status'] == 'pending' ? 'warning' : 'danger') 
                    ?>">
                        <?= ucfirst($appointment['status']); ?>
                    </span>
                </td>
                <td>
                    <?php if ($appointment['status'] == 'pending'): ?>
                        <form method="POST" action="process_appointment.php" class="d-inline">
                            <input type="hidden" name="appointment_id" value="<?= $appointment['id']; ?>">
                            <button type="submit" name="status" value="approved" class="btn btn-success btn-sm">Approve</button>
                            <button type="submit" name="status" value="cancelled" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-sm" disabled>No Action</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
