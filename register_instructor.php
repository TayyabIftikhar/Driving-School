<?php
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username       = mysqli_real_escape_string($conn, $_POST['username']);
    $email          = mysqli_real_escape_string($conn, $_POST['email']);
    $password       = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $full_name      = mysqli_real_escape_string($conn, $_POST['full_name']);
    $qualifications = mysqli_real_escape_string($conn, $_POST['qualifications']);

    try {
        // Insert into users
        $sql = "INSERT INTO users (username, password, role, email) 
                VALUES ('$username', '$password', 'instructor', '$email')";
        
        if ($conn->query($sql)) {
            $user_id = $conn->insert_id;

            // Insert into instructors table
            $sql_inst = "INSERT INTO instructors (user_id, full_name, qualifications) 
                         VALUES ($user_id, '$full_name', '$qualifications')";
            $conn->query($sql_inst);

            // Redirect to login on success
            header("Location: login.php");
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Duplicate entry error (MySQL 1062)
        if ($e->getCode() == 1062) {
            echo "<script>alert('⚠️ Username or Email already exists! Please choose another.'); window.history.back();</script>";
        } else {
            echo "<script>alert('❌ Registration failed: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Register as Instructor</h2>
<form method="POST" onsubmit="return validateForm(this)">
    <input type="text" name="username" required placeholder="Username">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <input type="text" name="full_name" required placeholder="Full Name">
    <textarea name="qualifications" required placeholder="Qualifications"></textarea>
    <button type="submit">Register</button>
</form>
<?php include 'includes/footer.php'; ?>