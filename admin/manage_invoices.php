<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) header("Location: ../login.php");
include '../includes/db_connect.php';

// Generate invoice on new enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll'])) {
    $student_id = (int)$_POST['student_id'];
    $course_id = (int)$_POST['course_id'];
    $start_date = $_POST['start_date'];
    $sql_enroll = "INSERT INTO enrollments (student_id, course_id, start_date) VALUES ($student_id, $course_id, '$start_date')";
    $conn->query($sql_enroll);
    $enrollment_id = $conn->insert_id;

    // Get price from course
    $course = $conn->query("SELECT price FROM courses WHERE id=$course_id")->fetch_assoc();
    $amount = $course['price'];
    $due_date = date('Y-m-d', strtotime('+30 days'));
    $sql_invoice = "INSERT INTO invoices (student_id, amount, due_date) VALUES ($student_id, $amount, '$due_date')";
    $conn->query($sql_invoice);

    // Send reminder
    $student = $conn->query("SELECT u.email, s.phone 
                             FROM students s 
                             JOIN users u ON s.user_id = u.id 
                             WHERE s.id=$student_id")->fetch_assoc();
    send_email($student['email'], 'New Invoice', "Invoice for $amount due on $due_date.");
    send_sms($student['phone'], "New invoice for $amount due on $due_date.");
}

// List invoices
$sql = "SELECT i.*, s.full_name 
        FROM invoices i 
        JOIN students s ON i.student_id = s.id";
$result = $conn->query($sql);

// Students with usernames
$students = $conn->query("SELECT s.id, u.username 
                          FROM students s 
                          JOIN users u ON s.user_id = u.id");

$courses = $conn->query("SELECT id, name FROM courses");
?>
<?php include '../includes/header.php'; ?>
<h2>Manage Invoices</h2>
<h3>Enroll Student to Generate Invoice</h3>
<form method="POST" onsubmit="return validateForm(this)">
    <select name="student_id" required>
        <?php while($s = $students->fetch_assoc()): ?>
            <option value="<?php echo $s['id']; ?>"><?php echo $s['username']; ?></option>
        <?php endwhile; ?>
    </select>
    <select name="course_id" required>
        <?php while($c = $courses->fetch_assoc()): ?>
            <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <input type="date" name="start_date" required>
    <button type="submit" name="enroll">Enroll and Generate Invoice</button>
</form>
<table>
    <tr><th>ID</th><th>Student</th><th>Amount</th><th>Due Date</th><th>Status</th><th>Actions</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td>$<?php echo $row['amount']; ?></td>
            <td><?php echo $row['due_date']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <?php if ($row['status'] != 'paid'): ?>
                    <a href="manage_payments.php?invoice_id=<?php echo $row['id']; ?>">Add Payment</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include '../includes/footer.php'; ?>
