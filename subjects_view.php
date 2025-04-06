<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch all subjects
$subjects = $conn->query("SELECT subject_id, zimsec_code, subject_name, level, is_core FROM subjects");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>View Subjects</title>
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
			<h1 class="title">View Subjects</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">View Subjects</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>All Subjects</h3>
					</div>
					<table class="styled-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>ZIMSEC Code</th>
								<th>Subject Name</th>
								<th>Level</th>
								<th>Core Subject</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $subjects->fetch_assoc()): ?>
								<tr>
									<td><?php echo $row['subject_id']; ?></td>
									<td><?php echo $row['zimsec_code']; ?></td>
									<td><?php echo $row['subject_name']; ?></td>
									<td><?php echo $row['level']; ?></td>
									<td><?php echo $row['is_core'] ? 'Yes' : 'No'; ?></td>
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
