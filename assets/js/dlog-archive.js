$(function(){

	cmnResizeHandler();

	var $container = $('.archives__container');
	var $moreBtn   = $('.more_btn[data-paged]');
	var $spinner   = $('.works-spinner');
	var $overlay   = $('<div class="works-overlay"></div>').appendTo('body');
	var isLoading  = false;

	function loadingStart() {
		isLoading = true;
		$spinner.show();
		$overlay.addClass('is-active');
	}

	function loadingEnd() {
		isLoading = false;
		$spinner.hide();
		$overlay.removeClass('is-active');
	}

	function fetchDlog(year, paged, mode) {
		if (isLoading) return;
		loadingStart();

		var fadeDone = (mode !== 'replace');
		var ajaxDone = false;
		var ajaxRes  = null;

		if (mode === 'replace') {
			requestAnimationFrame(function () {
				$container.addClass('is-loading');
				setTimeout(function () {
					fadeDone = true;
					if (ajaxDone) applyReplace(ajaxRes);
				}, 320);
			});
		}

		$.ajax({
			url:  dlogAjax.ajaxurl,
			type: 'POST',
			data: {
				action:    'dlog_filter',
				dlog_year: year,
				paged:     paged,
			},
			success: function (res) {
				$moreBtn.data('paged', paged).data('year', year);
				res.has_more ? $moreBtn.removeClass('is-hidden-btn') : $moreBtn.addClass('is-hidden-btn');
				$moreBtn.find('.next_load_count').text('(' + res.next_count + ')');

				if (mode === 'replace') {
					ajaxDone = true;
					ajaxRes  = res;
					if (fadeDone) applyReplace(res);
				} else {
					applyAppend(res);
				}
			},
			error: function () {
				$container.removeClass('is-loading');
				if (lenis) lenis.start();
				loadingEnd();
			},
		});

		function applyReplace(res) {
			if (lenis) lenis.stop();
			$container.empty();
			var $items = $(res.html || '<p>現在、登録された投稿はありません。</p>');
			$items.find('img').removeAttr('loading');
			$container.append($items);
			$container.removeClass('is-loading');
			waitForImages($container).then(function () {
				if (lenis) lenis.start();
				loadingEnd();
				requestAnimationFrame(function () {
					if (typeof scrollEventHandler === 'function') scrollEventHandler();
					if (typeof eventOnArchive    === 'function') eventOnArchive();
				});
			});
		}

		function applyAppend(res) {
			var $items = $(res.html);
			$items.find('img').removeAttr('loading');
			$container.append($items);
			waitForImages($container.find('.init')).then(function () {
				loadingEnd();
				requestAnimationFrame(function () {
					if (typeof scrollEventHandler === 'function') scrollEventHandler();
					if (typeof eventOnArchive    === 'function') eventOnArchive();
				});
			});
		}
	}

	function waitForImages($el) {
		var promises = $el.find('img').toArray().map(function (img) {
			var timeout = new Promise(function (resolve) { setTimeout(resolve, 5000); });
			var loaded  = (img.complete && img.naturalWidth > 0)
				? Promise.resolve()
				: new Promise(function (resolve) {
					img.onload  = resolve;
					img.onerror = resolve;
				});
			var decode  = loaded.then(function () {
				return img.decode ? img.decode().catch(function(){}) : Promise.resolve();
			});
			return Promise.race([decode, timeout]);
		});
		return Promise.all(promises.length ? promises : [Promise.resolve()]);
	}

	// --------------------------------------------------
	// 年フィルタークリック
	// --------------------------------------------------
	// 表示速度改善(第三段階・PJAX対応): document へのデリゲートイベントは
	// PJAXでこのスクリプトが再読込されるたびに再バインドされてしまうため、
	// 同じ名前空間のイベントを先に外してから登録し、多重発火を防ぐ。
	$(document).off('click.dlogArchive');
	$(document).on('click.dlogArchive', '.filter-link', function (e) {
		e.preventDefault();

		var $this = $(this);
		var year  = $this.data('year') || '';

		$('.filter-link').removeClass('active');
		$this.addClass('active');

		var newUrl = dlogAjax.archiveUrl;
		if (year) newUrl += '?dlog_year=' + year;
		history.pushState({ year: year }, '', newUrl);

		if (cmn_mode === 'sp') {
			var $trigger = $('.menu_container .accordion-trigger');
			if ($trigger.closest('.accordion-parent').find('.accordion-target').hasClass('isOpen')) {
				$trigger.trigger('click');
			}
		}

		if (lenis) lenis.scrollTo(0, { duration: 0.8 });
		else window.scrollTo({ top: 0, behavior: 'smooth' });

		fetchDlog(year, 1, 'replace');
	});

	// --------------------------------------------------
	// More ボタンクリック
	// --------------------------------------------------
	$(document).on('click.dlogArchive', '.more_btn[data-paged] a', function (e) {
		e.preventDefault();
		var year  = $moreBtn.data('year') || '';
		var paged = parseInt($moreBtn.data('paged')) + 1;
		fetchDlog(year, paged, 'append');
	});

	// --------------------------------------------------
	// ブラウザ 戻る / 進む
	// --------------------------------------------------
	// 表示速度改善(第三段階・PJAX対応):
	// このスクリプトは pjax.js によってPJAX遷移ごとに再読込されるため、
	// popstateリスナーが多重登録されないよう、前回登録分を必ず解除してから登録する。
	// また pjax.js が発行する pushState (state.pjax === true) は
	// ページ遷移そのものであり、このアーカイブ内フィルターの状態ではないため無視する。
	if (window.__dlogArchivePopstateHandler) {
		window.removeEventListener('popstate', window.__dlogArchivePopstateHandler);
	}
	window.__dlogArchivePopstateHandler = function (e) {
		var state = e.state || {};
		if (state.pjax) return; // PJAXによるページ遷移なので何もしない

		var year = state.year || '';

		$('.filter-link').removeClass('active');
		if (year) {
			$('.filter-link[data-year="' + year + '"]').addClass('active');
		} else {
			$('.filter-link[data-year=""]').addClass('active');
		}

		fetchDlog(year, 1, 'replace');
	};
	window.addEventListener('popstate', window.__dlogArchivePopstateHandler);

	// --------------------------------------------------
	// pjax.js からの離脱時クリーンアップ登録
	// --------------------------------------------------
	// 表示速度改善(第三段階・PJAX対応): 他ページへPJAX遷移した後もこの
	// popstateリスナーが残ってエラーになることを防ぐため、pjax.js が
	// 次の専用JSを読み込む直前に呼び出すクリーンアップ関数を登録しておく。
	window.__pjaxPageCleanup = function () {
		if (window.__dlogArchivePopstateHandler) {
			window.removeEventListener('popstate', window.__dlogArchivePopstateHandler);
			window.__dlogArchivePopstateHandler = null;
		}
		$(document).off('click.dlogArchive');
	};

	// --------------------------------------------------
	// 初期ロード時の history state を設定
	// --------------------------------------------------
	(function () {
		var params = new URLSearchParams(window.location.search);
		var year   = params.has('dlog_year') ? params.get('dlog_year') : '';
		// 表示速度改善(第三段階・PJAX対応): pjax.js が付与した state.pjax
		// フラグを消してしまわないよう、既存のstateをベースにマージする。
		var mergedState = Object.assign({}, history.state, { year: year });
		history.replaceState(mergedState, '', window.location.href);
	})();

});

function opening( sec ) {
}

function cmnOpenHandler(){
}

let mode_flg = 'pc';

function cmnResizeHandler(){
	cmnScrollHandler();
	if(cmn_mode === "sp" && mode_flg === "pc"){
		$('.menu_container .accordion-trigger').removeClass('active');
		$('.menu_container .accordion-target').removeClass('isOpen');
		mode_flg = "sp";

	}else if(cmn_mode === "pc" && mode_flg === "sp"){
		$('.menu_container .accordion-trigger').addClass('active');
		$('.menu_container .accordion-target').addClass('isOpen');
		mode_flg = "pc";
	}
}

function cmnScrollHandler(){
	const scrpx = $(window).scrollTop();
	const innerH = window.innerHeight;
}
