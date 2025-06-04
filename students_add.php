<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch all available classes
$classes = $conn->query("SELECT class_id, class_name FROM classes");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$firstName = $_POST['first_name'];
	$lastName = $_POST['last_name'];
	$gender = $_POST['gender'];
	$dateOfBirth = $_POST['date_of_birth'];
	$enrollmentDate = $_POST['enrollment_date'];
	$address = $_POST['address'];
	$classId = $_POST['class_id'];
	$previousSchoolName = $_POST['previous_school_name'];
	$levelCompleted = $_POST['level_completed'];
	$previousSchoolStartDate = $_POST['previous_school_start_date'];
	$previousSchoolEndDate = $_POST['previous_school_end_date'];
	$reasonForLeaving = $_POST['reason_for_leaving'];
	$performanceSummary = $_POST['performance_summary'];

	$conn->query("INSERT INTO students (first_name, last_name, gender, date_of_birth, enrollment_date, address, class_id, previous_school_name, level_completed, previous_school_start_date, previous_school_end_date, reason_for_leaving, performance_summary) 
		VALUES ('$firstName', '$lastName', '$gender', '$dateOfBirth', '$enrollmentDate', '$address', '$classId', '$previousSchoolName', '$levelCompleted', '$previousSchoolStartDate', '$previousSchoolEndDate', '$reasonForLeaving', '$performanceSummary')");
	header('Location: students_view.php');
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
	<title>Add Student</title>
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
			<h1 class="title">Add Student</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add Student</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>New Student Form</h3>
					</div>
					<div class="form-card">
						<form method="POST" action="">
							<div class="form-group">
								<label>First Name</label>
								<input type="text" name="first_name" placeholder="Enter first name" required>
							</div>
							<div class="form-group">
								<label>Last Name</label>
								<input type="text" name="last_name" placeholder="Enter last name" required>
							</div>
							<div class="form-group">
								<label>Gender</label>
								<select name="gender" required>
									<option value="" disabled selected>Select gender</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
									<option value="Other">Other</option>
								</select>
							</div>
							<div class="form-group">
								<label>Date of Birth</label>
								<input type="date" name="date_of_birth" required>
							</div>
							<div class="form-group">
								<label>Class</label>
								<select name="class_id" required>
									<option value="" disabled selected>Select class</option>
									<?php while ($class = $classes->fetch_assoc()): ?>
										<option value="<?php echo $class['class_id']; ?>">
											<?php echo htmlspecialchars($class['class_name']); ?>
										</option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Enrollment Date</label>
								<input type="date" name="enrollment_date" required>
							</div>
							<div class="form-group">
								<label>Address</label>
								<textarea name="address" placeholder="Enter address" required></textarea>
							</div>
							<div class="form-group">
								<label>Previous School Name</label>
								<input type="text" name="previous_school_name" placeholder="Enter previous school name" required>
							</div>
							<div class="form-group">
								<label>Level Completed</label>
								<select name="level_completed" required>
									<option value="" disabled selected>Select level</option>
									<option value="Primary">Primary</option>
									<option value="Secondary">Secondary</option>
									<option value="O-Level">O-Level</option>
									<option value="A-Level">A-Level</option>
									<option value="Other">Other</option>
								</select>
							</div>
							<div class="form-group">
								<label>Start Date</label>
								<input type="date" name="previous_school_start_date">
							</div>
							<div class="form-group">
								<label>End Date</label>
								<input type="date" name="previous_school_end_date">
							</div>
							<div class="form-group">
								<label>Reason for Leaving</label>
								<textarea name="reason_for_leaving" placeholder="Enter reason for leaving"></textarea>
							</div>
							<div class="form-group">
								<label>Performance Summary</label>
								<textarea name="performance_summary" placeholder="Enter performance summary"></textarea>
							</div>
							<button type="submit" class="btn-submit">Add Student</button>
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