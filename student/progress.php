<?php
session_start();
if ($_SESSION['role'] != 'student') header("Location: ../login.php");
include '../includes/db_connect.php';

// Get student_id safely
$stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$student_id = $student['id'] ?? 0;

// Current date for comparison
$current_date = date('Y-m-d');

// Fetch completed and scheduled lessons
$completed_lessons = [];
$scheduled_lessons = [];
if ($student_id > 0) {
    $stmt = $conn->prepare("
        SELECT 
            c.name AS course_name,
            COUNT(l.id) AS lessons_attended,
            i.full_name AS instructor_name,
            l.date,
            l.time,
            l.status
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        JOIN lessons l ON e.id = l.enrollment_id
        JOIN instructors i ON l.instructor_id = i.id
        WHERE e.student_id = ? 
        GROUP BY c.name, i.full_name, l.date, l.time, l.status
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $all_lessons = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($all_lessons as $lesson) {
        if ($lesson['status'] == 'completed') {
            $completed_lessons[] = $lesson;
        } elseif ($lesson['status'] == 'scheduled') {
            // Check if the lesson date is in the past
            $lesson_date = $lesson['date'];
            if ($lesson_date < $current_date) {
                $lesson['status'] = 'unattended';
            }
            $scheduled_lessons[] = $lesson;
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<h2>My Progress</h2>
<?php if (empty($completed_lessons) && empty($scheduled_lessons)): ?>
    <p>No lessons found. Your progress will appear here once you book or complete a lesson.</p>
<?php else: ?>
    <?php if (!empty($completed_lessons)): ?>
        <h3>Completed Lessons</h3>
        <table>
            <tr>
                <th>Course Name</th>
                <th>Lessons Attended</th>
                <th>Instructor Name</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            <?php foreach ($completed_lessons as $lesson): ?>
                <tr>
                    <td><?php echo htmlspecialchars($lesson['course_name']); ?></td>
                    <td><?php echo $lesson['lessons_attended']; ?></td>
                    <td><?php echo htmlspecialchars($lesson['instructor_name']); ?></td>
                    <td><?php echo htmlspecialchars($lesson['date']); ?></td>
                    <td><?php echo htmlspecialchars($lesson['time']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if (!empty($scheduled_lessons)): ?>
        <h3>Scheduled Lessons</h3>
        <table>
            <tr>
                <th>Course Name</th>
                <th>Lessons Attended</th>
                <th>Instructor Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php foreach ($scheduled_lessons as $lesson): ?>
                <tr>
                    <td><?php echo htmlspecialchars($lesson['course_name']); ?></td>
                    <td><?php echo $lesson['lessons_attended']; ?></td>
                    <td><?php echo htmlspecialchars($lesson['instructor_name']); ?></td>
                    <td><?php echo htmlspecialchars($lesson['date']); ?></td>
                    <td><?php echo htmlspecialchars($lesson['time']); ?></td>
                    <td><?php echo htmlspecialchars($lesson['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>