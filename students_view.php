<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Restrict access to Admin, Teacher, and Parent roles
checkAccess(['Admin', 'Teacher', 'Parent']);

// Filter students based on user role
if ($userRole === 'Parent') {
    $stmt = $conn->prepare("SELECT student_id, first_name, last_name, gender, date_of_birth, enrollment_date, status 
        FROM students 
        WHERE student_id = ?");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $students = $stmt->get_result();
    $stmt->close();
} elseif ($userRole === 'Teacher') {
    $students = $conn->query("SELECT student_id, first_name, last_name, gender, date_of_birth, enrollment_date, status 
        FROM students 
        WHERE current_class_id IN (
            SELECT class_id FROM classes WHERE class_teacher_id = $associatedId
        )");
} elseif ($userRole === 'Admin') {
    $students = $conn->query("SELECT student_id, first_name, last_name, gender, date_of_birth, enrollment_date, status FROM students");
} else {
    $students = $conn->query("SELECT * FROM students WHERE 1=0"); // No data for other roles
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>View Students</title>
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
			<h1 class="title">View Students</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">View Students</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>All Students</h3>
					</div>
					<table class="styled-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Gender</th>
								<th>Date of Birth</th>
								<th>Enrollment Date</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $students->fetch_assoc()): ?>
								<tr>
									<td><?php echo $row['student_id']; ?></td>
									<td><?php echo $row['first_name']; ?></td>
									<td><?php echo $row['last_name']; ?></td>
									<td><?php echo $row['gender']; ?></td>
									<td><?php echo $row['date_of_birth']; ?></td>
									<td><?php echo $row['enrollment_date']; ?></td>
									<td><?php echo $row['status']; ?></td>
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