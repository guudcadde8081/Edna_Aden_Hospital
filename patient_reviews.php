<?php
include 'config/db.php';
$patient_id = $_SESSION['user_id'];

$sql_appointments = "SELECT a.id, d.id AS doctor_id, d.name AS doctor_name 
                     FROM appointments a
                     JOIN users d ON a.doctor_id = d.id
                     WHERE a.patient_id='$patient_id' AND a.status='approved'
                     ORDER BY a.appointment_date DESC";
$appointments = $conn->query($sql_appointments);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doctor_id'])) {
    $doctor_id = $_POST['doctor_id'];
    $rating = $_POST['rating'];
    $comment = $conn->real_escape_string($_POST['comment']);

    $insert_review = "INSERT INTO reviews (doctor_id, patient_id, rating, comment) VALUES ('$doctor_id', '$patient_id', '$rating', '$comment')";
    
    if ($conn->query($insert_review) === TRUE) {
        $success = "Review submitted successfully!";
    } else {
        $error = "Error submitting review.";
    }
}
?>

<h3>Leave a Review</h3>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success; ?></div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error; ?></div>
<?php endif; ?>

<form method="POST">
    <select name="doctor_id" class="form-control mb-3" required>
        <option value="">-- Select Doctor --</option>
        <?php while ($row = $appointments->fetch_assoc()): ?>
            <option value="<?= $row['doctor_id']; ?>"><?= $row['doctor_name']; ?></option>
        <?php endwhile; ?>
    </select>
    <textarea name="comment" class="form-control mb-3" placeholder="Leave a review..." required></textarea>
    <button type="submit" class="btn btn-primary">Submit Review</button>
</form>
