<?php
include 'config/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle user soft deletion or reactivation
$delete_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = (int)$_POST['user_id'];
    if (isset($_POST['delete_user'])) {
        if ($_SESSION['user_id'] == $user_id) {
            $delete_error = "You cannot deactivate yourself.";
        } else {
            $conn->query("UPDATE users SET is_active = 0 WHERE id = $user_id");
        }
    } elseif (isset($_POST['reactivate_user'])) {
        $conn->query("UPDATE users SET is_active = 1 WHERE id = $user_id");
    }
}

// Determine filter
$role_filter = $_GET['role'] ?? 'all';
$status_filter = $_GET['status'] ?? 'active';

$where_clause = "WHERE 1=1";
if (in_array($role_filter, ['admin', 'doctor', 'patient'])) {
    $where_clause .= " AND role = '$role_filter'";
}
if ($status_filter === 'active') {
    $where_clause .= " AND is_active = 1";
} elseif ($status_filter === 'inactive') {
    $where_clause .= " AND is_active = 0";
}

// Fetch users
$sql_users = "SELECT id, name, email, role, is_active FROM users $where_clause";
$users = $conn->query($sql_users);
?>

<h2>Manage Users</h2>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link <?= $role_filter == 'all' ? 'active' : ''; ?>" href="admin_dashboard.php?page=users&role=all">All Roles</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $role_filter == 'admin' ? 'active' : ''; ?>" href="admin_dashboard.php?page=users&role=admin">Admins</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $role_filter == 'doctor' ? 'active' : ''; ?>" href="admin_dashboard.php?page=users&role=doctor">Doctors</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $role_filter == 'patient' ? 'active' : ''; ?>" href="admin_dashboard.php?page=users&role=patient">Patients</a>
    </li>
</ul>

<!-- Status Filter -->
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link <?= $status_filter == 'active' ? 'active' : ''; ?>" href="admin_dashboard.php?page=users&role=<?= $role_filter; ?>&status=active">Active</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $status_filter == 'inactive' ? 'active' : ''; ?>" href="admin_dashboard.php?page=users&role=<?= $role_filter; ?>&status=inactive">Inactive</a>
    </li>
</ul>

<?php if ($delete_error): ?>
    <div class="alert alert-danger"><?= $delete_error; ?></div>
<?php endif; ?>

<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= ucfirst($user['role']); ?></td>
                <td><?= $user['is_active'] ? 'Active' : 'Inactive'; ?></td>
                <td>
                    <?php if ($user['is_active']): ?>
                        <a href="admin_dashboard.php?page=edit_user&id=<?= $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="admin_dashboard.php?page=reset_password&id=<?= $user['id']; ?>" class="btn btn-primary btn-sm">Reset Password</a>
                        <?php if ($_SESSION['user_id'] != $user['id']): ?>
                            <form method="POST" action="" class="d-inline">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-danger btn-sm" onclick="return confirm('Deactivate this user?');">Deactivate</button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <form method="POST" action="" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <button type="submit" name="reactivate_user" class="btn btn-success btn-sm">Reactivate</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
