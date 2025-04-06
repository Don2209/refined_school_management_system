// JavaScript to handle sidebar popups
document.addEventListener('DOMContentLoaded', () => {
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
					const menu = popup.querySelector('.popup-menu');
					if (menu) menu.style.display = 'none';
				});

				// Open the clicked popup
				parent.classList.add('open');
				popupMenu.style.display = 'block';
			}
		});
	});
});
