<?php
include 'config/db.php';

// Count total users (excluding admin)
$sql_users = "SELECT COUNT(*) AS total_users FROM users WHERE role != 'admin'";
$total_users = $conn->query($sql_users)->fetch_assoc()['total_users'];

// Count total doctors
$sql_doctors = "SELECT COUNT(*) AS total_doctors FROM users WHERE role = 'doctor'";
$total_doctors = $conn->query($sql_doctors)->fetch_assoc()['total_doctors'];

// Count total patients
$sql_patients = "SELECT COUNT(*) AS total_patients FROM users WHERE role = 'patient'";
$total_patients = $conn->query($sql_patients)->fetch_assoc()['total_patients'];

// Count total appointments
$sql_appointments = "SELECT COUNT(*) AS total_appointments FROM appointments";
$total_appointments = $conn->query($sql_appointments)->fetch_assoc()['total_appointments'];

// Count pending appointments
$sql_pending = "SELECT COUNT(*) AS pending FROM appointments WHERE status = 'pending'";
$pending_appointments = $conn->query($sql_pending)->fetch_assoc()['pending'];

// Count approved appointments
$sql_approved = "SELECT COUNT(*) AS approved FROM appointments WHERE status = 'approved'";
$approved_appointments = $conn->query($sql_approved)->fetch_assoc()['approved'];

// Count canceled appointments
$sql_canceled = "SELECT COUNT(*) AS canceled FROM appointments WHERE status = 'cancelled'";
$canceled_appointments = $conn->query($sql_canceled)->fetch_assoc()['canceled'];
?>


<p>Welcome to the Admin Panel. Below is a quick overview of the system.</p>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <h4>Total Users</h4>
                <p class="fs-3"><?= $total_users; ?></p>
              
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <h4>Total Doctors</h4>
                <p class="fs-3"><?= $total_doctors; ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-warning text-dark mb-3">
            <div class="card-body">
                <h4>Total Patients</h4>
                <p class="fs-3"><?= $total_patients; ?></p>
            </div>
        </div>
    </div>

    

   
</div>
