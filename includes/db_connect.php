<?php
$servername = "localhost";
$username = "root";
$password = "";  // Default XAMPP
$dbname = "Origin_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to simulate SMS (log to file for demo)
function send_sms($phone, $message) {
    file_put_contents(__DIR__ . '/sms_log.txt', "SMS to $phone: $message\n", FILE_APPEND);
}

// Function to simulate email (log to file for demo instead of sending)
function send_email($email, $subject, $message) {
    // Append to a log file instead of using mail()
    file_put_contents(__DIR__ . '/email_log.txt', "Email to $email | Subject: $subject | Message: $message\n", FILE_APPEND);
}
?>