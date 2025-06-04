<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch user role and associated ID
$userRole = $_SESSION['user_role'] ?? null;
$associatedId = isset($_SESSION['associated_id']) ? intval($_SESSION['associated_id']) : 0;

// Restrict access to Admin, Teacher, and Parent roles
checkAccess(['Admin', 'Teacher', 'Parent']);

// Filter classes based on user role
if ($userRole === 'Teacher') {
    $classes = $conn->query("SELECT classes.class_id, classes.class_name, academic_years.name AS academic_year, 
        classes.stream, CONCAT(staff.first_name, ' ', staff.last_name) AS class_teacher 
        FROM users 
        JOIN staff ON users.associated_id = staff.staff_id 
        JOIN classes ON staff.staff_id = classes.class_teacher_id 
        JOIN academic_years ON classes.academic_year_id = academic_years.academic_year_id 
        WHERE users.user_id = $associatedId");

    // Debugging output
    if ($conn->error) {
        echo "SQL Error: " . $conn->error;
    }
    if ($classes->num_rows === 0) {
        echo "No classes found for teacher with ID: $associatedId";
    }
} elseif ($userRole === 'Parent') {
    $stmt = $conn->prepare("SELECT classes.class_id, classes.class_name, academic_years.name AS academic_year, 
        classes.stream, CONCAT(staff.first_name, ' ', staff.last_name) AS class_teacher 
        FROM classes 
        JOIN academic_years ON classes.academic_year_id = academic_years.academic_year_id 
        LEFT JOIN staff ON classes.class_teacher_id = staff.staff_id 
        WHERE classes.class_id = (
            SELECT current_class_id FROM students WHERE student_id = ?
        )");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $classes = $stmt->get_result();
    $stmt->close();
} elseif ($userRole === 'Admin') {
    $classes = $conn->query("SELECT classes.class_id, classes.class_name, academic_years.name AS academic_year, 
        classes.stream, CONCAT(staff.first_name, ' ', staff.last_name) AS class_teacher 
        FROM classes 
        JOIN academic_years ON classes.academic_year_id = academic_years.academic_year_id 
        LEFT JOIN staff ON classes.class_teacher_id = staff.staff_id");

    // Debugging output
    if ($conn->error) {
        echo "SQL Error: " . $conn->error;
    }
    if ($classes->num_rows === 0) {
        echo "No classes found.";
    }
} else {
    $classes = $conn->query("SELECT * FROM classes WHERE 1=0"); // No data for other roles
}
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
					<?php if ($userRole === 'Teacher' || $userRole === 'Admin'): ?>
						<div class="card-container">
							<?php while ($row = $classes->fetch_assoc()): ?>
								<a href="class_details.php?class_id=<?php echo $row['class_id']; ?>" class="class-card">
									<div class="card">
										<h4><?php echo $row['class_name']; ?></h4>
										<p>Academic Year: <?php echo $row['academic_year']; ?></p>
										<p>Stream: <?php echo $row['stream']; ?></p>
										<p>Class Teacher: <?php echo $row['class_teacher']; ?></p>
									</div>
								</a>
							<?php endwhile; ?>
						</div>
					<?php else: ?>
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
					<?php endif; ?>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->
	<style>
		.card-container {
			display: flex;
			flex-wrap: wrap;
			gap: 20px;
		}
		.class-card {
			text-decoration: none;
			color: inherit;
		}
		.card {
			border: 1px solid #ddd;
			border-radius: 8px;
			padding: 20px;
			width: 250px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
			transition: transform 0.2s;
		}
		.card:hover {
			transform: scale(1.05);
		}
		.card h4 {
			margin: 0 0 10px;
		}
		.card p {
			margin: 5px 0;
		}
	</style>
</body>
</html>
