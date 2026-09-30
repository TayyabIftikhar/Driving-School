<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connect.php';

// --- Fetch data for edit ---
$edit_course = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM courses WHERE id = $edit_id AND is_active = 1");
    if ($res && $res->num_rows > 0) {
        $edit_course = $res->fetch_assoc();
    }
}

// --- Handle Add/Edit ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $num_classes = (int)$_POST['num_classes'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if ($id) {
        $sql = "UPDATE courses 
                SET name='$name', price=$price, num_classes=$num_classes, description='$description' 
                WHERE id=$id";
    } else {
        $sql = "INSERT INTO courses (name, price, num_classes, description, is_active) 
                VALUES ('$name', $price, $num_classes, '$description', 1)";
    }
    $conn->query($sql);

    // Redirect back to avoid resubmission
    header("Location: manage_courses.php");
    exit;
}

// --- Soft Delete ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("UPDATE courses SET is_active = 0 WHERE id=$id");
    header("Location: manage_courses.php");
    exit;
}

// --- List active courses only ---
$sql = "SELECT * FROM courses WHERE is_active = 1";
$result = $conn->query($sql);
?>
<?php include '../includes/header.php'; ?>
<h2>Manage Courses</h2>

<form method="POST" onsubmit="return validateForm(this)">
    <input type="hidden" name="id" value="<?php echo $edit_course['id'] ?? ''; ?>">

    <input type="text" name="name" required placeholder="Course Name"
           value="<?php echo htmlspecialchars($edit_course['name'] ?? ''); ?>">

    <input type="number" name="price" required placeholder="Price" step="0.01"
           value="<?php echo htmlspecialchars($edit_course['price'] ?? ''); ?>">

    <input type="number" name="num_classes" required placeholder="Number of Classes"
           value="<?php echo htmlspecialchars($edit_course['num_classes'] ?? ''); ?>">

    <textarea name="description" placeholder="Description"><?php echo htmlspecialchars($edit_course['description'] ?? ''); ?></textarea>

    <button type="submit">Save Course</button>
</form>

<h3>Active Courses</h3>
<table>
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Classes</th><th>Description</th><th>Actions</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td>$<?php echo $row['price']; ?></td>
            <td><?php echo $row['num_classes']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<?php include '../includes/footer.php'; ?>
