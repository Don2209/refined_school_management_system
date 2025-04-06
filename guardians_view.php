<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch guardians
$guardians = $conn->query("SELECT guardians.guardian_id, CONCAT(students.first_name, ' ', students.last_name) AS student_name, 
    guardians.full_name, guardians.relationship, guardians.contact_number, guardians.email, guardians.is_primary 
    FROM guardians 
    JOIN students ON guardians.student_id = students.student_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>View Guardians</title>
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
			<h1 class="title">View Guardians</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">View Guardians</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>All Guardians</h3>
					</div>
					<table class="styled-table">
						<thead>
							<tr>
								<th>Guardian Name</th>
								<th>Student Name</th>
								<th>Relationship</th>
								<th>Contact Number</th>
								<th>Email</th>
								<th>Primary Guardian</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $guardians->fetch_assoc()): ?>
								<tr>
									<td><?php echo $row['full_name']; ?></td>
									<td><?php echo $row['student_name']; ?></td>
									<td><?php echo $row['relationship']; ?></td>
									<td><?php echo $row['contact_number']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td><?php echo $row['is_primary'] ? 'Yes' : 'No'; ?></td>
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
