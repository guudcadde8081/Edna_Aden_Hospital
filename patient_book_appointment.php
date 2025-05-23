
<?php
include 'config/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

$sql_doctors = "SELECT id, name, specialization, qualifications, available_days, available_hours, image, experience, languages, fee 
                FROM users WHERE role = 'doctor'";
$doctors = $conn->query($sql_doctors);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doctor_id'])) {
    $patient_id = $_SESSION['user_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Check for existing appointment at the same time
    $check_slot = $conn->query("SELECT * FROM appointments WHERE doctor_id = '$doctor_id' AND appointment_date = '$appointment_date' AND appointment_time = '$appointment_time' AND status != 'cancelled'");
    if ($check_slot->num_rows > 0) {
        $error = "This time slot is already booked. Please choose another.";
    } else {
        do {
            $appointment_id = 'APPT-' . strtoupper(bin2hex(random_bytes(4)));
            $check = $conn->query("SELECT * FROM appointments WHERE appointment_id = '$appointment_id'");
        } while ($check->num_rows > 0);

        $sql = "INSERT INTO appointments (appointment_id, patient_id, doctor_id, appointment_date, appointment_time, status)
                VALUES ('$appointment_id', '$patient_id', '$doctor_id', '$appointment_date', '$appointment_time', 'pending')";

        if ($conn->query($sql) === TRUE) {
            $success = "Appointment booked successfully! ID: <strong>$appointment_id</strong>";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Available Doctors</h2>
    <?php if (isset($success)): ?><div class="alert alert-success"><?= $success; ?></div><?php endif; ?>
    <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>

    <div class="row">
        <?php while ($doctor = $doctors->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= $doctor['image']; ?>" alt="Doctor Image" class="rounded-circle me-3" width="60" height="60" style="object-fit: cover;">
                    <div class="flex-grow-1">
                        <strong>Dr. <?= $doctor['name']; ?></strong><br>
                        <small><?= $doctor['experience'] ?? '1' ?> years experience</small>
                    </div>
                    <span class="text-warning fw-bold"></span>
                </div>
                <ul class="list-unstyled mb-3">
                    <li><strong>Specialization:</strong> <?= $doctor['specialization']; ?></li>
                    <li><strong>Languages:</strong> <?= $doctor['languages'] ?? 'English'; ?></li>
                    <li><strong>Consultation Fee:</strong> <?= $doctor['fee'] ? '$' . $doctor['fee'] : '$3.00'; ?></li>
                </ul>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="doctor_profile.php?id=<?= $doctor['id']; ?>" class="btn btn-outline-primary btn-sm">View Profile</a>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookAppointment<?= $doctor['id']; ?>">Book Appointment</button>
                </div>
            </div>
        </div>

        <!-- Booking Modal -->
        <div class="modal fade" id="bookAppointment<?= $doctor['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Book Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="doctor_id" value="<?= $doctor['id']; ?>">
                        <div class="mb-3">
                            <label>Select Date</label>
                            <input type="date" name="appointment_date" class="form-control" required onchange="loadSlots(this, <?= $doctor['id']; ?>)">
                        </div>
                        <div class="mb-3" id="slotContainer<?= $doctor['id']; ?>">
                            <label>Select Time</label>
                            <select name="appointment_time" class="form-select" required>
                                <option value="">Select a date first</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
function loadSlots(dateInput, doctorId) {
    const container = document.querySelector(`#slotContainer${doctorId} select`);
    const selectedDate = dateInput.value;
    if (!selectedDate) return;
    fetch(`get_slots.php?doctor_id=${doctorId}&date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            container.innerHTML = "";
            if (data.slots.length === 0) {
                container.innerHTML = "<option disabled>No available slots</option>";
            } else {
                data.slots.forEach(time => {
                    const opt = document.createElement("option");
                    opt.value = time;
                    opt.textContent = time;
                    container.appendChild(opt);
                });
            }
        });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
