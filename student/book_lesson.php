<?php
session_start();
if ($_SESSION['role'] != 'student') header("Location: ../login.php");
include '../includes/db_connect.php';

$student_id = $conn->query("SELECT id FROM students WHERE user_id=" . $_SESSION['user_id'])->fetch_assoc()['id'];

// Get active enrollment
$enrollment = $conn->query("SELECT id FROM enrollments WHERE student_id=$student_id AND status='active' LIMIT 1")->fetch_assoc();
$enrollment_id = $enrollment['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $enrollment_id) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $instructor_id = (int)$_POST['instructor_id'];
    $vehicle_id = (int)$_POST['vehicle_id'];

    try {
        $sql = "INSERT INTO lessons (enrollment_id, date, time, instructor_id, vehicle_id) 
                VALUES ($enrollment_id, '$date', '$time', $instructor_id, $vehicle_id)";
        if ($conn->query($sql)) {
            // Send reminder
            $user = $conn->query("SELECT email, phone FROM users u 
                                  JOIN students s ON u.id = s.user_id 
                                  WHERE s.id=$student_id")->fetch_assoc();
            send_email($user['email'], 'Lesson Booked', "Lesson on $date at $time.");
            send_sms($user['phone'], "Lesson booked on $date at $time.");
            echo "<script>alert('Booked successfully!');</script>";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Duplicate entry
            echo "<script>alert('⚠️ Cannot double book. Please choose another time.');</script>";
        } else {
            echo "<script>alert('An unexpected error occurred.');</script>";
        }
    }
}

$instructors = $conn->query("SELECT id, full_name FROM instructors");
$vehicles = $conn->query("SELECT id, model FROM vehicles");
?>
<?php include '../includes/header.php'; ?>
<h2>Book Lesson</h2>
<?php if (!$enrollment_id): ?>
    <p>Please enroll in a course first via admin.</p>
<?php else: ?>
<form method="POST" onsubmit="return validateForm(this)">
    <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
    
    <!-- Time restricted to 9:00 - 17:00 -->
    <input type="time" name="time" required min="09:00" max="17:00">
    
    <select name="instructor_id" required>
        <?php while($i = $instructors->fetch_assoc()): ?>
            <option value="<?php echo $i['id']; ?>"><?php echo $i['full_name']; ?></option>
        <?php endwhile; ?>
    </select>
    
    <select name="vehicle_id" required>
        <?php while($v = $vehicles->fetch_assoc()): ?>
            <option value="<?php echo $v['id']; ?>"><?php echo $v['model']; ?></option>
        <?php endwhile; ?>
    </select>
    
    <button type="submit">Book</button>
</form>

<script>
function validateForm(form) {
    let time = form.time.value;
    if (time < "09:00" || time > "17:00") {
        alert("Time must be between 9 AM to 5 PM.");
        return false;
    }
    return true;
}
</script>
<?php endif; ?>
<!-- <div class="calendar"></div> -->
<?php include '../includes/footer.php'; ?>