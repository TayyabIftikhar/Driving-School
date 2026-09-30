<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connect.php';

// -----------------------------
// Add or Update Branch
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    if ($id) {
        $sql = "UPDATE branches SET name='$name', location='$location' WHERE id=$id";
    } else {
        $sql = "INSERT INTO branches (name, location, is_active) VALUES ('$name', '$location', 1)";
    }
    $conn->query($sql);
    header("Location: manage_branches.php");
    exit;
}

// -----------------------------
// Soft delete branch
// -----------------------------
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("UPDATE branches SET is_active = 0 WHERE id=$id");
    header("Location: manage_branches.php");
    exit;
}

// -----------------------------
// Restore branch
// -----------------------------
if (isset($_GET['restore'])) {
    $id = (int)$_GET['restore'];
    $conn->query("UPDATE branches SET is_active = 1 WHERE id=$id");
    header("Location: manage_branches.php");
    exit;
}

// -----------------------------
// Fetch Active Branches
// -----------------------------
$sql = "SELECT * FROM branches WHERE is_active = 1";
$result = $conn->query($sql);

// -----------------------------
// Fetch Deleted Branches
// -----------------------------
$deleted = $conn->query("SELECT * FROM branches WHERE is_active = 0");
?>
<?php include '../includes/header.php'; ?>
<h2>Manage Branches</h2>

<!-- Branch Form -->
<form method="POST" onsubmit="return validateForm(this)">
    <input type="hidden" name="id" value="">
    <input type="text" name="name" required placeholder="Branch Name">
    <textarea name="location" required placeholder="Location"></textarea>
    <button type="submit">Save Branch</button>
</form>

<!-- Active Branches -->
<h3>Active Branches</h3>
<table>
    <tr><th>ID</th><th>Name</th><th>Location</th><th>Actions</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td>
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this branch?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- Deleted Branches -->
<h3>Deleted Branches</h3>
<?php if ($deleted->num_rows > 0): ?>
<table>
    <tr><th>ID</th><th>Name</th><th>Location</th><th>Actions</th></tr>
    <?php while($row = $deleted->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td>
                <a href="?restore=<?php echo $row['id']; ?>" onclick="return confirm('Restore this branch?');">Restore</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p>No deleted branches.</p>
<?php endif; ?>


