<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch students for dropdown
$students = $conn->query("SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name FROM students");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['student_id'];
    $fullName = $_POST['full_name'];
    $relationship = $_POST['relationship'];
    $contactNumber = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $isPrimary = isset($_POST['is_primary']) ? 1 : 0;

    // Insert guardian into the database
    $conn->query("INSERT INTO guardians (student_id, full_name, relationship, contact_number, email, address, is_primary) 
        VALUES ('$studentId', '$fullName', '$relationship', '$contactNumber', '$email', '$address', '$isPrimary')");
    header('Location: guardians_view.php');
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
	<title>Add Guardian</title>
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
			<h1 class="title">Add Guardian</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add Guardian</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>New Guardian Form</h3>
					</div>
					<div class="form-card">
						<form method="POST" action="">
							<div class="form-group">
								<label>Student</label>
								<select name="student_id" required>
									<option value="" disabled selected>Select student</option>
									<?php while ($row = $students->fetch_assoc()): ?>
										<option value="<?php echo $row['student_id']; ?>"><?php echo $row['full_name']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Full Name</label>
								<input type="text" name="full_name" placeholder="Enter full name" required>
							</div>
							<div class="form-group">
								<label>Relationship</label>
								<select name="relationship" required>
									<option value="" disabled selected>Select relationship</option>
									<option value="Parent">Parent</option>
									<option value="Sibling">Sibling</option>
									<option value="Relative">Relative</option>
									<option value="Other">Other</option>
								</select>
							</div>
							<div class="form-group">
								<label>Contact Number</label>
								<input type="text" name="contact_number" placeholder="Enter contact number" required>
							</div>
							<div class="form-group">
								<label>Email</label>
								<input type="email" name="email" placeholder="Enter email">
							</div>
							<div class="form-group">
								<label>Address</label>
								<textarea name="address" placeholder="Enter address"></textarea>
							</div>
							<div class="form-group">
								<label>Primary Guardian</label>
								<input type="checkbox" name="is_primary">
							</div>
							<button type="submit" class="btn-submit">Add Guardian</button>
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
