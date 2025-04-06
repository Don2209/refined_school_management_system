<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Restrict access to Admin role
checkAccess(['Admin']);

// Fetch all terms
$terms = $conn->query("SELECT terms.term_id, academic_years.name AS academic_year, terms.term_number, terms.start_date, terms.end_date 
	FROM terms 
	JOIN academic_years ON terms.academic_year_id = academic_years.academic_year_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>View Terms</title>
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
			<h1 class="title">Terms</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Terms</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>All Terms</h3>
					</div>
					<table class="styled-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Academic Year</th>
								<th>Term Number</th>
								<th>Start Date</th>
								<th>End Date</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $terms->fetch_assoc()): ?>
								<tr>
									<td><?php echo $row['term_id']; ?></td>
									<td><?php echo $row['academic_year']; ?></td>
									<td><?php echo $row['term_number']; ?></td>
									<td><?php echo $row['start_date']; ?></td>
									<td><?php echo $row['end_date']; ?></td>
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
