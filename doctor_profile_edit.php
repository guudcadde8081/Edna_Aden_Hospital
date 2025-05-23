<?php
include 'config/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $specialization = $conn->real_escape_string($_POST['specialization']);
    $qualifications = $conn->real_escape_string($_POST['qualifications']);
    $experience = (int)$_POST['experience'];
    $languages = $conn->real_escape_string($_POST['languages']);
    $fee = (float)$_POST['fee'];
    $available_days = is_array($_POST['available_days'] ?? null) ? implode(", ", $_POST['available_days']) : '';
    $available_hours = $conn->real_escape_string($_POST['start_time'] . " - " . $_POST['end_time']);

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $update = "UPDATE users SET name='$name', specialization='$specialization', qualifications='$qualifications', experience='$experience',
                   languages='$languages', fee='$fee', available_days='$available_days', available_hours='$available_hours', image='$target_file'
                   WHERE id='$doctor_id'";
    } else {
        $update = "UPDATE users SET name='$name', specialization='$specialization', qualifications='$qualifications', experience='$experience',
                   languages='$languages', fee='$fee', available_days='$available_days', available_hours='$available_hours'
                   WHERE id='$doctor_id'";
    }

    $success = $conn->query($update) ? "Profile updated successfully." : "Error: " . $conn->error;
}

$sql = "SELECT * FROM users WHERE id='$doctor_id'";
$doctor = $conn->query($sql)->fetch_assoc();
$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
$current_days = explode(", ", $doctor['available_days']);
$hour_options = '';
for ($h = 0; $h < 24; $h++) {
    for ($m = 0; $m < 60; $m += 30) {
        $time = date("H:i", strtotime("$h:$m"));
        $hour_options .= "<option value='$time'>$time</option>";
    }
}
?>

<div class="container mt-4">
    <h4>Edit My Profile</h4>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" value="<?= $doctor['name']; ?>" required></div>
        <div class="mb-3"><label>Specialization</label><input type="text" name="specialization" class="form-control" value="<?= $doctor['specialization']; ?>"></div>
        <div class="mb-3"><label>Qualifications</label><input type="text" name="qualifications" class="form-control" value="<?= $doctor['qualifications']; ?>"></div>
        <div class="mb-3"><label>Experience (years)</label><input type="number" name="experience" class="form-control" value="<?= $doctor['experience']; ?>"></div>
        <div class="mb-3"><label>Languages</label><input type="text" name="languages" class="form-control" value="<?= $doctor['languages']; ?>"></div>
        <div class="mb-3"><label>Consultation Fee ($)</label><input type="number" step="0.01" name="fee" class="form-control" value="<?= $doctor['fee']; ?>"></div>

        <div class="mb-3">
            <label>Available Days</label><br>
            <?php foreach ($days as $day): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="available_days[]" value="<?= $day; ?>" <?= in_array($day, $current_days) ? 'checked' : ''; ?>>
                    <label class="form-check-label"><?= $day; ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label>Available Hours</label>
            <div class="d-flex gap-3">
                <select name="start_time" class="form-select"><?= $hour_options; ?></select>
                <select name="end_time" class="form-select"><?= $hour_options; ?></select>
            </div>
        </div>

        <div class="mb-3"><label>Profile Image</label><input type="file" name="image" class="form-control"></div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
