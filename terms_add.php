<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Restrict access to Admin role
checkAccess(['Admin']);

// Fetch academic years for dropdown
$academicYears = $conn->query("SELECT academic_year_id, name FROM academic_years");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$academicYearId = $_POST['academic_year_id'];
	$termNumber = $_POST['term_number'];
	$startDate = $_POST['start_date'];
	$endDate = $_POST['end_date'];

	$conn->query("INSERT INTO terms (academic_year_id, term_number, start_date, end_date) 
		VALUES ('$academicYearId', '$termNumber', '$startDate', '$endDate')");
	header('Location: terms_view.php');
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
	<title>Add Term</title>
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
			<h1 class="title">Add Term</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add Term</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>New Term Form</h3>
					</div>
					<div class="form-card">
						<form method="POST" action="">
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
								<label>Term Number</label>
								<select name="term_number" required>
									<option value="" disabled selected>Select term number</option>
									<option value="1">Term 1</option>
									<option value="2">Term 2</option>
									<option value="3">Term 3</option>
								</select>
							</div>
							<div class="form-group">
								<label>Start Date</label>
								<input type="date" name="start_date" required>
							</div>
							<div class="form-group">
								<label>End Date</label>
								<input type="date" name="end_date" required>
							</div>
							<button type="submit" class="btn-submit">Add Term</button>
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
