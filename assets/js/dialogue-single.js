document.addEventListener('DOMContentLoaded', function(){

	var links = Array.prototype.slice.call(document.querySelectorAll('.index_list a[href^="#section"]'));
	if (!links.length) return;

	// 初期状態：1番上のリンクをactive
	links[0].classList.add('active');

	// --------------------------------------------------
	// スクロールでactive付け替え
	// --------------------------------------------------
	function updateActive() {
		var threshold = window.innerHeight * 0.25;
		var activeId  = null;

		links.forEach(function (link) {
			var id  = link.getAttribute('href');
			var el = document.querySelector(id);
			if (!el) return;
			if (el.getBoundingClientRect().top <= threshold) {
				activeId = id;
			}
		});

		links.forEach(function (link) { link.classList.remove('active'); });
		if (activeId) {
			links.forEach(function (link) {
				if (link.getAttribute('href') === activeId) link.classList.add('active');
			});
		} else {
			links[0].classList.add('active');
		}
	}

	if (typeof lenis !== 'undefined' && lenis) {
		lenis.on('scroll', updateActive);
	} else {
		window.addEventListener('scroll', updateActive);
	}

	// --------------------------------------------------
	// index_listのaタグクリックでアンカースクロール
	// --------------------------------------------------
	links.forEach(function (link) {
		link.addEventListener('click', function (e) {
			e.preventDefault();
			var target = document.querySelector(link.getAttribute('href'));
			if (!target) return;

			if (typeof lenis !== 'undefined' && lenis) {
				lenis.scrollTo(target, { duration: 1.0, offset: -100 });
			} else {
				var top = target.getBoundingClientRect().top + window.pageYOffset - 100;
				window.scrollTo({ top: top, behavior: 'smooth' });
			}
		});
	});

});

function opening( sec ) {
}

function cmnOpenHandler(){
}

function cmnResizeHandler(){
	cmnScrollHandler();
}

function cmnScrollHandler(){
	const scrpx = window.pageYOffset || document.documentElement.scrollTop;
	const innerH = window.innerHeight;
}
