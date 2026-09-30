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
</body>
</html>
