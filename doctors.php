<?php
include 'config/db.php';

// Fetch all doctors
$sql = "SELECT id, name, specialization FROM users WHERE role='doctor'";
$doctors = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors - Appointment System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Our Doctors</h2>

        <input type="text" id="search" class="form-control mb-3" placeholder="Search by name or specialization...">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Profile</th>
                </tr>
            </thead>
            <tbody id="doctorTable">
                <?php while ($doctor = $doctors->fetch_assoc()): ?>
                    <tr>
                        <td><?= $doctor['name']; ?></td>
                        <td><?= $doctor['specialization']; ?></td>
                        <td><a href="doctor_profile.php?id=<?= $doctor['id']; ?>" class="btn btn-info btn-sm">View Profile</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search Functionality
        document.getElementById('search').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#doctorTable tr');
            rows.forEach(row => {
                let name = row.cells[0].textContent.toLowerCase();
                let specialization = row.cells[1].textContent.toLowerCase();
                row.style.display = (name.includes(filter) || specialization.includes(filter)) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
