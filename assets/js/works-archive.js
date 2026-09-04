var URL = location.protocol + "//" + location.hostname,
referrer = document.referrer;

$(function(){

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
	//Filterの表示非常時
	if(cmn_mode == "pc"){
		$('a.filter_btn').addClass('active');
		$('.list_wrapper').addClass('isOpen');
	}

	// --------------------------------------------------
	// AJAX でポストを取得して DOM を更新
	// mode: 'replace' = 差し替え / 'append' = 追加
	// --------------------------------------------------
	function fetchWorks(taxonomy, term, paged, mode) {
		if (isLoading) return;
		loadingStart();

		// replace: rAF で初期状態を確定させてからフェードアウト開始
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
			url:    worksAjax.ajaxurl,
			type:   'POST',
			data: {
				action:   'works_filter',
				taxonomy: taxonomy,
				term:     term,
				paged:    paged,
			},
			success: function (res) {
				// more_btn の状態更新
				$moreBtn
					.data('paged',    paged)
					.data('taxonomy', taxonomy)
					.data('term',     term);
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

		// replace: コンテナ入れ替え → 画像ロード待ち → アニメイン → スクロール再開
		function applyReplace(res) {
			if (lenis) lenis.stop();
			$container.empty();
			var $items = $(res.html || '<p>現在、登録された投稿はありません。</p>');
			$items.find('img').removeAttr('loading'); // lazy を外して即時ロード
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

		// append: 追加 → 画像ロード待ち → アニメイン
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

	// 指定要素内の img がデコード完了するまで待つ（最大 5s でタイムアウト）
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
	// フィルタークリック
	// --------------------------------------------------
	$(document).on('click', '.filter-link', function (e) {
		e.preventDefault();

		var $this    = $(this);
		var taxonomy = $this.data('taxonomy') || '';
		var term     = $this.data('term')     || '';

		// active クラス付け替え（同グループ内 + 他グループのALL）
		$('ul.category-list').find('.filter-link').removeClass('active');
		if (!taxonomy && !term) {
			$('.filter-link[data-taxonomy=""][data-term=""]').addClass('active');
		} else {
			$('.filter-link[data-taxonomy=""][data-term=""]').removeClass('active');
			$this.addClass('active');
		}

		// URL 更新
		var newUrl = worksAjax.archiveUrl;
		if (taxonomy && term) {
			newUrl += '?' + taxonomy + '=' + term;
		}
		history.pushState({ taxonomy: taxonomy, term: term }, '', newUrl);

		

		//SPサイズならアコーディオンを閉じる
		console.log(cmn_mode);
		if(cmn_mode === "sp"){
			// メニューを閉じる
			var $trigger = $('.menu_container .accordion-trigger');
			if ($trigger.closest('.accordion-parent').find('.accordion-target').hasClass('isOpen')) {
				$trigger.trigger('click');
			}
		}

		// ページトップへスムーズスクロール
		if (lenis) lenis.scrollTo(0, { duration: 0.8 });
		else window.scrollTo({ top: 0, behavior: 'smooth' });

		fetchWorks(taxonomy, term, 1, 'replace');
	});

	// --------------------------------------------------
	// More ボタンクリック
	// --------------------------------------------------
	$(document).on('click', '.more_btn[data-paged] a', function (e) {
		e.preventDefault();

		var taxonomy = $moreBtn.data('taxonomy') || '';
		var term     = $moreBtn.data('term')     || '';
		var paged    = parseInt($moreBtn.data('paged')) + 1;

		fetchWorks(taxonomy, term, paged, 'append');
	});

	// --------------------------------------------------
	// ブラウザ 戻る / 進む
	// --------------------------------------------------
	window.addEventListener('popstate', function (e) {
		var state    = e.state || {};
		var taxonomy = state.taxonomy || '';
		var term     = state.term     || '';

		$('.filter-link').removeClass('active');
		if (taxonomy && term) {
			$('.filter-link[data-taxonomy="' + taxonomy + '"][data-term="' + term + '"]').addClass('active');
		} else {
			$('.filter-link[data-taxonomy=""][data-term=""]').addClass('active');
		}

		fetchWorks(taxonomy, term, 1, 'replace');
	});

	// --------------------------------------------------
	// 初期ロード時の history state を設定
	// --------------------------------------------------
	(function () {
		var params   = new URLSearchParams(window.location.search);
		var taxonomy = '';
		var term     = '';

		if (params.has('works-category')) {
			taxonomy = 'works-category';
			term     = params.get('works-category');
		} else if (params.has('client-category')) {
			taxonomy = 'client-category';
			term     = params.get('client-category');
		}

		history.replaceState({ taxonomy: taxonomy, term: term }, '', window.location.href);
	})();

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
