<?php
include 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id']) && isset($_POST['status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Update appointment status
    $update_sql = "UPDATE appointments SET status='$status' WHERE id='$appointment_id'";
    
    if ($conn->query($update_sql) === TRUE) {
        header("Location: admin_dashboard.php?page=appointments&success=Appointment Updated");
        exit();
    } else {
        header("Location: admin_dashboard.php?page=appointments&error=Failed to update appointment");
        exit();
    }
} else {
    header("Location: admin_dashboard.php?page=appointments");
    exit();
}
?>
