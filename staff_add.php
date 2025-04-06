<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$firstName = $_POST['first_name'];
	$lastName = $_POST['last_name'];
	$gender = $_POST['gender'];
	$position = $_POST['position'];
	$qualification = $_POST['qualification_level'];
	$employmentDate = $_POST['employment_date'];
	$nationalId = $_POST['national_id'];
	$subjectSpecialization = $_POST['subject_specialization'];

	$conn->query("INSERT INTO staff (first_name, last_name, gender, position, qualification_level, employment_date, national_id, subject_specialization) 
		VALUES ('$firstName', '$lastName', '$gender', '$position', '$qualification', '$employmentDate', '$nationalId', '$subjectSpecialization')");
	header('Location: staff_view.php');
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
	<title>Add Staff</title>
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
			<h1 class="title">Add Staff</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add Staff</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>New Staff Form</h3>
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
								<label>Position</label>
								<select name="position" required>
									<option value="" disabled selected>Select position</option>
									<option value="Teacher">Teacher</option>
									<option value="Administrator">Administrator</option>
									<option value="Support Staff">Support Staff</option>
								</select>
							</div>
							<div class="form-group">
								<label>Qualification Level</label>
								<select name="qualification_level" required>
									<option value="" disabled selected>Select qualification</option>
									<option value="Diploma">Diploma</option>
									<option value="Degree">Degree</option>
									<option value="Masters">Masters</option>
									<option value="PhD">PhD</option>
								</select>
							</div>
							<div class="form-group">
								<label>Employment Date</label>
								<input type="date" name="employment_date" required>
							</div>
							<div class="form-group">
								<label>National ID</label>
								<input type="text" name="national_id" placeholder="Enter national ID" required>
							</div>
							<div class="form-group">
								<label>Subject Specialization</label>
								<input type="text" name="subject_specialization" placeholder="Enter subject specialization">
							</div>
							<button type="submit" class="btn-submit">Add Staff</button>
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
