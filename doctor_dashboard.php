<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}
$page = $_GET['page'] ?? 'home';
$doctorName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Doctor';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    .feature-card {
        border-radius: 20px;
        background-color: #ced4da;
        text-align: center;
        padding: 30px 15px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        transition: 0.2s ease-in-out;
        color: #212529;
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
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
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #6ec6ff;">
  <div class="container-fluid">
    <a class="navbar-brand" href="doctor_dashboard.php">Doctor Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link <?= $page == 'appointments' ? 'active' : ''; ?>" href="doctor_dashboard.php?page=appointments">Appointments</a></li>
        <li class="nav-item"><a class="nav-link <?= $page == 'medical_records' ? 'active' : ''; ?>" href="doctor_dashboard.php?page=medical_records">Medical Records</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
            <img src="icons/user.png" alt="Profile" width="30" height="30" class="rounded-circle me-2">
            Dr. <?= htmlspecialchars($doctorName); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="doctor_dashboard.php?page=profile">My Profile</a></li>
            <li><a class="dropdown-item" href="doctor_dashboard.php?page=edit_profile">Edit Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Dashboard Content -->
<div class="container mt-4">
<?php
if ($page == 'appointments') {
    include 'doctor_appointments.php';
} elseif ($page == 'medical_records') {
    include 'doctor_medical_records.php';
} elseif ($page == 'profile') {
    include 'doctor_profile.php';
} elseif ($page == 'edit_profile') {
    include 'doctor_profile_edit.php';
} else {
?>
  <h3 class="mb-4">Welcome, Dr. <?= htmlspecialchars($doctorName); ?></h3>

  <div class="row g-4">
    <div class="col-6 col-md-4 col-lg-3">
      <a href="doctor_dashboard.php?page=appointments" class="text-decoration-none text-dark">
        <div class="feature-card">
          <img src="icons/calendar.png" alt="Appointments">
          <h6>Manage Appointments</h6>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
      <a href="doctor_dashboard.php?page=medical_records" class="text-decoration-none text-dark">
        <div class="feature-card">
          <img src="icons/medical-report.png" alt="Medical Records">
          <h6>Medical Records</h6>
        </div>
      </a>
    </div>

    <div class="col-6 col-md-4 col-lg-3">
      <a href="doctor_dashboard.php?page=profile" class="text-decoration-none text-dark">
        <div class="feature-card">
          <img src="icons/user.png" alt="Profile">
          <h6>My Profile</h6>
        </div>
      </a>
    </div>
  </div>

<?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
