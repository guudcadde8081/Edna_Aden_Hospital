<?php
include 'config/db.php';


if (!isset($_GET['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_GET['id'];
$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password='$hashed' WHERE id=$user_id";
    if ($conn->query($sql) === TRUE) {
        $success = "Password has been reset successfully.";
    } else {
        $error = "Error resetting password: " . $conn->error;
    }
}

// Fetch user name
$result = $conn->query("SELECT name FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();
?>

<div class="container">
    <h3>Reset Password for <?= htmlspecialchars($user['name']); ?></h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
