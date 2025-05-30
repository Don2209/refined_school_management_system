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

// Fetch student details associated with the parent
$studentDetails = $conn->query("SELECT student_id, first_name, last_name, gender, date_of_birth, enrollment_date, status 
    FROM students 
    WHERE student_id = $associatedId")->fetch_assoc();

// Fetch academic performance (e.g., average marks) of the student
$academicPerformance = $conn->query("SELECT AVG(academic_results.total_mark) AS average_mark 
    FROM academic_results 
    WHERE student_id = $associatedId")->fetch_assoc();

// Fetch academic results of the student
$academicResults = $conn->query("SELECT subjects.subject_name, terms.term_number, academic_results.continuous_assessment, 
    academic_results.final_exam, academic_results.total_mark, academic_results.grade 
    FROM academic_results 
    JOIN class_subjects ON academic_results.class_subject_id = class_subjects.class_subject_id 
    JOIN subjects ON class_subjects.subject_id = subjects.subject_id 
    JOIN terms ON academic_results.term_id = terms.term_id 
    WHERE academic_results.student_id = $associatedId");

// Fetch fee summary for the student
$feeSummary = $conn->query("SELECT total_amount, amount_paid, balance 
    FROM fee_management 
    WHERE student_id = $associatedId")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>Parent Dashboard</title>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
			<h1 class="title">Parent Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="parent_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Dashboard</a></li>
			</ul>

			<!-- Student Details -->
			<div class="info-data">
				<div class="card">
					<div class="head">
						<div>
							<h2><?php echo $studentDetails['first_name'] . ' ' . $studentDetails['last_name']; ?></h2>
							<p>Student Name</p>
						</div>
						<i class='bx bxs-user icon'></i>
					</div>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2><?php echo $studentDetails['status']; ?></h2>
							<p>Status</p>
						</div>
						<i class='bx bxs-check-circle icon'></i>
					</div>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2><?php echo $feeSummary['balance'] ?? 0; ?></h2>
							<p>Outstanding Fees</p>
						</div>
						<i class='bx bxs-wallet icon'></i>
					</div>
				</div>
			</div>

			<!-- Academic Performance -->
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Academic Performance</h3>
					</div>
					<div class="chart-card">
						<div id="performance-chart"></div>
					</div>
				</div>
			</div>

			<!-- Academic Results -->
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Academic Results</h3>
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
							<?php while ($row = $academicResults->fetch_assoc()): ?>
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

	<script>
		// Academic Performance Chart
		var performanceOptions = {
			series: [<?php echo $academicPerformance['average_mark'] ?? 0; ?>],
			chart: { type: 'radialBar' },
			plotOptions: {
				radialBar: {
					hollow: { size: '70%' },
					dataLabels: {
						name: { show: true, fontSize: '16px' },
						value: { show: true, fontSize: '14px' }
					}
				}
			},
			labels: ['Average Mark']
		};
		var performanceChart = new ApexCharts(document.querySelector("#performance-chart"), performanceOptions);
		performanceChart.render();
	</script>
</body>
</html>
