<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch students with outstanding balances
$outstandingFees = $conn->query("SELECT students.student_id, CONCAT(students.first_name, ' ', students.last_name) AS student_name, 
    academic_years.name AS academic_year, fee_management.balance 
    FROM fee_management 
    JOIN students ON fee_management.student_id = students.student_id 
    JOIN academic_years ON fee_management.academic_year_id = academic_years.academic_year_id 
    WHERE fee_management.balance > 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>Outstanding Balances</title>
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
			<h1 class="title">Outstanding Balances</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Outstanding Balances</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Students with Outstanding Balances</h3>
					</div>
					<table class="styled-table">
						<thead>
							<tr>
								<th>Student Name</th>
								<th>Academic Year</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $outstandingFees->fetch_assoc()): ?>
								<tr>
									<td><?php echo $row['student_name']; ?></td>
									<td><?php echo $row['academic_year']; ?></td>
									<td><?php echo $row['balance']; ?></td>
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
