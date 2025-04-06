<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch all users
$users = $conn->query("SELECT user_id, username, user_role, account_status, last_login FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>View Users</title>
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
			<h1 class="title">View Users</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">View Users</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>All Users</h3>
					</div>
					<table class="styled-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Username</th>
								<th>Role</th>
								<th>Status</th>
								<th>Last Login</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($row = $users->fetch_assoc()): ?>
								<tr>
									<td><?php echo $row['user_id']; ?></td>
									<td><?php echo $row['username']; ?></td>
									<td><?php echo $row['user_role']; ?></td>
									<td><?php echo $row['account_status']; ?></td>
									<td><?php echo $row['last_login'] ? $row['last_login'] : 'Never'; ?></td>
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
