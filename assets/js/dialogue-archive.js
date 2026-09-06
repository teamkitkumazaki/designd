document.addEventListener('DOMContentLoaded', function(){

	var container = document.querySelector('.archives__container');
	var moreBtn   = document.querySelector('.more_btn[data-paged]');
	var spinner   = document.querySelector('.works-spinner');
	var overlay   = document.createElement('div');
	overlay.className = 'works-overlay';
	document.body.appendChild(overlay);
	var isLoading = false;

	function loadingStart() {
		isLoading = true;
		if (spinner) spinner.style.display = '';
		overlay.classList.add('is-active');
	}

	function loadingEnd() {
		isLoading = false;
		if (spinner) spinner.style.display = 'none';
		overlay.classList.remove('is-active');
	}

	function fetchDialogue(paged) {
		if (isLoading) return;
		loadingStart();

		var params = new URLSearchParams();
		params.append('action', 'dialogue_filter');
		params.append('paged', paged);

		fetch(dialogueAjax.ajaxurl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: params.toString()
		})
			.then(function (response) { return response.json(); })
			.then(function (res) {
				if (moreBtn) moreBtn.dataset.paged = paged;
				if (moreBtn) {
					if (res.has_more) moreBtn.classList.remove('is-hidden-btn');
					else moreBtn.classList.add('is-hidden-btn');
					var countEl = moreBtn.querySelector('.next_load_count');
					if (countEl) countEl.textContent = '(' + res.next_count + ')';
				}

				applyAppend(res);
			})
			.catch(function () {
				loadingEnd();
			});

		function applyAppend(res) {
			var temp = document.createElement('div');
			temp.innerHTML = res.html;
			var items = Array.prototype.slice.call(temp.children);
			items.forEach(function (item) {
				item.querySelectorAll('img').forEach(function (img) {
					img.removeAttribute('loading');
				});
				container.appendChild(item);
			});
			waitForImages(container.querySelectorAll('.init')).then(function () {
				loadingEnd();
				requestAnimationFrame(function () {
					if (typeof scrollEventHandler === 'function') scrollEventHandler();
					if (typeof eventOnArchive    === 'function') eventOnArchive();
				});
			});
		}
	}

	function waitForImages(imgList) {
		var promises = Array.prototype.slice.call(imgList).map(function (img) {
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
	// More ボタンクリック
	// --------------------------------------------------
	document.addEventListener('click', function (e) {
		var link = e.target.closest('.more_btn[data-paged] a');
		if (!link) return;
		e.preventDefault();

		var paged = parseInt(moreBtn.dataset.paged || '1') + 1;

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
	const scrpx = window.pageYOffset || document.documentElement.scrollTop;
	const innerH = window.innerHeight;
}
