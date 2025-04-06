<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Restrict access to Admin role
checkAccess(['Admin']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = $_POST['name'];
	$startDate = $_POST['start_date'];
	$endDate = $_POST['end_date'];

	$conn->query("INSERT INTO academic_years (name, start_date, end_date) 
		VALUES ('$name', '$startDate', '$endDate')");
	header('Location: academic_years_view.php');
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
	<title>Add Academic Year</title>
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
			<h1 class="title">Add Academic Year</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add Academic Year</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>New Academic Year Form</h3>
					</div>
					<div class="form-card">
						<form method="POST" action="">
							<div class="form-group">
								<label>Name</label>
								<input type="text" name="name" placeholder="Enter academic year (e.g., 2024-2025)" required>
							</div>
							<div class="form-group">
								<label>Start Date</label>
								<input type="date" name="start_date" required>
							</div>
							<div class="form-group">
								<label>End Date</label>
								<input type="date" name="end_date" required>
							</div>
							<button type="submit" class="btn-submit">Add Academic Year</button>
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
