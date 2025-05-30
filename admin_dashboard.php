<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_role']) || empty($_SESSION['user_role'])) {
    header("Location: index.php");
    exit();
}

// Include the database configuration
include 'config.php';

// Fetch user role and associated ID
$userRole = $_SESSION['user_role'] ?? null;
$associatedId = isset($_SESSION['associated_id']) ? intval($_SESSION['associated_id']) : 0;

// Initialize default values
$totalStudents = 0;
$totalStaff = 0;
$totalClasses = 0;
$totalSubjects = 0;
$feeSummary = ['total_paid' => 0, 'total_balance' => 0];

if ($userRole === 'Admin') {
    $totalStudents = $conn->query("SELECT COUNT(*) AS count FROM students")->fetch_assoc()['count'];
    $totalStaff = $conn->query("SELECT COUNT(*) AS count FROM staff")->fetch_assoc()['count'];
    $totalClasses = $conn->query("SELECT COUNT(*) AS count FROM classes")->fetch_assoc()['count'];
    $totalSubjects = $conn->query("SELECT COUNT(*) AS count FROM subjects")->fetch_assoc()['count'];
    $feeSummary = $conn->query("SELECT SUM(amount_paid) AS total_paid, SUM(balance) AS total_balance FROM fee_management")->fetch_assoc();
} elseif ($userRole === 'Teacher' && $associatedId > 0) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM students WHERE current_class_id IN (SELECT class_id FROM classes WHERE class_teacher_id = ?)");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $stmt->bind_result($totalStudents);
    $stmt->fetch();
    $stmt->close();

    $totalStaff = 1;

    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM classes WHERE class_teacher_id = ?");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $stmt->bind_result($totalClasses);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM class_subjects WHERE teacher_id = ?");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $stmt->bind_result($totalSubjects);
    $stmt->fetch();
    $stmt->close();
} elseif ($userRole === 'Parent' && $associatedId > 0) {
    // Parents see data related to their child
    $totalStudents = 1; // Parents only see their child
    $totalStaff = 0; // Parents don't see staff data

    // Fetch the class associated with the parent's child
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM classes WHERE class_id = (
        SELECT current_class_id FROM students WHERE student_id = ?
    )");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $stmt->bind_result($totalClasses);
    $stmt->fetch();
    $stmt->close();

    // Fetch the subjects associated with the parent's child
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM class_subjects WHERE class_id = (
        SELECT current_class_id FROM students WHERE student_id = ?
    )");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $stmt->bind_result($totalSubjects);
    $stmt->fetch();
    $stmt->close();

    // Fetch the fee summary for the parent's child
    $stmt = $conn->prepare("SELECT SUM(amount_paid), SUM(balance) FROM fee_management WHERE student_id = ?");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $stmt->bind_result($feeSummary['total_paid'], $feeSummary['total_balance']);
    $stmt->fetch();
    $stmt->close();

    // Fetch student enrollment trends for the parent's child
    $enrollmentTrends = $conn->query("SELECT academic_years.name AS year, COUNT(students.student_id) AS total_students 
        FROM students 
        JOIN academic_years ON students.enrollment_date BETWEEN academic_years.start_date AND academic_years.end_date 
        WHERE students.student_id = $associatedId 
        GROUP BY academic_years.name");

    // Fetch class distribution for the parent's child
    $classDistribution = $conn->query("SELECT classes.class_name, COUNT(students.student_id) AS total_students 
        FROM students 
        JOIN classes ON students.current_class_id = classes.class_id 
        WHERE students.student_id = $associatedId 
        GROUP BY classes.class_name");

    // Fetch gender distribution for the parent's child
    $genderDistribution = $conn->query("SELECT gender, COUNT(*) AS count FROM students WHERE student_id = $associatedId");
}

// Fetch student enrollment trends
$enrollmentTrends = $conn->query("SELECT academic_years.name AS year, COUNT(students.student_id) AS total_students 
    FROM students 
    JOIN academic_years ON students.enrollment_date BETWEEN academic_years.start_date AND academic_years.end_date 
    GROUP BY academic_years.name");

// Fetch class distribution grouped by class
$classDistribution = $conn->query("SELECT classes.class_name, COUNT(students.student_id) AS total_students 
    FROM students 
    JOIN classes ON students.current_class_id = classes.class_id 
    GROUP BY classes.class_name");

// Prepare data for class chart
$classData = [];
while ($row = $classDistribution->fetch_assoc()) {
    $classData[] = $row;
}

// Fetch gender distribution
$genderDistribution = $conn->query("SELECT gender, COUNT(*) AS count FROM students GROUP BY gender");
$genderData = [];
while ($row = $genderDistribution->fetch_assoc()) {
    $genderData[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="css/style.css">
	<title>AdminSite</title>
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
			<h1 class="title">Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Dashboard</a></li>
			</ul>
			<div class="info-data">
				<div class="card">
					<div class="head">
						<div>
							<h2><?php echo $totalStudents; ?></h2>
							<p>Total Students</p>
						</div>
						<i class='bx bxs-user icon'></i>
					</div>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2><?php echo $totalStaff; ?></h2>
							<p>Total Staff</p>
						</div>
						<i class='bx bxs-group icon'></i>
					</div>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2><?php echo $totalClasses; ?></h2>
							<p>Total Classes</p>
						</div>
						<i class='bx bxs-school icon'></i>
					</div>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2><?php echo $totalSubjects; ?></h2>
							<p>Total Subjects</p>
						</div>
						<i class='bx bxs-book icon'></i>
					</div>
				</div>
			</div>

			<!-- Fee Summary -->
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Fee Summary</h3>
					</div>
					<div id="fee-chart"></div>
				</div>

				<!-- Student Enrollment Trends -->
				<div class="content-data">
					<div class="head">
						<h3>Student Enrollment Trends</h3>
					</div>
					<div id="enrollment-chart"></div>
				</div>
			</div>

			<!-- Class Distribution -->
			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Class Distribution</h3>
					</div>
					<div id="class-chart"></div>
				</div>

				<!-- Gender Distribution -->
				<div class="content-data">
					<div class="head">
						<h3>Gender Distribution</h3>
					</div>
					<div id="gender-chart"></div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->

	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script>
		// Fee Summary Chart
		var feeOptions = {
			series: [<?php echo $feeSummary['total_paid'] ?? 0; ?>, <?php echo $feeSummary['total_balance'] ?? 0; ?>],
			chart: { type: 'pie' },
			labels: ['Total Fees Paid', 'Total Fees Balance']
		};
		var feeChart = new ApexCharts(document.querySelector("#fee-chart"), feeOptions);
		feeChart.render();

		// Enrollment Trends Chart
		var enrollmentData = <?php 
			$enrollmentData = [];
			while ($row = $enrollmentTrends->fetch_assoc()) {
				$enrollmentData[] = $row;
			}
			echo json_encode($enrollmentData);
		?>;
		var enrollmentOptions = {
			series: [{
				name: 'Total Students',
				data: enrollmentData.map(item => item.total_students)
			}],
			chart: { type: 'bar' },
			xaxis: { categories: enrollmentData.map(item => item.year) }
		};
		var enrollmentChart = new ApexCharts(document.querySelector("#enrollment-chart"), enrollmentOptions);
		enrollmentChart.render();

			// Class Distribution Chart
		var classData = <?php echo json_encode($classData); ?>;
		var classOptions = {
			series: [{
				name: 'Total Students',
				data: classData.map(item => item.total_students)
			}],
			chart: {
				type: 'bar',
				height: 350,
				toolbar: { show: false }
			},
			plotOptions: {
				bar: {
					horizontal: false,
					columnWidth: '55%',
					endingShape: 'rounded'
				}
			},
			dataLabels: { enabled: false },
			stroke: { show: true, width: 2, colors: ['transparent'] },
			xaxis: {
				categories: classData.map(item => item.class_name),
				title: { text: 'Classes' }
			},
			yaxis: {
				title: { text: 'Total Students' }
			},
			fill: { opacity: 1 },
			colors: ['#1775F1'],
			tooltip: {
				y: { formatter: function (val) { return val + " students"; } }
			}
		};
		var classChart = new ApexCharts(document.querySelector("#class-chart"), classOptions);
		classChart.render();

		// Gender Distribution Chart
		var genderData = <?php echo json_encode($genderData); ?>;
		var genderOptions = {
			series: genderData.map(item => item.count),
			chart: { type: 'pie' },
			labels: genderData.map(item => item.gender),
			colors: ['#1775F1', '#81D43A', '#FC3B56', '#F1F0F6'],
			legend: { position: 'bottom' }
		};
		var genderChart = new ApexCharts(document.querySelector("#gender-chart"), genderOptions);
		genderChart.render();

		// JavaScript to handle side popup functionality
		document.querySelectorAll('.popup > a').forEach(item => {
			item.addEventListener('click', event => {
				event.preventDefault(); // Prevent default link behavior
				const parent = item.parentElement;
				const popupMenu = parent.querySelector('.popup-menu');

				// Toggle the popup visibility
				if (parent.classList.contains('open')) {
					parent.classList.remove('open');
					popupMenu.style.display = 'none';
				} else {
					// Close other open popups
					document.querySelectorAll('.popup').forEach(popup => {
						popup.classList.remove('open');
						popup.querySelector('.popup-menu').style.display = 'none';
					});

					// Open the clicked popup
					parent.classList.add('open');
					popupMenu.style.display = 'block';
				}
			});
		});
	</script>
</body>
</html>


