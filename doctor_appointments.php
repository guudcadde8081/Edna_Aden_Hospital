<?php
include 'config/db.php';
// Ensure session is only started once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$doctor_id = $_SESSION['user_id'];

// Get today's, yesterday's, and tomorrow's dates
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Base query
$sql_appointments = "SELECT a.id, u.name AS patient_name, a.appointment_date, a.appointment_time, a.status 
                     FROM appointments a 
                     JOIN users u ON a.patient_id = u.id 
                     WHERE a.doctor_id='$doctor_id'";

// Apply search filter
if (!empty($search_query)) {
    $sql_appointments .= " AND u.name LIKE '%$search_query%'";
}

// Apply date range filter
if (!empty($start_date) && !empty($end_date)) {
    $sql_appointments .= " AND a.appointment_date BETWEEN '$start_date' AND '$end_date'";
}

// Order results
$sql_appointments .= " ORDER BY a.appointment_date ASC";
$appointments = $conn->query($sql_appointments);

// Categorize appointments
$today_appointments = [];
$yesterday_appointments = [];
$tomorrow_appointments = [];
$future_appointments = [];
$past_appointments = [];

while ($row = $appointments->fetch_assoc()) {
    $appointment_date = date('Y-m-d', strtotime($row['appointment_date'])); // Ensure correct format
    
    if ($appointment_date == $today) {
        $today_appointments[] = $row;
    } elseif ($appointment_date == $yesterday) {
        $yesterday_appointments[] = $row;
    } elseif ($appointment_date == $tomorrow) {
        $tomorrow_appointments[] = $row;
    } elseif ($appointment_date > $tomorrow) {
        $future_appointments[] = $row;
    } else {
        $past_appointments[] = $row;
    }
}

// Function to display appointments in tab content
function displayAppointments($appointments) { ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($appointments)): ?>
                <tr><td colspan="4" class="text-center">No appointments found</td></tr>
            <?php else: ?>
                <?php foreach ($appointments as $row): ?>
                    <tr>
                        <td><?= $row['patient_name']; ?></td>
                        <td><?= date("F j, Y", strtotime($row['appointment_date'])); ?></td>
                        <td><?= date("h:i A", strtotime($row['appointment_time'])); ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $row['status'] == 'approved' ? 'success' : 
                                ($row['status'] == 'pending' ? 'warning' : 'danger') 
                            ?>">
                                <?= ucfirst($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
<?php } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h2>Appointments</h2>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="appointmentTabs">
        <li class="nav-item">
            <a class="nav-link active" id="today-tab" data-bs-toggle="tab" href="#today">Today</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="yesterday-tab" data-bs-toggle="tab" href="#yesterday">Yesterday</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tomorrow-tab" data-bs-toggle="tab" href="#tomorrow">Tomorrow</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="future-tab" data-bs-toggle="tab" href="#future">Future</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="past-tab" data-bs-toggle="tab" href="#past">Past</a>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="today">
            <h4>Today's Appointments</h4>
            <?php displayAppointments($today_appointments); ?>
        </div>
        <div class="tab-pane fade" id="yesterday">
            <h4>Yesterday's Appointments</h4>
            <?php displayAppointments($yesterday_appointments); ?>
        </div>
        <div class="tab-pane fade" id="tomorrow">
            <h4>Tomorrow's Appointments</h4>
            <?php displayAppointments($tomorrow_appointments); ?>
        </div>
        <div class="tab-pane fade" id="future">
            <h4>Future Appointments</h4>
            <?php displayAppointments($future_appointments); ?>
        </div>
        <div class="tab-pane fade" id="past">
            <h4>Past Appointments</h4>
            <?php displayAppointments($past_appointments); ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
