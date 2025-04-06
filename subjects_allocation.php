<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch classes, subjects, and teachers for dropdowns
$classes = $conn->query("SELECT class_id, class_name FROM classes");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects");
$teachers = $conn->query("SELECT staff_id, CONCAT(first_name, ' ', last_name) AS full_name FROM staff WHERE position = 'Teacher'");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$classId = $_POST['class_id'];
	$subjectId = $_POST['subject_id'];
	$teacherId = $_POST['teacher_id'];
	$hoursPerWeek = $_POST['hours_per_week'];

	$conn->query("INSERT INTO class_subjects (class_id, subject_id, teacher_id, hours_per_week) 
		VALUES ('$classId', '$subjectId', '$teacherId', '$hoursPerWeek')");
	header('Location: subjects_allocation.php');
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
	<title>Subject Allocation</title>
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
			<h1 class="title">Subject Allocation</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Subject Allocation</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Allocate Subjects</h3>
					</div>
					<div class="form-card">
						<form method="POST" action="">
							<div class="form-group">
								<label>Class</label>
								<select name="class_id" required>
									<option value="" disabled selected>Select class</option>
									<?php while ($row = $classes->fetch_assoc()): ?>
										<option value="<?php echo $row['class_id']; ?>"><?php echo $row['class_name']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Subject</label>
								<select name="subject_id" required>
									<option value="" disabled selected>Select subject</option>
									<?php while ($row = $subjects->fetch_assoc()): ?>
										<option value="<?php echo $row['subject_id']; ?>"><?php echo $row['subject_name']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Teacher</label>
								<select name="teacher_id" required>
									<option value="" disabled selected>Select teacher</option>
									<?php while ($row = $teachers->fetch_assoc()): ?>
										<option value="<?php echo $row['staff_id']; ?>"><?php echo $row['full_name']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Hours Per Week</label>
								<input type="number" name="hours_per_week" placeholder="Enter hours per week" required>
							</div>
							<button type="submit" class="btn-submit">Allocate Subject</button>
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
