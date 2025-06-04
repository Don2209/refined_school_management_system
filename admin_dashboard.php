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

// Check user role and adjust visibility
$isAdmin = ($userRole === 'Admin');

// Initialize default values
$totalStudents = 0;
$totalStaff = 0;
$totalClasses = 0;
$totalSubjects = 0;
$feeSummary = ['total_paid' => 0, 'total_balance' => 0];

if ($userRole === 'Admin') {
    // Admin sees all data
    $totalStudents = $conn->query("SELECT COUNT(*) AS count FROM students")->fetch_assoc()['count'];
    $totalStaff = $conn->query("SELECT COUNT(*) AS count FROM staff")->fetch_assoc()['count'];
    $totalClasses = $conn->query("SELECT COUNT(*) AS count FROM classes")->fetch_assoc()['count'];
    $totalSubjects = $conn->query("SELECT COUNT(*) AS count FROM subjects")->fetch_assoc()['count'];
    $feeSummary = $conn->query("SELECT SUM(amount_paid) AS total_paid, SUM(balance) AS total_balance FROM fee_management")->fetch_assoc();

    // Fetch all enrollment trends
    $enrollmentTrends = $conn->query("SELECT academic_years.name AS year, COUNT(students.student_id) AS total_students 
        FROM students 
        JOIN academic_years ON students.enrollment_date BETWEEN academic_years.start_date AND academic_years.end_date 
        GROUP BY academic_years.name");
    $enrollmentData = [];
    while ($row = $enrollmentTrends->fetch_assoc()) {
        $enrollmentData[] = $row;
    }

    // Fetch all class distributions
    $classDistribution = $conn->query("SELECT classes.class_name, COUNT(students.student_id) AS total_students 
        FROM students 
        JOIN classes ON students.current_class_id = classes.class_id 
        GROUP BY classes.class_name");
    $classData = [];
    while ($row = $classDistribution->fetch_assoc()) {
        $classData[] = $row;
    }

    // Fetch gender distribution for all students
    $genderDistribution = $conn->query("SELECT students.gender, COUNT(*) AS count 
        FROM students 
        GROUP BY students.gender");
    $genderData = [];
    while ($row = $genderDistribution->fetch_assoc()) {
        $genderData[] = $row;
    }
} elseif ($userRole === 'Teacher' && $associatedId > 0) {
    // Filter total students taught by the teacher
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM students WHERE current_class_id IN (SELECT class_id FROM classes WHERE class_teacher_id = ?)");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $stmt->bind_result($totalStudents);
    $stmt->fetch();
    $stmt->close();

    // Filter total classes assigned to the teacher
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM classes WHERE class_teacher_id = ?");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $stmt->bind_result($totalClasses);
    $stmt->fetch();
    $stmt->close();

    // Filter total subjects taught by the teacher
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM class_subjects WHERE teacher_id = ?");
    $stmt->bind_param("i", $associatedId);
    $stmt->execute();
    $stmt->bind_result($totalSubjects);
    $stmt->fetch();
    $stmt->close();

    // Teachers only see their own data, so no fee summary or staff count
    $totalStaff = 1; // Only the teacher themselves
    $feeSummary = ['total_paid' => 0, 'total_balance' => 0];

    // Fetch staff associated with the same school/department as the teacher
    $staffQuery = $conn->prepare("SELECT staff.staff_id, CONCAT(staff.first_name, ' ', staff.last_name) AS name 
        FROM staff 
        WHERE staff.position = 'Teacher' AND staff.staff_id != ?");
    $staffQuery->bind_param("i", $associatedId);
    $staffQuery->execute();
    $staffResult = $staffQuery->get_result();
    $associatedStaff = [];
    while ($row = $staffResult->fetch_assoc()) {
        $associatedStaff[] = $row;
    }
    $staffQuery->close();

    // Filter enrollment trends for the teacher's classes
    $enrollmentTrends = $conn->query("SELECT academic_years.name AS year, COUNT(students.student_id) AS total_students 
        FROM students 
        JOIN academic_years ON students.enrollment_date BETWEEN academic_years.start_date AND academic_years.end_date 
        WHERE current_class_id IN (SELECT class_id FROM classes WHERE class_teacher_id = $associatedId)
        GROUP BY academic_years.name");

    // Fetch classes associated with the teacher and their linked staff
    $classDistribution = $conn->query("SELECT classes.class_name, COUNT(students.student_id) AS total_students 
        FROM students 
        JOIN classes ON students.current_class_id = classes.class_id 
        WHERE classes.class_teacher_id = $associatedId 
        OR classes.class_teacher_id IN (
            SELECT staff_id FROM staff 
            WHERE staff_id IN (
                SELECT staff_id FROM staff 
                WHERE position = 'Teacher' AND staff_id != $associatedId
            )
        )
        GROUP BY classes.class_name");
    $classData = [];
    while ($row = $classDistribution->fetch_assoc()) {
        $classData[] = $row;
    }

    // Fetch gender distribution for students in classes linked to the teacher and their associated staff
    $genderDistribution = $conn->query("SELECT students.gender, COUNT(*) AS count 
        FROM students 
        WHERE current_class_id IN (
            SELECT class_id FROM classes 
            WHERE class_teacher_id = $associatedId 
            OR class_teacher_id IN (
                SELECT staff_id FROM staff 
                WHERE staff_id IN (
                    SELECT staff_id FROM staff 
                    WHERE position = 'Teacher' AND staff_id != $associatedId
                )
            )
        )
        GROUP BY students.gender");
    $genderData = [];
    while ($row = $genderDistribution->fetch_assoc()) {
        $genderData[] = $row;
    }
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

    // Filter enrollment trends for the parent's child
    $enrollmentTrends = $conn->query("SELECT academic_years.name AS year, COUNT(students.student_id) AS total_students 
        FROM students 
        JOIN academic_years ON students.enrollment_date BETWEEN academic_years.start_date AND academic_years.end_date 
        WHERE students.student_id = $associatedId 
        GROUP BY academic_years.name");

    // Filter class distribution for the parent's child
    $classDistribution = $conn->query("SELECT classes.class_name, COUNT(students.student_id) AS total_students 
        FROM students 
        JOIN classes ON students.current_class_id = classes.class_id 
        WHERE students.student_id = $associatedId 
        GROUP BY classes.class_name");

    // Filter gender distribution for the parent's child
    $genderDistribution = $conn->query("SELECT gender, COUNT(*) AS count FROM students WHERE student_id = $associatedId");
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
				<?php if (!$isAdmin && !empty($associatedStaff)): ?>
				<div class="card">
					<div class="head">
						<div>
							<h2><?php echo count($associatedStaff); ?></h2>
							<p>Associated Staff</p>
						</div>
						<i class='bx bxs-user-detail icon'></i>
					</div>
					<ul>
						<?php foreach ($associatedStaff as $staff): ?>
							<li><?php echo htmlspecialchars($staff['name']); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
			</div>

			<?php if ($isAdmin): ?>
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
			<?php endif; ?>

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
		<?php if ($isAdmin): ?>
		// Fee Summary Chart
		var feeOptions = {
			series: [<?php echo $feeSummary['total_paid'] ?? 0; ?>, <?php echo $feeSummary['total_balance'] ?? 0; ?>],
			chart: { type: 'pie' },
			labels: ['Total Fees Paid', 'Total Fees Balance']
		};
		var feeChart = new ApexCharts(document.querySelector("#fee-chart"), feeOptions);
		feeChart.render();

		// Enrollment Trends Chart
		var enrollmentOptions = {
			series: [{
				name: 'Total Students',
				data: <?php echo json_encode(array_column($enrollmentData, 'total_students')); ?>
			}],
			chart: { type: 'bar' },
			xaxis: { categories: <?php echo json_encode(array_column($enrollmentData, 'year')); ?> }
		};
		var enrollmentChart = new ApexCharts(document.querySelector("#enrollment-chart"), enrollmentOptions);
		enrollmentChart.render();
		<?php endif; ?>

		// Class Distribution Chart
		var classOptions = {
			series: [{
				name: 'Total Students',
				data: <?php echo json_encode(array_column($classData, 'total_students')); ?>
			}],
			chart: { type: 'bar' },
			xaxis: { categories: <?php echo json_encode(array_column($classData, 'class_name')); ?> }
		};
		var classChart = new ApexCharts(document.querySelector("#class-chart"), classOptions);
		classChart.render();

		// Gender Distribution Chart
		var genderOptions = {
			series: <?php echo json_encode(array_column($genderData, 'count')); ?>,
			chart: { type: 'pie' },
			labels: <?php echo json_encode(array_column($genderData, 'gender')); ?>
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


