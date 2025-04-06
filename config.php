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

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve logged-in user's role and associated ID
$userRole = $_SESSION['user_role'] ?? null;
$associatedId = $_SESSION['associated_id'] ?? null;

// Function to check user access
function checkAccess($allowedRoles) {
    global $userRole;
    if (!in_array($userRole, $allowedRoles)) {
        header('Location: admin_dashboard.php'); // Redirect unauthorized users
        exit;
    }
}
?>
