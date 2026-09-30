<?php
session_start();
if ($_SESSION['role'] != 'instructor') header("Location: ../login.php");
include '../includes/db_connect.php';

// Handle lesson completion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lesson_id'])) {
    $lesson_id = (int)$_POST['lesson_id'];
    $stmt = $conn->prepare("SELECT instructor_id FROM lessons WHERE id = ?");
    $stmt->bind_param("i", $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lesson = $result->fetch_assoc();
    
    if ($lesson && $lesson['instructor_id'] == $conn->query("SELECT id FROM instructors WHERE user_id=" . $_SESSION['user_id'])->fetch_assoc()['id']) {
        $stmt = $conn->prepare("UPDATE lessons SET status = 'completed' WHERE id = ?");
        $stmt->bind_param("i", $lesson_id);
        $stmt->execute();
    }
}

// Fetch instructor's lessons
$instructor_id = $conn->query("SELECT id FROM instructors WHERE user_id=" . $_SESSION['user_id'])->fetch_assoc()['id'];
$stmt = $conn->prepare("SELECT l.*, s.full_name AS student 
                        FROM lessons l 
                        JOIN enrollments e ON l.enrollment_id = e.id 
                        JOIN students s ON e.student_id = s.id 
                        WHERE l.instructor_id = ?");
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Origin Driving School</title>
    <link rel="stylesheet" href="/school/css/styles.css">
    <style>
        /* Header styling */
        header {
            background-color: #0F172A; /* Dark Gray/Blue */
            color: #F1F5F9; /* Light Gray text */
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
            color: #0D9488; /* Teal accent */
        }

        /* Navigation links */
        nav {
            margin-top: 12px;
        }

        nav a {
            color: #F1F5F9; /* Soft white */
            text-decoration: none;
            margin: 0 12px;
            font-weight: 500;
            transition: color 0.3s ease, background 0.3s ease;
            padding: 8px 14px;
            border-radius: 6px;
        }

        nav a:hover {
            background-color: #0D9488; /* Teal hover */
            color: #ffffff;
        }

        /* Table styling */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #0D9488;
            color: #fff;
        }

        button {
            padding: 6px 12px;
            border: none;
            background: #0D9488;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0F766E;
        }
    </style>
</head>
<body>
    <header>
        <h1>Origin Driving School Management System</h1>
        <nav>
            <a href="/school/index.php">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff'): ?>
                    <a href="/school/admin/dashboard.php">Admin Dashboard</a>
                <?php elseif ($_SESSION['role'] == 'instructor'): ?>
                    <a href="/school/instructor/dashboard.php">Instructor Dashboard</a>
                    <a href="/school/instructor/messages.php">Received Messages</a>
                <?php elseif ($_SESSION['role'] == 'student'): ?>
                    <a href="/school/student/dashboard.php">Dashboard</a>
                    <a href="/school/student/progress.php">Progress</a>
                    <a href="/school/student/book_lesson.php">Bookings</a>
                    <a href="/school/student/messages.php">Messages</a>
                <?php endif; ?>
                <a href="/school/logout.php">Logout</a>
            <?php else: ?>
                <a href="/school/login.php">Login</a>
                <a href="/school/register_student.php">Register as Student</a>
                <a href="/school/register_instructor.php">Register as Instructor</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h2 style="text-align:center; margin-top:20px;">My Schedule</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['student']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['time']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php if ($row['status'] == 'scheduled'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="lesson_id" value="<?php echo $row['id']; ?>">
                                <button type="submit">Complete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
