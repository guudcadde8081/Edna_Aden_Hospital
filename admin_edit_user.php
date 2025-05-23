<?php
include 'config/db.php';
// Start session only if it's not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get user ID from URL
if (!isset($_GET['id'])) {
    die("User ID not provided.");
}

$user_id = $_GET['id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

// Handle form submission for updating user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = isset($_POST['specialization']) ? $_POST['specialization'] : NULL;

    // Update user details in database
    if ($user['role'] == 'doctor') {
        $update_sql = "UPDATE users SET name='$name', email='$email', specialization='$specialization' WHERE id='$user_id'";
    } else {
        $update_sql = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";
    }

    if ($conn->query($update_sql) === TRUE) {
        $success = "User details updated successfully!";
        $user['name'] = $name;
        $user['email'] = $email;
        if ($user['role'] == 'doctor') {
            $user['specialization'] = $specialization;
        }
    } else {
        $error = "Error updating user: " . $conn->error;
    }
}
?>

<h2>Edit User</h2>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success; ?></div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" value="<?= $user['name']; ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= $user['email']; ?>" required>
    </div>
    <?php if ($user['role'] == 'doctor'): ?>
        <div class="mb-3">
            <label class="form-label">Specialization</label>
            <input type="text" name="specialization" class="form-control" value="<?= $user['specialization']; ?>" required>
        </div>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="admin_dashboard.php?page=users" class="btn btn-secondary">Cancel</a>
</form>
