<?php
include 'config/db.php';

$doctor_id = $_GET['doctor_id'];
$bookedSlots = [];

$sql = "SELECT appointment_time FROM appointments WHERE doctor_id='$doctor_id'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $bookedSlots[] = $row['appointment_time'];
}

echo json_encode($bookedSlots);
?>
