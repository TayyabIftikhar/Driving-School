<?php
session_start();
if ($_SESSION['role'] != 'instructor') header("Location: ../login.php");
include '../includes/db_connect.php';

$instructor_id = $conn->query("SELECT id FROM instructors WHERE user_id=" . $_SESSION['user_id'])->fetch_assoc()['id'];
$lessons_count = $conn->query("SELECT COUNT(*) FROM lessons WHERE instructor_id=$instructor_id")->fetch_row()[0];
?>
<?php include '../includes/header.php'; ?>

<style>
    main {
        max-width: 900px;
        margin: 30px auto;
        padding: 20px;
        text-align: center;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #0F172A;
    }

    .card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        margin: 20px auto;
        width: 80%;
        max-width: 400px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }

    .card h3 {
        margin: 0;
        font-size: 20px;
        color: #0D9488;
    }

    .card p {
        font-size: 18px;
        margin: 10px 0 0;
        color: #0F172A;
    }

    .dashboard-link {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 18px;
        background-color: #0D9488;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        transition: background 0.3s ease;
    }

    .dashboard-link:hover {
        background-color: #0A7A6C;
    }
</style>

<main>
    <h2>Instructor Dashboard</h2>
    <div class="card">
        <h3>Lessons Assigned</h3>
        <p><?php echo $lessons_count; ?></p>
    </div>
    <a href="schedule.php" class="dashboard-link"> View Schedule</a>
</main>

<script>
    let instructorId = <?php echo json_encode($instructor_id); ?>;
    console.log("Instructor ID:", instructorId);
</script>

<?php include '../includes/footer.php'; ?>
