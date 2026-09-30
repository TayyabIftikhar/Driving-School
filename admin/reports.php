<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) header("Location: ../login.php");
include '../includes/db_connect.php';

// Student Progress
$student_progress = $conn->query("SELECT s.full_name, s.progress, COUNT(l.id) AS lessons_completed FROM students s LEFT JOIN enrollments e ON s.id = e.student_id LEFT JOIN lessons l ON e.id = l.enrollment_id WHERE l.status = 'completed' GROUP BY s.id");

// Instructor Performance
$instructor_perf = $conn->query("SELECT i.full_name, COUNT(l.id) AS lessons_taught, AVG(IF(l.status='completed', 1, 0)) AS completion_rate FROM instructors i LEFT JOIN lessons l ON i.id = l.instructor_id GROUP BY i.id");

// Business Metrics
$revenue = $conn->query("SELECT SUM(amount) AS total_revenue FROM payments")->fetch_assoc()['total_revenue'] ?? 0;
$outstanding = $conn->query("SELECT SUM(amount) AS total_outstanding FROM invoices WHERE status = 'pending'")->fetch_assoc()['total_outstanding'] ?? 0;
$student_retention = $conn->query("SELECT (COUNT(DISTINCT e.student_id) / COUNT(DISTINCT s.id)) * 100 AS retention_rate FROM enrollments e RIGHT JOIN students s ON e.student_id = s.id")->fetch_assoc()['retention_rate'] ?? 0;
?>
<?php include '../includes/header.php'; ?>
<h2>Reports and Analytics</h2>
<h3>Student Progress</h3>
<table>
    <tr><th>Student</th><th>Progress</th><th>Lessons Completed</th></tr>
    <?php while($row = $student_progress->fetch_assoc()): ?>
        <tr><td><?php echo $row['full_name']; ?></td><td><?php echo $row['progress']; ?></td><td><?php echo $row['lessons_completed']; ?></td></tr>
    <?php endwhile; ?>
</table>

<h3>Instructor Performance</h3>
<table>
    <tr><th>Instructor</th><th>Lessons Taught</th><th>Completion Rate</th></tr>
    <?php while($row = $instructor_perf->fetch_assoc()): ?>
        <tr><td><?php echo $row['full_name']; ?></td><td><?php echo $row['lessons_taught']; ?></td><td><?php echo round($row['completion_rate'] * 100, 2); ?>%</td></tr>
    <?php endwhile; ?>
</table>

<h3>Business Performance</h3>
<table>
    <tr><th>Metric</th><th>Value</th></tr>
    <tr><td>Total Revenue</td><td>$<?php echo $revenue; ?></td></tr>
    <tr><td>Outstanding Payments</td><td>$<?php echo $outstanding; ?></td></tr>
    <tr><td>Student Retention Rate</td><td><?php echo round($student_retention, 2); ?>%</td></tr>
</table>
<?php include '../includes/footer.php'; ?>