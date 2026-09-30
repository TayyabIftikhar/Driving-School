<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiver_id_raw = (int)$_POST['receiver_id']; // 0 means All Users
    $receiver_id = ($receiver_id_raw === 0) ? "NULL" : (int)$receiver_id_raw;
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $type = $_POST['type'];
    $sender_id = $_SESSION['user_id'];

    // Insert message
    $sql = "INSERT INTO messages (sender_id, receiver_id, content, type) 
            VALUES ($sender_id, $receiver_id, '$content', '$type')";
    $conn->query($sql);

    // Send notifications
    if ($receiver_id_raw === 0) {
        $users = $conn->query("SELECT id, email FROM users");
        while ($u = $users->fetch_assoc()) {
            if ($type == 'email') {
                send_email($u['email'], 'Message from Admin', $content);
            } elseif ($type == 'sms') {
                // you may need to join students/instructors to get phone numbers
                if (!empty($u['phone'])) send_sms($u['phone'], $content);
            }
        }
    } else {
        $user = $conn->query("SELECT email FROM users WHERE id=$receiver_id_raw")->fetch_assoc();
        if ($type == 'email') {
            send_email($user['email'], 'Message from Admin', $content);
        } elseif ($type == 'sms') {
            if (!empty($user['phone'])) send_sms($user['phone'], $content);
        }
    }
}

$users = $conn->query("SELECT id, username FROM users");
?>
<?php include '../includes/header.php'; ?>
<h2>Communications</h2>
<form method="POST" onsubmit="return validateForm(this)">
    <select name="receiver_id" required>
        <option value="0">All Users</option>
        <?php while($u = $users->fetch_assoc()): ?>
            <option value="<?php echo $u['id']; ?>"><?php echo $u['username']; ?></option>
        <?php endwhile; ?>
    </select>
    <select name="type" required>
        <option value="email">Email</option>
        <option value="sms">SMS</option>
        <option value="inapp">In-App</option>
    </select>
    <textarea name="content" required placeholder="Message Content"></textarea>
    <button type="submit">Send</button>
</form>

<h3>Message History</h3>
<?php
$history = $conn->query("SELECT m.*, u1.username AS sender_name, u2.username AS receiver_name
                         FROM messages m
                         JOIN users u1 ON m.sender_id = u1.id
                         LEFT JOIN users u2 ON m.receiver_id = u2.id
                         ORDER BY m.date DESC LIMIT 10");
?>
<table>
    <tr>
        <th>ID</th>
        <th>Sender</th>
        <th>Receiver</th>
        <th>Content</th>
        <th>Type</th>
        <th>Date</th>
    </tr>
    <?php while($row = $history->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['sender_name']; ?></td>
            <td><?php echo $row['receiver_name'] ? $row['receiver_name'] : 'All'; ?></td>
            <td><?php echo htmlspecialchars($row['content']); ?></td>
            <td><?php echo $row['type']; ?></td>
            <td><?php echo $row['date']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<?php include '../includes/footer.php'; ?>
