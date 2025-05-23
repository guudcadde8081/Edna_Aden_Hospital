<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $sql = "SELECT security_question FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        session_start();
        $_SESSION['reset_email'] = $email;
        $_SESSION['security_question'] = $user['security_question'];
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "Email not found!";
    }
}
?>

<h2>Forgot Password</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error; ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label class="form-label">Enter Your Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Next</button>
</form>
