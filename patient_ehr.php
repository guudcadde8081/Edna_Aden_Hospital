<?php
include 'config/db.php';
$patient_id = $_SESSION['user_id'];

$sql_ehr = "SELECT e.*, d.name AS doctor_name 
            FROM ehr_records e
            JOIN users d ON e.doctor_id = d.id
            WHERE e.patient_id='$patient_id'
            ORDER BY e.created_at DESC";
$ehr_records = $conn->query($sql_ehr);
?>

<h3>Your Medical History</h3>
<a href="download_ehr.php" class="btn btn-primary mb-3">Download as PDF</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Date</th>
            <th>Doctor</th>
            <th>Diagnosis</th>
            <th>Prescription</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($record = $ehr_records->fetch_assoc()): ?>
            <tr>
                <td><?= date("F j, Y", strtotime($record['created_at'])); ?></td>
                <td><?= $record['doctor_name']; ?></td>
                <td><?= $record['diagnosis']; ?></td>
                <td><?= $record['prescription']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
