<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch staff and students for dropdowns
$staff = $conn->query("SELECT staff_id, CONCAT(first_name, ' ', last_name) AS full_name FROM staff");
$students = $conn->query("SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name FROM students");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];
	$password = $_POST['password']; // Store password as plain text
	$userRole = $_POST['user_role'];
	$associatedId = $_POST['associated_id'];

	$conn->query("INSERT INTO users (username, password_hash, user_role, associated_id) 
		VALUES ('$username', '$password', '$userRole', '$associatedId')");
	header('Location: users_view.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>Add User</title>
	<script src="js/dropdown.js" defer></script>
</head>
<body>
	<!-- SIDEBAR -->
	<?php include 'includes/sidebar.php'; ?>
	<!-- SIDEBAR -->

	<!-- NAVBAR -->
	<section id="content">
		<?php include 'includes/navbar.php'; ?>
		<!-- MAIN -->
		<main>
			<h1 class="title">Add User</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add User</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>New User Form</h3>
					</div>
					<div class="form-card">
						<form method="POST" action="">
							<div class="form-group">
								<label>Username</label>
								<input type="text" name="username" placeholder="Enter username" required>
							</div>
							<div class="form-group">
								<label>Password</label>
								<input type="password" name="password" placeholder="Enter password" required>
							</div>
							<div class="form-group">
								<label>Role</label>
								<select name="user_role" required>
									<option value="" disabled selected>Select role</option>
									<option value="Admin">Admin</option>
									<option value="Teacher">Teacher</option>
									<option value="Accountant">Accountant</option>
									<option value="Parent">Parent</option>
								</select>
							</div>
							<div class="form-group">
								<label>Associated ID</label>
								<select name="associated_id" required>
									<option value="" disabled selected>Select associated person</option>
									<optgroup label="Staff">
										<?php while ($row = $staff->fetch_assoc()): ?>
											<option value="<?php echo $row['staff_id']; ?>"><?php echo $row['full_name']; ?> (Staff)</option>
										<?php endwhile; ?>
									</optgroup>
									<optgroup label="Students">
										<?php while ($row = $students->fetch_assoc()): ?>
											<option value="<?php echo $row['student_id']; ?>"><?php echo $row['full_name']; ?> (Student)</option>
										<?php endwhile; ?>
									</optgroup>
								</select>
							</div>
							<button type="submit" class="btn-submit">Add User</button>
						</form>
					</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->
</body>
</html>
