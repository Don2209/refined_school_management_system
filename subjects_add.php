<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$zimsecCode = $_POST['zimsec_code'];
	$subjectName = $_POST['subject_name'];
	$level = $_POST['level'];
	$isCore = isset($_POST['is_core']) ? 1 : 0;
	$recommendedBooks = $_POST['recommended_books'];

	$conn->query("INSERT INTO subjects (zimsec_code, subject_name, level, is_core, recommended_books) 
		VALUES ('$zimsecCode', '$subjectName', '$level', '$isCore', '$recommendedBooks')");
	header('Location: subjects_view.php');
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
	<title>Add Subject</title>
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
			<h1 class="title">Add Subject</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add Subject</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>New Subject Form</h3>
					</div>
					<div class="form-card">
						<form method="POST" action="">
							<div class="form-group">
								<label>ZIMSEC Code</label>
								<input type="text" name="zimsec_code" placeholder="Enter ZIMSEC code" required>
							</div>
							<div class="form-group">
								<label>Subject Name</label>
								<input type="text" name="subject_name" placeholder="Enter subject name" required>
							</div>
							<div class="form-group">
								<label>Level</label>
								<select name="level" required>
									<option value="" disabled selected>Select level</option>
									<option value="O-Level">O-Level</option>
									<option value="A-Level">A-Level</option>
								</select>
							</div>
							<div class="form-group">
								<label>Core Subject</label>
								<input type="checkbox" name="is_core">
							</div>
							<div class="form-group">
								<label>Recommended Books</label>
								<textarea name="recommended_books" placeholder="Enter recommended books"></textarea>
							</div>
							<button type="submit" class="btn-submit">Add Subject</button>
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
