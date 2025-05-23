<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load mpdf library
include 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Fetch patient's details
$sql_patient = "SELECT name FROM users WHERE id='$patient_id'";
$patient = $conn->query($sql_patient)->fetch_assoc();

// Fetch patient's EHR records
$sql_ehr = "SELECT e.*, d.name AS doctor_name 
            FROM ehr_records e
            JOIN users d ON e.doctor_id = d.id
            WHERE e.patient_id='$patient_id'
            ORDER BY e.created_at DESC";
$ehr_records = $conn->query($sql_ehr);

// Encode logo image
$logo_path = 'logo.png'; // Ensure it exists
$logo_type = pathinfo($logo_path, PATHINFO_EXTENSION);
$logo_data = base64_encode(file_get_contents($logo_path));
$logo_src = 'data:image/' . $logo_type . ';base64,' . $logo_data;

// Create PDF document
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetTitle("Medical Records - " . $patient['name']);

$html = '
<div style="text-align:center;">
    <img src="' . $logo_src . '" alt="Hospital Logo" style="height:70px; margin-bottom:10px;"><br>
    <h2 style="margin:0;">Edna Adan Hospital</h2>
    <p style="font-size:14px; color:gray; margin-top:0;">Electronic Health Records (EHR)</p>
</div>
<hr>
<h4>Patient: ' . $patient['name'] . '</h4>
<table border="1" width="100%" cellpadding="10" cellspacing="0">
    <tr>
        <th>Date</th>
        <th>Doctor</th>
        <th>Diagnosis</th>
        <th>Prescription</th>
    </tr>';

while ($record = $ehr_records->fetch_assoc()) {
    $html .= '<tr>
                <td>' . date("F j, Y", strtotime($record['created_at'])) . '</td>
                <td>' . $record['doctor_name'] . '</td>
                <td>' . $record['diagnosis'] . '</td>
                <td>' . $record['prescription'] . '</td>
              </tr>';
}

$html .= '</table>';

// Write content to PDF
$mpdf->WriteHTML($html);

// Output the PDF file for download
$mpdf->Output("EHR_" . $patient['name'] . ".pdf", "D");
exit;
?>
