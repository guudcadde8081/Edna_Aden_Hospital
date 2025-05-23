<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load mpdf library
include 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$appointment_id = $_GET['appointment_id'] ?? null;

$sql = "SELECT e.*, d.name AS doctor_name, a.appointment_date, a.appointment_time 
        FROM ehr_records e
        JOIN appointments a ON e.patient_id = a.patient_id AND a.id = e.appointment_id
        JOIN users d ON e.doctor_id = d.id
        WHERE a.id = '$appointment_id' AND a.patient_id = '$patient_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("No medical record found for this appointment.");
}

$record = $result->fetch_assoc();

$mpdf = new \Mpdf\Mpdf();
$mpdf->SetTitle("Medical Record - Appointment {$record['appointment_date']}");

$html = '
<style>
    .record-section { margin-bottom: 20px; }
    .label { font-weight: bold; }
</style>
<div style="text-align:center;">
    <h2>Edna Adan Hospital</h2>
    <h3>Medical Record</h3>
</div>
<hr>
<div class="record-section">
    <p><span class="label">Doctor:</span> Dr. ' . $record['doctor_name'] . '</p>
    <p><span class="label">Appointment Date:</span> ' . $record['appointment_date'] . ' at ' . date("h:i A", strtotime($record['appointment_time'])) . '</p>
</div>
<hr>
<div class="record-section">
    <h4>Diagnosis</h4>
    <p>' . nl2br($record['diagnosis']) . '</p>
</div>
<hr>
<div class="record-section">
    <h4>Prescription</h4>
    <p>' . nl2br($record['prescription']) . '</p>
</div>
';

$mpdf->WriteHTML($html);
$mpdf->Output("Medical_Record_{$record['appointment_date']}.pdf", "D");
exit;
?>
