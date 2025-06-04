document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.dropdown > a').forEach(item => {
		item.addEventListener('click', event => {
			event.preventDefault(); // Prevent default link behavior
			const parent = item.parentElement;

			// Toggle the dropdown visibility
			if (parent.classList.contains('open')) {
				parent.classList.remove('open');
			} else {
				// Close other open dropdowns
				document.querySelectorAll('.dropdown').forEach(dropdown => {
					dropdown.classList.remove('open');
				});

				// Open the clicked dropdown
				parent.classList.add('open');
			}
		});
	});
});

document.addEventListener("DOMContentLoaded", () => {
	const dropdownToggles = document.querySelectorAll("[data-dropdown]");

	dropdownToggles.forEach((toggle) => {
		const dropdownMenu = toggle.nextElementSibling;

		toggle.addEventListener("click", (e) => {
			e.preventDefault();
			e.stopPropagation(); // Prevent click from propagating
			dropdownMenu.classList.toggle("show");

			// Close other dropdowns
			document.querySelectorAll(".dropdown-menu").forEach((menu) => {
				if (menu !== dropdownMenu) {
					menu.classList.remove("show");
				}
			});
		});
	});

	// Close dropdown when clicking outside
	document.addEventListener("click", () => {
		document.querySelectorAll(".dropdown-menu").forEach((menu) => {
			menu.classList.remove("show");
		});
	});
});
