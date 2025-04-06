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
