<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load mpdf library
include 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Appointment ID is required.");
}

$appointment_id = $conn->real_escape_string($_GET['id']);
$patient_id = $_SESSION['user_id'];

// Fetch patient and appointment info
$sql_appt = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, d.name AS doctor_name, p.name AS patient_name
             FROM appointments a
             JOIN users d ON a.doctor_id = d.id
             JOIN users p ON a.patient_id = p.id
             WHERE a.appointment_id = '$appointment_id' AND a.patient_id = '$patient_id'";
$appt_result = $conn->query($sql_appt);

if ($appt_result->num_rows === 0) {
    die("Appointment not found or access denied.");
}

$appt = $appt_result->fetch_assoc();

// Fetch EHR records linked to this appointment
$sql_ehr = "SELECT * FROM ehr_records WHERE appointment_id = '$appointment_id'";
$ehr_result = $conn->query($sql_ehr);

// Encode logo
$logo_path = 'logo.png';
$logo_type = pathinfo($logo_path, PATHINFO_EXTENSION);
$logo_data = base64_encode(file_get_contents($logo_path));
$logo_src = 'data:image/' . $logo_type . ';base64,' . $logo_data;

$html = '
<div style="text-align:center;">
    <img src="' . $logo_src . '" alt="Hospital Logo" style="height:70px; margin-bottom:10px;"><br>
    <h2 style="margin:0;">Guud Medical Hospital</h2>
    <p style="font-size:14px; color:gray; margin-top:0;">Appointment Medical Report</p>
</div>
<hr>
<p><strong>Appointment ID:</strong> ' . $appt['appointment_id'] . '</p>
<p><strong>Patient:</strong> ' . $appt['patient_name'] . '</p>
<p><strong>Doctor:</strong> Dr. ' . $appt['doctor_name'] . '</p>
<p><strong>Date:</strong> ' . date("F j, Y", strtotime($appt['appointment_date'])) . '</p>
<p><strong>Time:</strong> ' . date("h:i A", strtotime($appt['appointment_time'])) . '</p>
<hr>';

// Include EHR data
if ($ehr_result->num_rows > 0) {
    $html .= '<table border="1" width="100%" cellpadding="10" cellspacing="0">
                <tr>
                    <th>Date</th>
                    <th>Diagnosis</th>
                    <th>Prescription</th>
                </tr>';
    while ($record = $ehr_result->fetch_assoc()) {
        $html .= '<tr>
                    <td>' . date("F j, Y", strtotime($record['created_at'])) . '</td>
                    <td>' . $record['diagnosis'] . '</td>
                    <td>' . $record['prescription'] . '</td>
                  </tr>';
    }
    $html .= '</table>';
} else {
    $html .= '<p style="color:red;">No medical records found for this appointment.</p>';
}

$mpdf = new \Mpdf\Mpdf();
$mpdf->SetTitle("Medical_Record_" . $appt['appointment_id']);
$mpdf->WriteHTML($html);
$mpdf->Output("Medical_Record_" . $appt['appointment_id'] . ".pdf", "D");
exit;
?>
