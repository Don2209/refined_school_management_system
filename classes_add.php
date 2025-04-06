<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch academic years and staff for dropdowns
$academicYears = $conn->query("SELECT academic_year_id, name FROM academic_years");
$staff = $conn->query("SELECT staff_id, CONCAT(first_name, ' ', last_name) AS full_name FROM staff WHERE position = 'Teacher'");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$className = $_POST['class_name'];
	$academicYearId = $_POST['academic_year_id'];
	$stream = $_POST['stream'];
	$classTeacherId = $_POST['class_teacher_id'];

	$conn->query("INSERT INTO classes (class_name, academic_year_id, stream, class_teacher_id) 
		VALUES ('$className', '$academicYearId', '$stream', '$classTeacherId')");
	header('Location: classes_view.php');
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
	<title>Add Class</title>
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
			<h1 class="title">Add Class</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add Class</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>New Class Form</h3>
					</div>
					<div class="form-card">
						<form method="POST" action="">
							<div class="form-group">
								<label>Class Name</label>
								<input type="text" name="class_name" placeholder="Enter class name" required>
							</div>
							<div class="form-group">
								<label>Academic Year</label>
								<select name="academic_year_id" required>
									<option value="" disabled selected>Select academic year</option>
									<?php while ($row = $academicYears->fetch_assoc()): ?>
										<option value="<?php echo $row['academic_year_id']; ?>"><?php echo $row['name']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Stream</label>
								<select name="stream" required>
									<option value="" disabled selected>Select stream</option>
									<option value="General">General</option>
									<option value="Science">Science</option>
									<option value="Arts">Arts</option>
									<option value="Commercial">Commercial</option>
								</select>
							</div>
							<div class="form-group">
								<label>Class Teacher</label>
								<select name="class_teacher_id" required>
									<option value="" disabled selected>Select class teacher</option>
									<?php while ($row = $staff->fetch_assoc()): ?>
										<option value="<?php echo $row['staff_id']; ?>"><?php echo $row['full_name']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<button type="submit" class="btn-submit">Add Class</button>
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
