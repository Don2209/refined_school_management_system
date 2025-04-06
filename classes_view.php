<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch all classes
$classes = $conn->query("SELECT classes.class_id, classes.class_name, academic_years.name AS academic_year, 
    classes.stream, CONCAT(staff.first_name, ' ', staff.last_name) AS class_teacher 
    FROM classes 
    JOIN academic_years ON classes.academic_year_id = academic_years.academic_year_id 
    LEFT JOIN staff ON classes.class_teacher_id = staff.staff_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>View Classes</title>
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
			<h1 class="title">View Classes</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">View Classes</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>All Classes</h3>
					</div>
					<table class="styled-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Class Name</th>
								<th>Academic Year</th>
								<th>Stream</th>
								<th>Class Teacher</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $classes->fetch_assoc()): ?>
								<tr>
									<td><?php echo $row['class_id']; ?></td>
									<td><?php echo $row['class_name']; ?></td>
									<td><?php echo $row['academic_year']; ?></td>
									<td><?php echo $row['stream']; ?></td>
									<td><?php echo $row['class_teacher']; ?></td>
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
