<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch students, class subjects, and terms for dropdowns
$students = $conn->query("SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name FROM students");
$classSubjects = $conn->query("SELECT class_subject_id, CONCAT(subjects.subject_name, ' (', classes.class_name, ')') AS subject_class 
    FROM class_subjects 
    JOIN subjects ON class_subjects.subject_id = subjects.subject_id 
    JOIN classes ON class_subjects.class_id = classes.class_id");
$terms = $conn->query("SELECT term_id, CONCAT('Term ', term_number, ' (', academic_years.name, ')') AS term_name 
    FROM terms 
    JOIN academic_years ON terms.academic_year_id = academic_years.academic_year_id");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$studentId = $_POST['student_id'];
	$classSubjectId = $_POST['class_subject_id'];
	$termId = $_POST['term_id'];
	$continuousAssessment = $_POST['continuous_assessment'];
	$finalExam = $_POST['final_exam'];

	$conn->query("INSERT INTO academic_results (student_id, class_subject_id, term_id, continuous_assessment, final_exam) 
		VALUES ('$studentId', '$classSubjectId', '$termId', '$continuousAssessment', '$finalExam')");
	header('Location: results_view.php');
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
	<title>Add Results</title>
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
			<h1 class="title">Add Results</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Add Results</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>New Results Form</h3>
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
								<label>Class Subject</label>
								<select name="class_subject_id" required>
									<option value="" disabled selected>Select class subject</option>
									<?php while ($row = $classSubjects->fetch_assoc()): ?>
										<option value="<?php echo $row['class_subject_id']; ?>"><?php echo $row['subject_class']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Term</label>
								<select name="term_id" required>
									<option value="" disabled selected>Select term</option>
									<?php while ($row = $terms->fetch_assoc()): ?>
										<option value="<?php echo $row['term_id']; ?>"><?php echo $row['term_name']; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Continuous Assessment</label>
								<input type="number" name="continuous_assessment" placeholder="Enter continuous assessment mark" required>
							</div>
							<div class="form-group">
								<label>Final Exam</label>
								<input type="number" name="final_exam" placeholder="Enter final exam mark" required>
							</div>
							<button type="submit" class="btn-submit">Add Results</button>
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
