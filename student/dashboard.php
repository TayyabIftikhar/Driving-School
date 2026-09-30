<?php
session_start();
if ($_SESSION['role'] != 'student') header("Location: ../login.php");
include '../includes/db_connect.php';

// Get student_id safely with prepared statement
$stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if ($student) {
    $student_id = $student['id'];
    // Count enrollments for this student
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM enrollments WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $enrollments = $stmt->get_result()->fetch_assoc();
    $enrollments_count = $enrollments['count'] ?? 0; // Default to 0 if no enrollments
} else {
    $student_id = 0; // Fallback if no student record
    $enrollments_count = 0;
    echo "<p>No student record found. Please contact admin to register you as a student.</p>";
}
?>
<?php include '../includes/header.php'; ?>
<h2>Student Dashboard</h2>
<p>Enrollments: <?php echo $enrollments_count; ?></p>
