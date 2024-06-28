document.addEventListener('DOMContentLoaded', () => {
	const flyoutMenu = document.getElementById('as-miusage-api-flyout');

	if (!flyoutMenu) {
		return;
	}

	const head = flyoutMenu.querySelector('.as-miusage-api-flyout-head');

	head.addEventListener('click', (e) => {
		e.preventDefault();
		flyoutMenu.classList.toggle('opened');
	});

	const wpfooter = document.getElementById('wpfooter');

	if (!wpfooter) {
		return;
	}

	const overlapSelectors = [
		'.as-miusage-api-page-logs-archive',
		'.as-miusage-api-tab-tools-action-scheduler',
		'.as-miusage-api-page-reports',
		'.as-miusage-api-tab-tools-debug-events',
		'.as-miusage-api-tab-connections',
	];

	const overlapElements = overlapSelectors.reduce((acc, selector) => {
		const elems = document.querySelectorAll(selector);
		return acc.concat(Array.prototype.slice.call(elems));
	}, []);

	function debounce(func, wait) {
		let timeout;
		return function () {
			clearTimeout(timeout);
			timeout = setTimeout(func, wait);
		};
	}

	function onResizeOrScroll() {
		const wpfooterRect = wpfooter.getBoundingClientRect();
		const wpfooterTop = wpfooterRect.top + window.pageYOffset;
		const wpfooterBottom = wpfooterTop + wpfooterRect.height;
		const overlapBottom =
			overlapElements.length > 0
				? Math.max.apply(
						null,
						overlapElements.map((el) => {
							const rect = el.getBoundingClientRect();
							return (
								rect.top + window.pageYOffset + rect.height + 85
							);
						}),
				  )
				: 0;
		const viewTop = window.pageYOffset;
		const viewBottom = viewTop + window.innerHeight;

		if (
			wpfooterBottom <= viewBottom &&
			wpfooterTop >= viewTop &&
			overlapBottom > viewBottom
		) {
			flyoutMenu.classList.add('out');
		} else {
			flyoutMenu.classList.remove('out');
		}
	}

	window.addEventListener('resize', debounce(onResizeOrScroll, 50));
	window.addEventListener('scroll', debounce(onResizeOrScroll, 50));

	onResizeOrScroll();
});
