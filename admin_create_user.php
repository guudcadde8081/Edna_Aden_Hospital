<?php
include 'config/db.php';

// Ensure session is only started once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
    $role = $_POST['role'] ?? '';

    // Only store these fields if the user is a doctor
    $specialization = $conn->real_escape_string($_POST['specialization'] ?? '');
    $qualifications = $conn->real_escape_string($_POST['qualifications'] ?? '');
    $available_days = isset($_POST['available_days']) ? implode(', ', $_POST['available_days']) : '';
    $available_hours = $conn->real_escape_string($_POST['available_hours'] ?? '');

    // Security question & answer validation
    $security_question = $conn->real_escape_string($_POST['security_question'] ?? '');
    $security_answer = !empty($_POST['security_answer']) ? password_hash($_POST['security_answer'], PASSWORD_DEFAULT) : '';

    $created_at = date("Y-m-d H:i:s");

    // Default profile image
    $image_path = "uploads/default.png";

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                $error = "Image upload failed.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }

    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        $error = "Email already exists! Please use a different email.";
    } else {
        // Insert new user into database
        $insert_sql = "INSERT INTO users (name, email, password, role, specialization, qualifications, available_days, available_hours, security_question, security_answer, image, created_at)
                       VALUES ('$name', '$email', '$password', '$role', '$specialization', '$qualifications', '$available_days', '$available_hours', '$security_question', '$security_answer', '$image_path', '$created_at')";

        if ($conn->query($insert_sql) === TRUE) {
            $success = "New user registered successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<h3>Add New User</h3>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success; ?></div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Security Question</label>
        <input type="text" name="security_question" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Security Answer</label>
        <input type="text" name="security_answer" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-control" id="roleSelect" required>
            <option value="doctor">Doctor</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <!-- Doctor-Specific Fields -->
    <div id="doctorFields" style="display: none;">
        <div class="mb-3">
            <label class="form-label">Specialization</label>
            <input type="text" name="specialization" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Qualifications</label>
            <input type="text" name="qualifications" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Available Days</label><br>
            <input type="checkbox" name="available_days[]" value="Monday"> Monday
            <input type="checkbox" name="available_days[]" value="Tuesday"> Tuesday
            <input type="checkbox" name="available_days[]" value="Wednesday"> Wednesday
            <input type="checkbox" name="available_days[]" value="Thursday"> Thursday
            <input type="checkbox" name="available_days[]" value="Friday"> Friday
            <input type="checkbox" name="available_days[]" value="Saturday"> Saturday
            <input type="checkbox" name="available_days[]" value="Sunday"> Sunday
        </div>

        <div class="mb-3">
            <label class="form-label">Available Hours</label>
            <input type="text" name="available_hours" class="form-control">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Profile Picture</label>
        <input type="file" name="image" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Register User</button>
</form>

<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    document.getElementById('doctorFields').style.display = this.value === 'doctor' ? 'block' : 'none';
});
</script>
