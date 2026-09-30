<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin')) {
    header("Location: ../login.php");  // Admin only
    exit;
}
include '../includes/db_connect.php';

// Handle Add Staff
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check for duplicate username/email
    $check = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
    if ($check->num_rows > 0) {
        $message = "⚠️ Username or Email already exists!";
    } else {
        $sql = "INSERT INTO users (username, password, role, email) 
                VALUES ('$username', '$password', 'staff', '$email')";
        if ($conn->query($sql)) {
            $message = "✅ Staff added successfully!";
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
}

// Handle Delete Staff
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='staff'");
    header("Location: manage_staff.php");
    exit;
}

// Fetch staff list
$sql = "SELECT * FROM users WHERE role = 'staff'";
$result = $conn->query($sql);
?>
<?php include '../includes/header.php'; ?>
<h2>Manage Staff</h2>

<?php if ($message): ?>
    <p style="color: red;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST" onsubmit="return validateForm(this)">
    <input type="text" name="username" required placeholder="Username">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Add Staff</button>
</form>

<table>
    <tr><th>ID</th><th>Username</th><th>Email</th><th>Actions</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this staff?')">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>
<?php include '../includes/footer.php'; ?>
