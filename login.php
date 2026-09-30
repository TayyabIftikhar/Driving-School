<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] == 'admin' || $user['role'] == 'staff') header("Location: admin/dashboard.php");
            elseif ($user['role'] == 'instructor') header("Location: instructor/dashboard.php");
            elseif ($user['role'] == 'student') header("Location: student/dashboard.php");
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Login</h2>
<form method="POST" onsubmit="return validateForm(this)">
    <input type="text" name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Login</button>
</form>
<?php include 'includes/footer.php'; ?>