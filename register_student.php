<?php
include 'includes/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $password  = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);

    try {
        // Insert into users table
        $sql_user = "INSERT INTO users (username, password, role, email) 
                     VALUES ('$username', '$password', 'student', '$email')";
        
        if ($conn->query($sql_user)) {
            $user_id = $conn->insert_id;

            // Insert into students table with foreign key to users.id
            $sql_student = "INSERT INTO students (user_id, full_name, phone) 
                            VALUES ($user_id, '$full_name', '$phone')";
            $conn->query($sql_student);

            // Redirect to login
            header("Location: login.php");
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            // Duplicate username or email
            echo "<script>alert('⚠️ Username or Email already exists!'); window.history.back();</script>";
        } else {
            echo "<script>alert('❌ Registration failed: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Register as Student</h2>
<form method="POST">
    <input type="text" name="username" required placeholder="Username">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <input type="text" name="full_name" required placeholder="Full Name">
    <input type="text" name="phone" required placeholder="Phone">
    <button type="submit">Register</button>
</form>
<?php include 'includes/footer.php'; ?>
