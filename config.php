<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'refined_work';

// Establish database connection
$conn = new mysqli($host, $user, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
