<?php
include 'config/db.php';


$doctor_id = $_SESSION['user_id'] ?? 0;

$sql = "SELECT name, image, specialization, qualifications, experience, languages, fee, available_days, available_hours 
        FROM users WHERE id = '$doctor_id' AND role = 'doctor'";
$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning'>Doctor profile not found.</div>";
    exit();
}
$doctor = $result->fetch_assoc();
?>

<div class="container mt-4">
    <div class="card p-4 shadow-sm">
        <div class="text-center mb-3">
            <img src="<?= $doctor['image']; ?>" alt="Doctor Image" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
        </div>
        <h3 class="text-center">Dr. <?= htmlspecialchars($doctor['name']); ?></h3>

        <ul class="list-group mt-3">
            <li class="list-group-item"><strong>Specialization:</strong> <?= $doctor['specialization']; ?></li>
            <li class="list-group-item"><strong>Qualifications:</strong> <?= $doctor['qualifications']; ?></li>
            <li class="list-group-item"><strong>Experience:</strong> <?= $doctor['experience']; ?> years</li>
            <li class="list-group-item"><strong>Languages:</strong> <?= $doctor['languages']; ?></li>
            <li class="list-group-item"><strong>Consultation Fee:</strong> $<?= $doctor['fee']; ?></li>
            <li class="list-group-item"><strong>Available Days:</strong> <?= $doctor['available_days']; ?></li>
            <li class="list-group-item"><strong>Available Hours:</strong> <?= $doctor['available_hours']; ?></li>
        </ul>
    </div>
</div>
