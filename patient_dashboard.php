<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}
$page = $_GET['page'] ?? 'home';
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Patient';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
.feature-card {
    border-radius: 20px;
    background-color: #dee2e6;
    text-align: center;
    padding: 30px 15px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    transition: 0.2s ease-in-out;
    color: #212529;
}
.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1);
}
.feature-card img {
    width: 60px;
    height: 60px;
    margin-bottom: 15px;
}
.feature-card h6 {
    font-weight: 600;
    font-size: 16px;
    margin: 0;
    color: #212529;
}
.logo-header {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
.logo-header img {
    height: 60px;
    margin-right: 15px;
}
  </style>
</head>
<body>

<!-- Logo Header -->
<div class="logo-header">
  <img src="logo.png" alt="Hospital Logo">
  <h4 class="mb-0">Edna Aden Hospital</h4>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="patient_dashboard.php">Dashboard</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link <?= $page == 'appointments' ? 'active' : ''; ?>" href="patient_dashboard.php?page=appointments">Appointments</a></li>
        <li class="nav-item"><a class="nav-link <?= $page == 'ehr' ? 'active' : ''; ?>" href="patient_dashboard.php?page=ehr">Medical Records</a></li>
        <li class="nav-item"><a class="nav-link <?= $page == 'reviews' ? 'active' : ''; ?>" href="patient_dashboard.php?page=reviews">Reviews</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Dashboard Content -->
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
  <h3 class="mb-4">Welcome, <?= htmlspecialchars($userName); ?></h3>

  <div class="row g-4">
    <div class="col-6 col-md-4 col-lg-3">
      <a href="patient_dashboard.php?page=book_appointment" class="text-decoration-none text-dark">
        <div class="feature-card">
          <img src="icons/calendar.png" alt="Book Appointment">
          <h6>Book Appointment</h6>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
      <a href="patient_dashboard.php?page=appointments" class="text-decoration-none text-dark">
        <div class="feature-card">
          <img src="icons/checklist.png" alt="Appointments">
          <h6>My Appointments</h6>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
      <a href="patient_dashboard.php?page=ehr" class="text-decoration-none text-dark">
        <div class="feature-card">
          <img src="icons/medical-report.png" alt="Medical Records">
          <h6>Medical Records</h6>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
      <a href="patient_dashboard.php?page=reviews" class="text-decoration-none text-dark">
        <div class="feature-card">
          <img src="icons/star.png" alt="Reviews">
          <h6>My Reviews</h6>
        </div>
      </a>
    </div>
  </div>

<?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
