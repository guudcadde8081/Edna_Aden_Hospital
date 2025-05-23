<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Fetch patient name
$sql = "SELECT name FROM users WHERE id='$patient_id'";
$result = $conn->query($sql);
$patient = $result->fetch_assoc();
$patient_name = $patient['name'] ?? 'Patient';

$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?= $patient_name; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    <!-- Navigation Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="patient_dashboard.php">Welcome, <?= $patient_name; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'appointments') ? 'active' : ''; ?>" href="patient_dashboard.php?page=appointments">My Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'book_appointment') ? 'active' : ''; ?>" href="patient_dashboard.php?page=book_appointment">Book Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'ehr') ? 'active' : ''; ?>" href="patient_dashboard.php?page=ehr">Medical Records</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'reviews') ? 'active' : ''; ?>" href="patient_dashboard.php?page=reviews">My Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Load Content Based on Selected Page -->
    <div class="container mt-4">
        <?php
        if ($page == 'appointments') {
            include 'patient_appointments.php';
        } elseif ($page == 'book_appointment') {
            include 'patient_book_appointment.php';
        } elseif ($page == 'ehr') {
            include 'patient_ehr.php';
        } elseif ($page == 'reviews') {
            include 'patient_reviews.php';
        } else {
        ?>
            <h3 class="mb-4">Welcome, <?= $patient_name; ?> 👋</h3>
            <div class="row">
                <div class="col-md-3">
                    <a href="patient_dashboard.php?page=book_appointment" class="btn btn-primary w-100 p-3">📅 Book an Appointment</a>
                </div>
                <div class="col-md-3">
                    <a href="patient_dashboard.php?page=appointments" class="btn btn-success w-100 p-3">📄 View My Appointments</a>
                </div>
                <div class="col-md-3">
                    <a href="patient_dashboard.php?page=ehr" class="btn btn-info w-100 p-3">📜 My Medical Records</a>
                </div>
                <div class="col-md-3">
                    <a href="patient_dashboard.php?page=reviews" class="btn btn-warning w-100 p-3">⭐ My Reviews</a>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
