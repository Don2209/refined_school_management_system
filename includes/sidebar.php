<section id="sidebar">
	<a href="#" class="brand">
		<i class='bx bxs-smile icon'></i>
		<span class="brand-name">School Management System</span>
	</a>
	<ul class="side-menu">
		<li><a href="./admin_dashboard.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
		<?php if ($userRole === 'Parent'): ?>
			<li><a href="students_view.php"><i class='bx bxs-user icon'></i> View Student Details</a></li>
			<li><a href="parent_results_view.php"><i class='bx bxs-bar-chart-alt-2 icon'></i> View Results</a></li>
			<li><a href="fees_summary.php"><i class='bx bxs-wallet icon'></i> View Fees</a></li>
			<li><a href="classes_view.php"><i class='bx bxs-school icon'></i> View Class</a></li>
		<?php elseif ($userRole === 'Teacher'): ?>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-user icon'></i> Students <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="students_view.php">View All Students</a></li>
					<li><a href="students_reports.php">Student Reports</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-school icon'></i> Classes <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="classes_view.php">View All Classes</a></li>
					<li><a href="classes_distribution.php">Class Distribution</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-bar-chart-alt-2 icon'></i> Academic Results <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="results_view.php">View Results</a></li>
					<li><a href="results_add.php">Add Results</a></li>
				</ul>
			</li>
		<?php else: ?>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-user icon'></i> Students <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="students_view.php">View All Students</a></li>
					<li><a href="students_add.php">Add New Student</a></li>
					<li><a href="students_reports.php">Student Reports</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-group icon'></i> Staff <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="staff_view.php">View All Staff</a></li>
					<li><a href="staff_add.php">Add New Staff</a></li>
					<li><a href="staff_reports.php">Staff Reports</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-school icon'></i> Classes <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="classes_view.php">View All Classes</a></li>
					<li><a href="classes_add.php">Add New Class</a></li>
					<li><a href="classes_distribution.php">Class Distribution</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-book icon'></i> Subjects <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="subjects_view.php">View All Subjects</a></li>
					<li><a href="subjects_add.php">Add New Subject</a></li>
					<li><a href="subjects_allocation.php">Subject Allocation</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-wallet icon'></i> Fees <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="fees_summary.php">Fee Summary</a></li>
					<li><a href="fees_add_payment.php">Add Payment</a></li>
					<li><a href="fees_outstanding.php">Outstanding Balances</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-bar-chart-alt-2 icon'></i> Academic Results <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="results_view.php">View Results</a></li>
					<li><a href="results_add.php">Add Results</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-user-circle icon'></i> User Management <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="users_view.php">View Users</a></li>
					<li><a href="users_add.php">Add User</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-user-detail icon'></i> Guardians <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="guardians_view.php">View Guardians</a></li>
					<li><a href="guardians_add.php">Add Guardian</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-calendar icon'></i> Academic Years <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="academic_years_view.php">View Academic Years</a></li>
					<li><a href="academic_years_add.php">Add Academic Year</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#"><i class='bx bxs-calendar-event icon'></i> Terms <i class='bx bx-chevron-down icon-right'></i></a>
				<ul class="dropdown-menu">
					<li><a href="terms_view.php">View Terms</a></li>
					<li><a href="terms_add.php">Add Term</a></li>
				</ul>
			</li>
		<?php endif; ?>
	</ul>
</section>


