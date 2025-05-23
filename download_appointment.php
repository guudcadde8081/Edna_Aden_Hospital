<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("No appointment ID provided.");
}

$appointment_id = $conn->real_escape_string($_GET['id']);
$patient_id = $_SESSION['user_id'];

// Fetch appointment details
$sql = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.status, 
               d.name AS doctor_name, d.specialization, 
               p.name AS patient_name
        FROM appointments a
        JOIN users d ON a.doctor_id = d.id
        JOIN users p ON a.patient_id = p.id
        WHERE a.appointment_id = '$appointment_id' AND a.patient_id = '$patient_id'
        LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Appointment not found or access denied.");
}

$row = $result->fetch_assoc();

// Base64 encode logo
$logo_path = 'logo.png'; // ensure this file exists in the same folder
$logo_type = pathinfo($logo_path, PATHINFO_EXTENSION);
$logo_data = base64_encode(file_get_contents($logo_path));
$logo_src = 'data:image/' . $logo_type . ';base64,' . $logo_data;

// Generate HTML content
$html = '
<div style="text-align:center;">
    <img src="' . $logo_src . '" alt="Hospital Logo" style="height:70px; margin-bottom:10px;"><br>
    <h2 style="margin:0;">Edan Adan Hospital</h2>
    <p style="font-size:14px; color:gray; margin-top:0;">Appointment Confirmation</p>
</div>
<hr>
<p><strong>Appointment ID:</strong> ' . $row['appointment_id'] . '</p>
<p><strong>Patient Name:</strong> ' . $row['patient_name'] . '</p>
<p><strong>Doctor:</strong> Dr. ' . $row['doctor_name'] . '</p>
<p><strong>Specialization:</strong> ' . $row['specialization'] . '</p>
<p><strong>Date:</strong> ' . date("F j, Y", strtotime($row['appointment_date'])) . '</p>
<p><strong>Time:</strong> ' . date("h:i A", strtotime($row['appointment_time'])) . '</p>
<p><strong>Status:</strong> ' . ucfirst($row['status']) . '</p>
<hr>
<p style="font-size:12px; color:gray;">Please arrive 15 minutes before your appointment. Show this confirmation at the reception.</p>
';

$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Appointment_" . $row['appointment_id'] . ".pdf", ["Attachment" => false]);
exit;
?>
