<?php
// Ensure $isAdmin is defined
if (!isset($isAdmin)) {
    $isAdmin = ($_SESSION['user_role'] ?? '') === 'Admin';
}
?>

<nav>
	<i class='bx bx-menu toggle-sidebar'></i>
	<form action="#">
		<div class="form-group">
			<input type="text" placeholder="Search...">
			<i class='bx bx-search icon'></i>
		</div>
	</form>
	<div class="nav-links">
		<!-- User Profile Dropdown -->
		<?php if (!$isAdmin && !empty($associatedStaff)): ?>
		<div class="dropdown">
			<a href="#" class="dropdown-toggle" data-dropdown>
				<i class='bx bx-user icon'></i>
			</a>
			<div class="dropdown-menu">
				<h4>Associated Staff</h4>
				<ul>
					<?php foreach ($associatedStaff as $staff): ?>
						<li>
							<div class="profile-card">
								<div class="profile-info">
									<h5><?php echo htmlspecialchars($staff['name']); ?></h5>
									<p>Staff ID: <?php echo htmlspecialchars($staff['staff_id']); ?></p>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>

		<a href="./logout.php" class="nav-link">
			<i class='bx bx-exit icon'></i>
		</a>
	</div>
</nav>
