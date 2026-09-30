<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header("Location: ../login.php");
    exit;
}
include '../includes/db_connect.php';

$search = $_GET['search'] ?? '';

// -----------------------------
// Fetch instructor for editing
// -----------------------------
$edit_instructor = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM instructors WHERE id = $edit_id AND is_active = 1");
    if ($res && $res->num_rows > 0) {
        $edit_instructor = $res->fetch_assoc();
    }
}

// -----------------------------
// Handle update (no new creation)
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id'])) {
    $id = (int)$_POST['id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $qualifications = mysqli_real_escape_string($conn, $_POST['qualifications']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability']);

    $sql = "UPDATE instructors SET full_name=?, qualifications=?, availability=? WHERE id=? AND is_active=1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $full_name, $qualifications, $availability, $id);

    if ($stmt->execute()) {
        // Notes/Attachments
        if (!empty($_POST['note'])) {
            $note = mysqli_real_escape_string($conn, $_POST['note']);
            $attachment = '';
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
                $attachment = '../uploads/' . basename($_FILES['attachment']['name']);
                move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment);
            }
            $sql_note = "INSERT INTO notes (related_id, type, content, attachment) VALUES (?, 'instructor', ?, ?)";
            $stmt_note = $conn->prepare($sql_note);
            $stmt_note->bind_param("iss", $id, $note, $attachment);
            $stmt_note->execute();
        }
    } else {
        die("Error updating instructor: " . $conn->error);
    }

    header("Location: manage_instructors.php");
    exit;
}

// -----------------------------
// Soft delete instructor
// -----------------------------
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("UPDATE instructors SET is_active = 0 WHERE id=$id");
    header("Location: manage_instructors.php");
    exit;
}

// -----------------------------
// List instructors (only active)
// -----------------------------
$where = "WHERE i.is_active = 1";
if ($search) {
    $where .= " AND (i.full_name LIKE '%$search%' OR i.qualifications LIKE '%$search%')";
}

$sql = "SELECT i.*, u.email 
        FROM instructors i 
        JOIN users u ON i.user_id = u.id 
        $where";
$result = $conn->query($sql);
?>
<?php include '../includes/header.php'; ?>
<h2>Manage Instructors</h2>

<!-- Search -->
<form method="GET">
    <input type="text" name="search" placeholder="Search instructors" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<!-- Edit Form -->
<?php if ($edit_instructor): ?>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $edit_instructor['id']; ?>">

    <input type="text" name="full_name" required placeholder="Full Name"
           value="<?php echo htmlspecialchars($edit_instructor['full_name']); ?>">

    <textarea name="qualifications" required placeholder="Qualifications"><?php echo htmlspecialchars($edit_instructor['qualifications']); ?></textarea>

    <textarea name="availability" placeholder="Availability (e.g., Mon-Fri 9-5)"><?php echo htmlspecialchars($edit_instructor['availability']); ?></textarea>

    <textarea name="note" placeholder="Add Note"></textarea>
    <input type="file" name="attachment">
    <button type="submit">Update Instructor</button>
</form>
<?php endif; ?>

<!-- Instructors Table -->
<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Qualifications</th><th>Availability</th><th>Actions</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['qualifications']; ?></td>
            <td><?php echo $row['availability']; ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include '../includes/footer.php'; ?>
