<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Ensure the user is a parent
if ($userRole !== 'Parent') {
    header('Location: admin_dashboard.php'); // Redirect unauthorized users
    exit;
}

// Fetch results for the parent's associated student
$results = $conn->query("SELECT academic_results.result_id, CONCAT(students.first_name, ' ', students.last_name) AS student_name, 
    subjects.subject_name, terms.term_number, academic_results.continuous_assessment, academic_results.final_exam, 
    academic_results.total_mark, academic_results.grade 
    FROM academic_results 
    JOIN students ON academic_results.student_id = students.student_id 
    JOIN class_subjects ON academic_results.class_subject_id = class_subjects.class_subject_id 
    JOIN subjects ON class_subjects.subject_id = subjects.subject_id 
    JOIN terms ON academic_results.term_id = terms.term_id 
    WHERE academic_results.student_id = $associatedId");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>View Results</title>
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
			<h1 class="title">Student Results</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Student Results</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>End-of-Term Results</h3>
					</div>
					<table class="styled-table">
						<thead>
							<tr>
								<th>Subject</th>
								<th>Term</th>
								<th>Continuous Assessment</th>
								<th>Final Exam</th>
								<th>Total Mark</th>
								<th>Grade</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $results->fetch_assoc()): ?>
								<tr>
									<td><?php echo $row['subject_name']; ?></td>
									<td><?php echo 'Term ' . $row['term_number']; ?></td>
									<td><?php echo $row['continuous_assessment']; ?></td>
									<td><?php echo $row['final_exam']; ?></td>
									<td><?php echo $row['total_mark']; ?></td>
									<td><?php echo $row['grade']; ?></td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->
</body>
</html>
