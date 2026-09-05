var URL = location.protocol + "//" + location.hostname,
referrer = document.referrer;

// 修正: openingFlg(内部遷移か初回アクセスかの判定)はスクリプトのトップレベルで
// 同期的に確定させる。
// defer属性のスクリプトは全てDOMContentLoadedが発火する前に実行されるため、
// ここで同期的に確定させておけば、common.js側のDOMContentLoadedハンドラー
// (script.php内でこのtop.jsより先に読み込まれ、animsition('in')経由で
// cmnOpenHandler()を呼び出す)が、このファイル自身のDOMContentLoadedリスナーより
// 先に実行された場合でも、cmnOpenHandler()が参照する時点でopeningFlgは
// 既に正しい値になっている。
// (修正前はDOMContentLoadedリスナー内でopeningFlgを更新していたため、
// common.js側のハンドラーが先に実行される順序になるとopeningFlgがまだ
// 初期値のtrueのままcmnOpenHandler()に判定されてしまい、内部遷移時に
// opening()が一切呼ばれず、トップページの「#parallax」が表示されないままに
// なる不具合が発生していた)
let openingFlg = (-1 === referrer.indexOf(URL));

document.addEventListener('DOMContentLoaded', function () {
	var elm = document.getElementById('js-opening');
	if (elm) elm.style.display = 'none';

	if (openingFlg) {
		// 外部サイトからの初回アクセス: スプラッシュ画面を表示してからopening()を実行
		//
		// 修正: 「ブラウザで最初にトップページを開いた時だけファーストビューが
		// 表示されないままになる」不具合対策。
		// 従来はsetTimeoutを入れ子(ネスト)にしていたため、外側のコールバック内で
		// 何かひとつでも例外が発生する(あるいは想定外の理由で処理が止まる)と、
		// 内側のsetTimeoutが一度もスケジュールされず、opening()(.initを外す
		// 本体)が永久に呼ばれないままになるリスクがあった。初回アクセスは
		// キャッシュが空で読み込みが重く、この待機時間中に何らかの例外や
		// タブのバックグラウンド化によるタイマー遅延が起きやすく、症状と一致する。
		// また、この間openingFlgはtrueのままのため、common.js側のウォッチドッグ
		// (cmnOpenHandler経由)はガード条件により一切救済できなかった。
		//
		// 対策: 各ステップを最初から独立してスケジュールし(入れ子にしない)、
		// かつtry/catchで保護することで、途中の処理が失敗しても後続の処理
		// (最終的なopening()の呼び出し)が必ず実行されるようにする。
		try {
			document.body.classList.add('is-fix');
		} catch (e) {}
		if (elm) elm.style.display = '';

		setTimeout(function() {
			try {
				if (elm) elm.classList.add('is-end');
			} catch (e) {}
		}, 5000);

		setTimeout(function() {
			try {
				document.body.classList.remove('is-fix');
			} catch (e) {}
			openingFlg = false;
			opening(200);
		}, 5500);
	}
	// 内部遷移(サイト内からの再訪問)の場合はここでは何もしない。
	// opening()はcommon.js側から呼ばれるcmnOpenHandler()経由で実行される。
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
