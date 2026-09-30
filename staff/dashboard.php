<?php
session_start();
if ($_SESSION['role'] != 'staff') header("Location: ../login.php");
include '../includes/header.php'; ?>
<h2>Staff Dashboard</h2>
<p>Access to manage operations.</p>
<a href="../admin/manage_students.php">Manage Students</a> <!-- Limited links -->
<?php include '../includes/footer.php'; ?>