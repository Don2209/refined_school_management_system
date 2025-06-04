<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Restrict access to Admin, Teacher, and Parent roles
checkAccess(['Admin', 'Teacher', 'Parent']);

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Filter students based on user role
if ($userRole === 'Parent') {
    $stmt = $conn->prepare("SELECT student_id, first_name, last_name, gender, date_of_birth, enrollment_date, status 
        FROM students 
        WHERE student_id = ? 
        LIMIT ? OFFSET ?");
    $stmt->bind_param("iii", $associatedId, $limit, $offset);
    $stmt->execute();
    $students = $stmt->get_result();
    $stmt->close();
} elseif ($userRole === 'Teacher') {
    $stmt = $conn->prepare("SELECT students.student_id, students.first_name, students.last_name, students.gender, 
        students.date_of_birth, students.enrollment_date, students.status 
        FROM students 
        JOIN classes ON students.current_class_id = classes.class_id 
        JOIN staff ON classes.class_teacher_id = staff.staff_id 
        JOIN users ON staff.staff_id = users.associated_id 
        WHERE users.user_id = ? 
        LIMIT ? OFFSET ?");
    $stmt->bind_param("iii", $_SESSION['user_id'], $limit, $offset);
    $stmt->execute();
    $students = $stmt->get_result();
    $stmt->close();
} elseif ($userRole === 'Admin') {
    $students = $conn->query("SELECT student_id, first_name, last_name, gender, date_of_birth, enrollment_date, status 
        FROM students 
        LIMIT $limit OFFSET $offset");
} else {
    $students = $conn->query("SELECT * FROM students WHERE 1=0"); // No data for other roles
}

// Get total records for pagination
$totalRecordsQuery = $conn->query("SELECT COUNT(*) AS total FROM students");
$totalRecords = $totalRecordsQuery->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);
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
	<style>
		/* Add styles for the action icons */
		.action-icon {
			display: inline-block;
			padding: 5px;
			border: 2px solid transparent;
			border-radius: 50%;
			transition: border-color 0.3s ease, background-color 0.3s ease;
			color: white; /* Default icon color */
		}

		.action-icon:hover {
			border-color: #2196F3; /* Blue outline on hover */
			background-color: #2196F3; /* Blue background on hover */
		}

		.action-icon-edit {
			color:  #2196F3; /* Green for edit */
		}

		.action-icon-delete {
			color:  #2196F3; /* Red for delete */
		}

		.action-icon-print {
			color: #2196F3; /* Blue for print */
		}
	</style>
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
						<a href="students_add.php" class="btn-add">Add Student</a>
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
								<th>Actions</th>
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
									<td>
										<a href="students_edit.php?id=<?php echo $row['student_id']; ?>" title="Edit" class="action-icon action-icon-edit">
											<i class='bx bx-edit'></i>
										</a>
										<a href="students_delete.php?id=<?php echo $row['student_id']; ?>" title="Delete" class="action-icon action-icon-delete" onclick="return confirm('Are you sure you want to delete this student?');">
											<i class='bx bx-trash'></i>
										</a>
										<a href="students_print.php?id=<?php echo $row['student_id']; ?>" title="Print" class="action-icon action-icon-print">
											<i class='bx bx-printer'></i>
										</a>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
					<!-- Pagination -->
					<div class="pagination">
						<?php if ($page > 1): ?>
							<a href="?page=<?php echo $page - 1; ?>" class="btn-prev">Previous</a>
						<?php endif; ?>
						<?php for ($i = 1; $i <= $totalPages; $i++): ?>
							<a href="?page=<?php echo $i; ?>" class="btn-page <?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
						<?php endfor; ?>
						<?php if ($page < $totalPages): ?>
							<a href="?page=<?php echo $page + 1; ?>" class="btn-next">Next</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->
</body>
</html>