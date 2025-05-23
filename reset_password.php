<?php
include 'config/db.php';
session_start();

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['security_question'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$security_question = $_SESSION['security_question'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $answer = $_POST['answer'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Check security answer
    $sql = "SELECT security_answer FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if (password_verify($answer, $user['security_answer'])) {
        // Update password
        $update_sql = "UPDATE users SET password='$new_password' WHERE email='$email'";
        if ($conn->query($update_sql) === TRUE) {
            session_destroy();
            header("Location: login.php?success=Password reset successfully! You can now log in.");
            exit();
        } else {
            $error = "Error updating password.";
        }
    } else {
        $error = "Incorrect answer!";
    }
}
?>

<h2>Reset Password</h2>
<p>Security Question: <strong><?= $security_question; ?></strong></p>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Your Answer</label>
        <input type="text" name="answer" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Reset Password</button>
</form>
