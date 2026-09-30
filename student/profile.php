<?php
session_start();
if ($_SESSION['role'] != 'student') header("Location: ../login.php");
include '../includes/db_connect.php';

$student_id = $conn->query("SELECT id FROM students WHERE user_id=" . $_SESSION['user_id'])->fetch_assoc()['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $sql = "UPDATE students SET full_name='$full_name', phone='$phone', address='$address' WHERE id=$student_id";
    $conn->query($sql);

    // Notes
    if (!empty($_POST['note'])) {
        $note = mysqli_real_escape_string($conn, $_POST['note']);
        $attachment = '';
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
            $attachment = '../uploads/' . basename($_FILES['attachment']['name']);
            move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment);
        }
        $sql_note = "INSERT INTO notes (related_id, type, content, attachment) VALUES ($student_id, 'student', '$note', '$attachment')";
        $conn->query($sql_note);
    }
}

$profile = $conn->query("SELECT * FROM students WHERE id=$student_id")->fetch_assoc();
?>
<?php include '../includes/header.php'; ?>
<h2>My Profile</h2>
<form method="POST" enctype="multipart/form-data" onsubmit="return validateForm(this)">
    <input type="text" name="full_name" value="<?php echo $profile['full_name']; ?>" required>
    <input type="text" name="phone" value="<?php echo $profile['phone']; ?>" required>
    <textarea name="address" placeholder="Address"><?php echo $profile['address']; ?></textarea>
    <textarea name="note" placeholder="Add Note"></textarea>
    <input type="file" name="attachment">
    <button type="submit">Update Profile</button>
</form>
<?php include '../includes/footer.php'; ?>