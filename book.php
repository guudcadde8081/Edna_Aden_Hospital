<?php
include 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Fetch all doctors for the dropdown
$sql_doctors = "SELECT id, name, specialization FROM users WHERE role='doctor'";
$doctors = $conn->query($sql_doctors);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $appointment_datetime = $appointment_date . " " . $appointment_time;

    // Check if the doctor is available at the selected time
    $check_availability = "SELECT * FROM appointments WHERE doctor_id='$doctor_id' AND appointment_date='$appointment_datetime' AND status='approved'";
    $availability_result = $conn->query($check_availability);

    if ($availability_result->num_rows > 0) {
        $error = "The selected doctor is not available at this time.";
    } else {
        // Insert the appointment request as "pending"
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) 
                VALUES ('$patient_id', '$doctor_id', '$appointment_datetime', 'pending')";

        if ($conn->query($sql) === TRUE) {
            $success = "Appointment request submitted! Awaiting confirmation.";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Patient Dashboard</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Book a New Appointment</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Doctor</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">-- Choose a Doctor --</option>
                    <?php while ($doctor = $doctors->fetch_assoc()): ?>
                        <option value="<?= $doctor['id']; ?>">
                            <?= $doctor['name']; ?> - <?= $doctor['specialization']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Select Date</label>
                <input type="date" name="appointment_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Select Time</label>
                <input type="time" name="appointment_time" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Book Appointment</button>
        </form>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
