<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header("Location: ../login.php");
    exit;
}
include '../includes/db_connect.php';

$search = $_GET['search'] ?? '';

// Soft Delete student
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("UPDATE students SET is_active = 0 WHERE id = $id");
    header("Location: manage_students.php");
    exit;
}

// Fetch student if editing
$edit_student = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM students WHERE id = $edit_id AND is_active = 1");
    if ($res && $res->num_rows > 0) {
        $edit_student = $res->fetch_assoc();
    }
}

// Handle update only
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id'])) {
    $id = (int)$_POST['id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $license_status = mysqli_real_escape_string($conn, $_POST['license_status']);
    $progress = mysqli_real_escape_string($conn, $_POST['progress']);

    $sql = "UPDATE students 
            SET full_name=?, phone=?, license_status=?, progress=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $full_name, $phone, $license_status, $progress, $id);

    if ($stmt->execute()) {
        // Optional: Add note
        if (!empty($_POST['note'])) {
            $note = mysqli_real_escape_string($conn, $_POST['note']);
            $attachment = '';
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
                $attachment = '../uploads/' . basename($_FILES['attachment']['name']);
                move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment);
            }
            $sql_note = "INSERT INTO notes (related_id, type, content, attachment) VALUES (?, 'student', ?, ?)";
            $stmt_note = $conn->prepare($sql_note);
            $stmt_note->bind_param("iss", $id, $note, $attachment);
            $stmt_note->execute();
        }
    } else {
        die("Error updating student: " . $conn->error);
    }

    header("Location: manage_students.php");
    exit;
}

// List with search (only active students)
$where = "WHERE s.is_active = 1";
if ($search) {
    $where .= " AND (s.full_name LIKE '%$search%' OR s.phone LIKE '%$search%')";
}

$sql = "SELECT s.*, u.email 
        FROM students s 
        JOIN users u ON s.user_id = u.id 
        $where";
$result = $conn->query($sql);
?>
<?php include '../includes/header.php'; ?>
<h2>Manage Students</h2>

<!-- Search -->
<form method="GET">
    <input type="text" name="search" placeholder="Search students" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<!-- Edit Form -->
<?php if ($edit_student): ?>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $edit_student['id']; ?>">

    <input type="text" name="full_name" required placeholder="Full Name"
           value="<?php echo htmlspecialchars($edit_student['full_name']); ?>">

    <input type="text" name="phone" required placeholder="Phone"
           value="<?php echo htmlspecialchars($edit_student['phone']); ?>">

    <select name="license_status" required>
        <option value="learner" <?php echo ($edit_student['license_status']=='learner') ? 'selected' : ''; ?>>Learner</option>
        <option value="provisional" <?php echo ($edit_student['license_status']=='provisional') ? 'selected' : ''; ?>>Provisional</option>
        <option value="full" <?php echo ($edit_student['license_status']=='full') ? 'selected' : ''; ?>>Full</option>
    </select>

    <textarea name="progress" placeholder="Progress"><?php echo htmlspecialchars($edit_student['progress']); ?></textarea>

    <textarea name="note" placeholder="Add Note"></textarea>
    <input type="file" name="attachment">
    <button type="submit">Update Student</button>
</form>
<?php endif; ?>

<!-- Students Table -->
<table border="1" cellpadding="5" cellspacing="0">
    <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Status</th><th>Progress</th><th>Actions</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['license_status']; ?></td>
            <td><?php echo $row['progress']; ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to deactivate this student?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include '../includes/footer.php'; ?>
