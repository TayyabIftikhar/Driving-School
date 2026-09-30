<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['student', 'instructor'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/db_connect.php';

// Current logged in user
$user_id = $_SESSION['user_id'];

// Fetch messages where this user is receiver OR sent to all (receiver_id = 0 or NULL)
$sql = "SELECT m.*, u.username AS sender_name
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE m.receiver_id = ? OR m.receiver_id = 0 OR m.receiver_id IS NULL
        ORDER BY m.date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Origin Driving School - Messages</title>
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

        nav {
            margin-top: 12px;
        }

        nav a {
            color: #F1F5F9;
            text-decoration: none;
            margin: 0 12px;
            font-weight: 500;
            transition: color 0.3s ease, background 0.3s ease;
            padding: 8px 14px;
            border-radius: 6px;
        }

        nav a:hover {
            background-color: #0D9488;
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

        td {
            word-wrap: break-word;
            max-width: 300px;
            text-align: left;
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
        <h2 style="text-align:center; margin-top:20px;">My Received Messages</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Sender</th>
                <th>Content</th>
                <th>Type</th>
                <th>Date</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['sender_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['content']); ?></td>
                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No messages received yet.</td>
                </tr>
            <?php endif; ?>
        </table>
    </main>
</body>
</html>
