<?php
$host = 'localhost';  // MySQL server host
$db = 'attendance_system';  // Database name
$user = 'root';  // MySQL username
$pass = '';  // MySQL password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>