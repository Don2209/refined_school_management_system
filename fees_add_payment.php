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
	$amountPaid = $_POST['amount_paid'];

	$conn->query("UPDATE fee_management SET amount_paid = amount_paid + $amountPaid WHERE student_id = $studentId");
	header('Location: fees_summary.php');
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
	<title>Add Payment</title>
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
			<h1 class="title">Add Payment</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add Payment</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Payment Form</h3>
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
								<label>Amount Paid</label>
								<input type="number" name="amount_paid" placeholder="Enter amount paid" required>
							</div>
							<button type="submit" class="btn-submit">Add Payment</button>
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
