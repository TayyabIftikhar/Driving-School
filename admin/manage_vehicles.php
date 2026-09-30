<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header("Location: ../login.php");
    exit;
}
include '../includes/db_connect.php';

// Handle insert/update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? 0;
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $registration = mysqli_real_escape_string($conn, $_POST['registration']);
    $branch_id = (int)$_POST['branch_id'];
    $schedule = mysqli_real_escape_string($conn, $_POST['schedule']);

    if ($id) {
        $sql = "UPDATE vehicles 
                SET model='$model', registration='$registration', branch_id=$branch_id, schedule='$schedule' 
                WHERE id=$id";
    } else {
        $sql = "INSERT INTO vehicles (model, registration, branch_id, schedule) 
                VALUES ('$model', '$registration', $branch_id, '$schedule')";
    }
    $conn->query($sql);
    header("Location: manage_vehicles.php");
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM vehicles WHERE id=$id");
    header("Location: manage_vehicles.php");
    exit;
}

// Fetch vehicle if editing
$edit_vehicle = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_vehicle = $conn->query("SELECT * FROM vehicles WHERE id=$edit_id")->fetch_assoc();
}

// Fetch all vehicles with branch name
$sql = "SELECT v.*, b.name AS branch_name 
        FROM vehicles v 
        JOIN branches b ON v.branch_id = b.id";
$result = $conn->query($sql);

// Fetch branches for dropdown
$branches = $conn->query("SELECT * FROM branches");
?>
<?php include '../includes/header.php'; ?>
<h2>Manage Vehicles (Fleets)</h2>

<form method="POST" onsubmit="return validateForm(this)">
    <input type="hidden" name="id" value="<?php echo $edit_vehicle['id'] ?? ''; ?>">

    <input type="text" name="model" required placeholder="Model"
           value="<?php echo htmlspecialchars($edit_vehicle['model'] ?? ''); ?>">

    <input type="text" name="registration" required placeholder="Registration"
           value="<?php echo htmlspecialchars($edit_vehicle['registration'] ?? ''); ?>">

    <select name="branch_id" required>
        <option value="">-- Select Branch --</option>
        <?php
        $branches->data_seek(0); // reset pointer
        while ($b = $branches->fetch_assoc()):
            $selected = ($edit_vehicle && $edit_vehicle['branch_id'] == $b['id']) ? 'selected' : '';
        ?>
            <option value="<?php echo $b['id']; ?>" <?php echo $selected; ?>>
                <?php echo $b['name']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <textarea name="schedule" placeholder="Schedule (e.g., Available Mon-Fri)"><?php 
        echo htmlspecialchars($edit_vehicle['schedule'] ?? ''); 
    ?></textarea>

    <button type="submit"><?php echo $edit_vehicle ? 'Update Vehicle' : 'Save Vehicle'; ?></button>
    <?php if ($edit_vehicle): ?>
        <a href="manage_vehicles.php"><button type="button">Cancel</button></a>
    <?php endif; ?>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Model</th>
        <th>Registration</th>
        <th>Branch</th>
        <th>Schedule</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['model']; ?></td>
            <td><?php echo $row['registration']; ?></td>
            <td><?php echo $row['branch_name']; ?></td>
            <td><?php echo $row['schedule']; ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include '../includes/footer.php'; ?>
