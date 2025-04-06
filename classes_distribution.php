<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration
include 'config.php';

// Fetch class distribution
$classDistribution = $conn->query("SELECT stream, COUNT(*) AS count FROM classes GROUP BY stream");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>Class Distribution</title>
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
			<h1 class="title">Class Distribution</h1>
			<ul class="breadcrumbs">
				<li><a href="admin_dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Class Distribution</a></li>
			</ul>
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Class Stream Distribution</h3>
					</div>
					<div class="chart-card">
						<div id="class-chart"></div>
					</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->

	<script>
		var classData = <?php 
			$data = [];
			while ($row = $classDistribution->fetch_assoc()) {
				$data[] = $row;
			}
			echo json_encode($data);
		?>;
		var options = {
			series: classData.map(item => item.count),
			chart: { type: 'pie' },
			labels: classData.map(item => item.stream),
			colors: ['#1775F1', '#81D43A', '#FC3B56', '#F1F0F6'],
			legend: { position: 'bottom' }
		};
		var chart = new ApexCharts(document.querySelector("#class-chart"), options);
		chart.render();
	</script>
</body>
</html>
