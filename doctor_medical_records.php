<?php
include 'config/db.php';
$doctor_id = $_SESSION['user_id'];

// Fetch patients from approved appointments
$sql_patients = "SELECT DISTINCT u.id, u.name FROM users u
                 JOIN appointments a ON u.id = a.patient_id
                 WHERE a.doctor_id='$doctor_id' AND a.status='approved'";
$patients = $conn->query($sql_patients);

// Handle adding medical records
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['patient_id'])) {
    $patient_id = $_POST['patient_id'];
    $appointment_id = $_POST['appointment_id'];
    $diagnosis = $conn->real_escape_string($_POST['diagnosis']);
    $prescription = $conn->real_escape_string($_POST['prescription']);

    $insert_ehr = "INSERT INTO ehr_records (patient_id, doctor_id, appointment_id, diagnosis, prescription) 
                   VALUES ('$patient_id', '$doctor_id', '$appointment_id', '$diagnosis', '$prescription')";
    
    if ($conn->query($insert_ehr) === TRUE) {
        $success = "Medical record added successfully!";
    } else {
        $error = "Error adding record.";
    }
}
?>

<h3>Add Medical Record</h3>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success; ?></div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Select Patient</label>
        <select name="patient_id" class="form-control" required>
            <option value="">-- Select Patient --</option>
            <?php while ($row = $patients->fetch_assoc()): ?>
                <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Select Appointment</label>
        <select name="appointment_id" class="form-control" required>
            <option value="">-- Select Appointment --</option>
            <?php
            $sql_appointments = "SELECT id, appointment_date, appointment_time FROM appointments WHERE doctor_id='$doctor_id' AND status='approved'";
            $appointments_result = $conn->query($sql_appointments);
            while ($appointment = $appointments_result->fetch_assoc()):
            ?>
                <option value="<?= $appointment['id']; ?>">
                    <?= $appointment['appointment_date']; ?> at <?= date("h:i A", strtotime($appointment['appointment_time'])); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Diagnosis</label>
        <textarea name="diagnosis" class="form-control" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Prescription</label>
        <textarea name="prescription" class="form-control" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Medical Record</button>
</form>
