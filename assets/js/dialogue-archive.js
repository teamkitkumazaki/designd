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

	function fetchDialogue(paged) {
		if (isLoading) return;
		loadingStart();

		$.ajax({
			url:  dialogueAjax.ajaxurl,
			type: 'POST',
			data: {
				action: 'dialogue_filter',
				paged:  paged,
			},
			success: function (res) {
				$moreBtn.data('paged', paged);
				res.has_more ? $moreBtn.removeClass('is-hidden-btn') : $moreBtn.addClass('is-hidden-btn');
				$moreBtn.find('.next_load_count').text('(' + res.next_count + ')');

				applyAppend(res);
			},
			error: function () {
				loadingEnd();
			},
		});

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

	$(document).on('click', '.more_btn[data-paged] a', function (e) {
		e.preventDefault();
		var paged = parseInt($moreBtn.data('paged')) + 1;
		fetchDialogue(paged);
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
