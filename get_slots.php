<?php
include 'config/db.php';

$doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
$date = $_GET['date'] ?? '';

header('Content-Type: application/json');

if (!$doctor_id || !$date) {
    echo json_encode(['slots' => []]);
    exit;
}

// Get doctor details
$sql = "SELECT available_days, available_hours FROM users WHERE id = '$doctor_id'";
$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo json_encode(['slots' => []]);
    exit;
}

$doctor = $result->fetch_assoc();
$available_days = explode(', ', $doctor['available_days']);
$available_hours = explode(' - ', $doctor['available_hours']);
$selected_day = date('l', strtotime($date));

// Validate day
if (!in_array($selected_day, $available_days) || count($available_hours) != 2) {
    echo json_encode(['slots' => []]);
    exit;
}

// Generate 20-minute slots
function generate_slots($start, $end) {
    $slots = [];
    $start_time = strtotime($start);
    $end_time = strtotime($end);

    while ($start_time + 20 * 60 <= $end_time) {
        $slots[] = date('H:i', $start_time);
        $start_time += 20 * 60;
    }
    return $slots;
}

$all_slots = generate_slots(trim($available_hours[0]), trim($available_hours[1]));

// Fetch already booked times
$sql_booked = "SELECT appointment_time FROM appointments 
               WHERE doctor_id = '$doctor_id' AND appointment_date = '$date' AND status != 'cancelled'";
$result_booked = $conn->query($sql_booked);
$booked_slots = [];

while ($row = $result_booked->fetch_assoc()) {
    $booked_slots[] = trim($row['appointment_time']);
}

// Filter out booked slots
$available_slots = array_values(array_diff($all_slots, $booked_slots));

echo json_encode(['slots' => $available_slots]);
?>