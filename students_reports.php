<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch student status counts
$statusCounts = $conn->query("SELECT status, COUNT(*) AS count FROM students GROUP BY status");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>Student Reports</title>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
			<h1 class="title">Student Reports</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Student Reports</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Student Status Distribution</h3>
					</div>
						<div class="chart-card">
							<div id="status-chart"></div>
						</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->

	<script>
		var statusData = <?php 
			$data = [];
			while ($row = $statusCounts->fetch_assoc()) {
				$data[] = $row;
			}
			echo json_encode($data);
		?>;
		var options = {
			series: statusData.map(item => item.count),
			chart: { type: 'pie' },
			labels: statusData.map(item => item.status),
			colors: ['#1775F1', '#81D43A', '#FC3B56', '#F1F0F6'],
			legend: { position: 'bottom' }
		};
		var chart = new ApexCharts(document.querySelector("#status-chart"), options);
		chart.render();
	</script>
</body>
</html>