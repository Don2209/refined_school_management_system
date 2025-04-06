<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch all staff members
$staff = $conn->query("SELECT staff_id, first_name, last_name, gender, position, qualification_level, employment_date FROM staff");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>View Staff</title>
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
			<h1 class="title">View Staff</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">View Staff</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>All Staff Members</h3>
					</div>
					<table class="styled-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Gender</th>
								<th>Position</th>
								<th>Qualification</th>
								<th>Employment Date</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $staff->fetch_assoc()): ?>
								<tr>
									<td><?php echo $row['staff_id']; ?></td>
									<td><?php echo $row['first_name']; ?></td>
									<td><?php echo $row['last_name']; ?></td>
									<td><?php echo $row['gender']; ?></td>
									<td><?php echo $row['position']; ?></td>
									<td><?php echo $row['qualification_level']; ?></td>
									<td><?php echo $row['employment_date']; ?></td>
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
