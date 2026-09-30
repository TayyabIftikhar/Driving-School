<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) header("Location: ../login.php");
include '../includes/db_connect.php';

// Stats
$student_count = $conn->query("SELECT COUNT(*) FROM students")->fetch_row()[0];
$instructor_count = $conn->query("SELECT COUNT(*) FROM instructors")->fetch_row()[0];
$revenue = $conn->query("SELECT SUM(amount) FROM payments")->fetch_row()[0] ?? 0;
?>
<?php include '../includes/header.php'; ?>

<style>
    .dashboard {
        max-width: 1000px;
        margin: 30px auto;
        font-family: Arial, sans-serif;
    }

    .stats {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        flex: 1;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .card h3 {
        margin-bottom: 10px;
        color: #333;
    }

    .card p {
        font-size: 20px;
        font-weight: bold;
        color: #007bff;
    }

    .links {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .links a {
        display: block;
        background: #007bff;
        color: white;
        text-decoration: none;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        transition: 0.3s;
        font-weight: bold;
    }

    .links a:hover {
        background: #0056b3;
    }
</style>

<div class="dashboard">
    <h2>Admin Dashboard</h2>

    <div class="stats">
        <div class="card">
            <h3>Students</h3>
            <p><?php echo $student_count; ?></p>
        </div>
        <div class="card">
            <h3>Instructors</h3>
            <p><?php echo $instructor_count; ?></p>
        </div>
        <div class="card">
            <h3>Revenue</h3>
            <p>$<?php echo $revenue; ?></p>
        </div>
    </div>

    <div class="links">
        <a href="manage_students.php">Manage Students</a>
        <a href="manage_instructors.php">Manage Instructors</a>
        <a href="manage_branches.php">Manage Branches</a>
        <a href="manage_courses.php">Manage Courses</a>
        <a href="manage_vehicles.php">Manage Vehicles</a>
        <a href="manage_staff.php">Manage Staff</a>
        <a href="manage_invoices.php">Manage Invoices</a>
        <a href="reports.php">Reports</a>
        <a href="communications.php">Communications</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
