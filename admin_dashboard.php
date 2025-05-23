<?php
include 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #343a40;
      padding-top: 20px;
    }
    .sidebar a {
      padding: 15px;
      text-decoration: none;
      font-size: 18px;
      color: white;
      display: block;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background-color: #575d63;
    }
    .sidebar .active {
      background-color: #007bff;
    }
    .content {
      margin-left: 260px;
      padding: 20px;
    }
    .page-header {
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      padding: 20px 10px;
      margin-bottom: 20px;
      text-align: center;
    }
    .page-header img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      margin-bottom: 5px;
    }
    .page-header h4 {
      margin: 0;
      font-weight: 600;
      font-size: 20px;
    }
  </style>
</head>
<body>

  <!-- Sidebar Menu -->
  <div class="sidebar">
    <h4 class="text-center text-white">Admin Panel</h4>
    <a href="admin_dashboard.php?page=home" class="<?= ($page == 'home') ? 'active' : ''; ?>">Dashboard</a>
    <a href="admin_dashboard.php?page=users" class="<?= ($page == 'users') ? 'active' : ''; ?>">Manage Users</a>
    <a href="admin_dashboard.php?page=appointments" class="<?= ($page == 'appointments') ? 'active' : ''; ?>">Manage Appointments</a>
    <a href="admin_dashboard.php?page=create_doctor" class="<?= ($page == 'create_doctor') ? 'active' : ''; ?>">Add New Doctor</a>
    <a href="admin_dashboard.php?page=create_admin" class="<?= ($page == 'create_admin') ? 'active' : ''; ?>">Add New Admin</a>
    <a href="logout.php" class="text-danger">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="page-header">
      <img src="logo.png" alt="Hospital Logo">
      <h4>Edna Aden Hospital - Admin Panel</h4>
    </div>

    <?php
      if ($page == 'home') {
          include 'admin_home.php';
      } elseif ($page == 'users') {
          include 'admin_users.php';
      } elseif ($page == 'create_doctor') {
          include 'admin_create_doctor.php';
      } elseif ($page == 'create_admin') {
          include 'admin_create_admin.php';
      } elseif ($page == 'reset_password') {
          include 'admin_reset_password.php';
      } elseif ($page == 'edit_user') {
          include 'admin_edit_user.php';
      } elseif ($page == 'appointments') {
          include 'admin_appointments.php';
      } else {
          echo "<h2 class='text-danger'>Page Not Found</h2>";
      }
    ?>
  </div>

</body>
</html>
