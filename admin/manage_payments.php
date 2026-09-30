<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) header("Location: ../login.php");
include '../includes/db_connect.php';

$invoice_id = (int)($_GET['invoice_id'] ?? 0);

// Validate invoice_id
$valid_invoice = false;
$invoice = null;
if ($invoice_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM invoices WHERE id = ?");
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $valid_invoice = true;
        $invoice = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $valid_invoice) {
    $amount = (float)$_POST['amount'];
    $date = $_POST['date'];
    $method = mysqli_real_escape_string($conn, $_POST['method']);

    // Use prepared statement to insert payment
    $stmt = $conn->prepare("INSERT INTO payments (invoice_id, amount, date, method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $invoice_id, $amount, $date, $method);
    if ($stmt->execute()) {
        // Update invoice status
        $total_paid = $conn->query("SELECT SUM(amount) FROM payments WHERE invoice_id=$invoice_id")->fetch_row()[0];
        $invoice_amount = $invoice['amount'];
        if ($total_paid >= $invoice_amount) {
            $conn->query("UPDATE invoices SET status='paid' WHERE id=$invoice_id");
        }
        echo "Payment added successfully!";
    } else {
        echo "Error adding payment: " . $conn->error;
    }
}

// List payments for invoice
$sql = "SELECT * FROM payments WHERE invoice_id=$invoice_id";
$result = $conn->query($sql);
?>
<?php include '../includes/header.php'; ?>
<h2>Manage Payments for Invoice #<?php echo $invoice_id; ?></h2>
<?php if (!$valid_invoice): ?>
    <p>Invalid or missing invoice ID. Please select a valid invoice.</p>
<?php else: ?>
    <form method="POST" onsubmit="return validateForm(this)">
    <input type="number" name="amount" required placeholder="Amount" step="0.01"
           value="<?php echo htmlspecialchars($invoice['amount']); ?>" readonly>
    <input type="date" name="date" required
           value="<?php echo date('Y-m-d'); ?>">
    <select name="method" required>
        <option value="cash">Cash</option>
        <option value="card">Card</option>
        <option value="online">Online</option>
    </select>
    <button type="submit">Add Payment</button>
</form>

    <table>
        <tr><th>ID</th><th>Amount</th><th>Date</th><th>Method</th></tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td>$<?php echo $row['amount']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['method']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>
