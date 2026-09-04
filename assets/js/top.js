var URL = location.protocol + "//" + location.hostname,
referrer = document.referrer;

let openingFlg = true;

document.addEventListener('DOMContentLoaded', function () {
	var elm = document.getElementById('js-opening');
	if (elm) elm.style.display = 'none';

	if (-1 !== referrer.indexOf(URL)) {
		openingFlg = false;
	} else {
		document.body.classList.add('is-fix');
		if (elm) elm.style.display = '';
		setTimeout(function() {
			if (elm) elm.classList.add('is-end');
			setTimeout(function() {
				document.body.classList.remove('is-fix');
				openingFlg = false;
				opening(200);
			}, 500);
		}, 5000);
	}
});

function opening( sec ) {
	setTimeout(function(){
		var copyJp = document.querySelector('.introduction__copy-jp');
		var introBg = document.querySelector('.introduction__bg');
		if (copyJp) copyJp.classList.remove('init');
		if (introBg) introBg.classList.remove('init');
	},sec);
}

function cmnOpenHandler(){
	if(!openingFlg) opening(100)
}

document.addEventListener('DOMContentLoaded', function () {
	// ピン留めセクションの初期bottom位置を記録
	document.querySelectorAll('.top-dialogue, .top-archives').forEach(section => {
		section.dataset.naturalBottom = section.offsetTop + section.offsetHeight;
	});
});

function cmnResizeHandler(){
	// 1. is-pinned / placeholder を解除して通常フローに戻す
	document.querySelectorAll('.top-dialogue, .top-archives').forEach(section => {
		if (section.classList.contains('is-pinned')) {
			const next = section.nextElementSibling;
			if (next && next.classList.contains('pin-placeholder')) {
				next.remove();
			}
			section.classList.remove('is-pinned');
		}
	});

	// 2. naturalBottom を再計算
	document.querySelectorAll('.top-dialogue, .top-archives').forEach(section => {
		section.dataset.naturalBottom = section.offsetTop + section.offsetHeight;
	});

	// 3. 現在のスクロール位置で状態を再適用
	cmnScrollHandler();
}


function cmnScrollHandler(){
	const scrpx = window.pageYOffset || document.documentElement.scrollTop;
	const innerH = window.innerHeight;

	// Introduction parallax
	const introBg = document.querySelector('.introduction__bg');
	const scr_rate = (cmn_mode === 'sp' && introBg)? 0 : 0.65;
	if (introBg) {
		introBg.style.setProperty('--parallax-y', (-scrpx * scr_rate) + 'px');
		introBg.style.setProperty('--parallax-opacity', (scrpx * 0.0015));
	}

	document.querySelectorAll('.top-dialogue').forEach(section => {
		const naturalBottom = parseFloat(section.dataset.naturalBottom);
		if (!naturalBottom) return;

		const relativeBottom = naturalBottom - scrpx;
		const shouldPin = relativeBottom <= innerH && relativeBottom > 0;

		if (shouldPin && !section.classList.contains('is-pinned')) {
			const placeholder = document.createElement('div');
			placeholder.className = 'pin-placeholder';
			placeholder.style.height = section.offsetHeight + 'px';
			section.after(placeholder);
			section.classList.add('is-pinned');
		} else if (!shouldPin && section.classList.contains('is-pinned')) {
			const next = section.nextElementSibling;
			if (next && next.classList.contains('pin-placeholder')) {
				next.remove();
			}
			section.classList.remove('is-pinned');
		}
	});
}
