$(function(){

	var $links = $('.index_list a[href^="#section"]');
	if (!$links.length) return;

	// 初期状態：1番上のリンクをactive
	$links.first().addClass('active');

	// --------------------------------------------------
	// スクロールでactive付け替え
	// --------------------------------------------------
	function updateActive() {
		var threshold = window.innerHeight * 0.25;
		var activeId  = null;

		$links.each(function () {
			var id  = $(this).attr('href');
			var $el = $(id);
			if (!$el.length) return;
			if ($el[0].getBoundingClientRect().top <= threshold) {
				activeId = id;
			}
		});

		$links.removeClass('active');
		if (activeId) {
			$links.filter('[href="' + activeId + '"]').addClass('active');
		} else {
			$links.first().addClass('active');
		}
	}

	if (typeof lenis !== 'undefined' && lenis) {
		lenis.on('scroll', updateActive);
	} else {
		$(window).on('scroll', updateActive);
	}

	// --------------------------------------------------
	// index_listのaタグクリックでアンカースクロール
	// --------------------------------------------------
	$links.on('click', function (e) {
		e.preventDefault();
		var target = $(this).attr('href');
		var $target = $(target);
		if (!$target.length) return;

		if (typeof lenis !== 'undefined' && lenis) {
			lenis.scrollTo($target[0], { duration: 1.0, offset: -100 });
		} else {
			$('html,body').animate({ scrollTop: $target.offset().top - 100 }, 600);
		}
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
	const scrpx = $(window).scrollTop();
	const innerH = window.innerHeight;
}
