let lenis;

const break_s = 960;
let cmn_mode = "pc";
let cmn_now_content = "";
let lastScrpx = 0;

// Safari bfcache対策：戻るボタンで復元された場合はリロード
window.addEventListener('pageshow', function (e) {
	if (e.persisted) window.location.reload();
});


// Cursor tip
// 変更点: window の load(画像/動画/フォント等の全リソース読込完了)を待たず、
// DOMContentLoaded 相当のこのタイミングで実行する(ファーストビュー描画後に
// カーソル演出・カバー解除・アコーディオン等の初期化を行うため)。
$(function () {
	const el = document.querySelector('.cursor_tip');
	const tip = el ? el.querySelector('p.tip') : null;
	const arrow = el ? el.querySelector('.hover_cursor') : null;

	document.addEventListener('mouseover', function (e) {
		const trigger = e.target.closest('.tip-trigger');
		if (trigger && tip) {
			tip.textContent = trigger.dataset.text || '';
			tip.classList.add('view');
		}
		const cursorTrigger = e.target.closest('.cursor-trigger');
		if (cursorTrigger && arrow) arrow.classList.add('view');
	});

	document.addEventListener('mouseout', function (e) {
		const trigger = e.target.closest('.tip-trigger');
		if (trigger && tip) {
			tip.textContent = '';
			tip.classList.remove('view');
		}
		const cursorTrigger = e.target.closest('.cursor-trigger');
		if (cursorTrigger && arrow) arrow.classList.remove('view');
	});
	if (!el) return;

	let mouseX = 0, mouseY = 0;
	let curX = 0, curY = 0;
	const ease = 0.12;

	document.addEventListener('mousemove', function (e) {
		mouseX = e.clientX;
		mouseY = e.clientY;
	});

	function loop() {
		curX += (mouseX - curX) * ease;
		curY += (mouseY - curY) * ease;
		el.style.transform = 'translate(' + (curX - el.offsetWidth / 2) + 'px, ' + (curY - el.offsetHeight / 2) + 'px)';
		requestAnimationFrame(loop);
	}
	loop();

	///////

	$('body').addClass( 'is-complete' );
	lastScrpx = $(window).scrollTop();

	// 現在のスクロール位置を取得
    var currentPos = $(window).scrollTop();
    
    // 100px以内であれば最上部(0)へ強制移動
    if (currentPos <= 100) {
        $(window).scrollTop(0);
    }

	//Accordion
	$('.accordion-trigger').click(function () {
		let t = $(this);
		let target = t.closest('.accordion-parent').find('.accordion-target');
		if (target.hasClass('isOpen')) {
			t.removeClass('active');
			target.removeClass('isOpen');
		} else {
			t.addClass('active');
			target.addClass('isOpen');
		}
		setTimeout(function () {
			scrollEventHandler();
		}, 400);
		return false;
	});

	//Scroll Event
	//-------------------------------------------
	$(window).on('scroll', function (e) {
		scrollEventHandler();
	});

	//Resize Event
	//-------------------------------------------
	$(window).on('resize', function () {
		resizeEventHandler();
		scrollEventHandler();
	});

	//modaal
	//-------------------------------------------
	$('.inline-modal').modaal({
		before_open: function() {
			if (typeof lenis !== 'undefined' && lenis) lenis.stop();
		},
		after_open: function() {
			$('.modaal-wrapper').attr('data-lenis-prevent', '');
		},
		after_close: function() {
			if (typeof lenis !== 'undefined' && lenis) lenis.start();
		}
	});

	//link
	//-------------------------------------------
	autoLinker('#articleContent');
});


$(function(){
	ua.checkAgent();

	// 動画の遅延読み込み(preload="none" + data-src で保留していた分の読込開始)。
	// 背景: <video><source src="..."> をHTMLに直接書くと、ブラウザのプリロード
	// スキャナがJS実行前・HTMLパース時点で動画バイトのダウンロードを開始してしまい、
	// フェードイン表示に必要なjQuery/Lenis/animsition/common.js等のスクリプト
	// ダウンロードと帯域を奪い合う。その結果、ページ読込全体は完了していても
	// 「ファーストビューの表示自体が遅く感じる」体感速度の悪化につながっていた。
	// 対策: 該当videoは preload="none" + <source data-src="..."> にHTML側を変更済み
	// (プリロードスキャナに拾わせない)。ここで最優先(他の初期化処理より前)に
	// data-src を実際の src へ設定し明示的に load() を呼ぶことで、フェードイン用
	// スクリプトの読込を妨げずに、できるだけ早いタイミングで動画取得を開始する。
	// poster 画像が設定されているため、動画取得中も見た目上の空白は発生しない。
	document.querySelectorAll('.js-lazy-video').forEach(function (video) {
		if (video.dataset.lazyLoaded) return;
		video.dataset.lazyLoaded = '1';
		video.querySelectorAll('source[data-src]').forEach(function (source) {
			source.src = source.dataset.src;
			delete source.dataset.src;
		});
		video.load();
		// autoplay属性により通常は自動再生されるが、環境によっては明示的な
		// play()呼び出しが必要な場合があるため保険として実行する
		// (再生不可のPromise拒否はUXに影響しないため握りつぶす)。
		var playPromise = video.play();
		if (playPromise && typeof playPromise.catch === 'function') {
			playPromise.catch(function () {});
		}
	});

	//Lenis
	//------------------------------------
	lenis = new Lenis({
		duration:1.5,
		smoothTouch: true
	});

	function raf(time) {
		lenis.raf(time);
		requestAnimationFrame(raf);
	}

	requestAnimationFrame(raf);

	txtSplit();

	/* smooth scroll
	================================================*/
	$('a[href^="#"].page-ancher').click(function(){
		// var speed = 1000;
		// if( window.matchMedia('(min-width: 1000px)').matches ) {
		// 	headerHeight = 0;
		// } else {
		// 	headerHeight = $header.outerHeight();
		// }
		var tag= $(this).attr("href");
		// var target = $(href == "#" || href == "" ? 'html' : href);
		// var position = target.offset().top - headerHeight;
		// $("html, body").animate({scrollTop:position}, speed, "swing");
		if (tag === '#') {
			tag = 0;
		}
		// スクロール
		if(lenis){
			lenis.scrollTo(tag);
		}else{
			var target = $(href == "#" || href == "" ? 'html' : href);
			var position = target.offset().top - headerHeight;
			$("html, body").animate({scrollTop:position}, speed, "swing");
		}
		return false;
	});

	/* animsition
	================================================*/
	// 変更点: onLoadEvent を false にし、animsition内部の
	// $(window).on('load', ...) による発火(画像/動画/フォント等の
	// 全リソース読込完了を待つ)を無効化。代わりにこの$(function(){...})
	// (DOMContentLoaded相当のタイミング=ファーストビュー描画後)で
	// 明示的に .animsition('in') を呼び、フェードインを開始する。
	// これにより「ページ全体を読み込まないとアニメーションが終わらない」
	// 問題を解消しつつ、既存の見た目(fade-in/fade-out-up-sm等)は変更しない。
	$(".animsition").animsition({
		inClass : 'fade-in', // ロード時のエフェクト
		outClass : 'fade-out-up-sm', // 離脱時のエフェクト
		inDuration : 1500, // ロード時の演出時間
		outDuration : 800, // 離脱時の演出時間
		linkElement : '.animsition-link', //アニメーションを行う要素
		// e.g. linkElement : 'a:not([target="_blank"]):not([href^=#])'
		loading : true, //ローディングの有効/無効
		loadingParentElement : 'body', //ローディング要素のラッパー
		loadingClass : 'animsition-loading', //ローディングのクラス
		unSupportCss : [ 'animation-duration',
						'-webkit-animation-duration',
						'-o-animation-duration'],
		overlay : false, //オーバーレイの有効/無効
		overlayClass : 'animsition-overlay-slide', //オーバーレイのクラス
		overlayParentElement : 'body', //オーバーレイ要素のラッパー
		onLoadEvent : false //window.loadでのin()自動発火を無効化(下で明示的に呼ぶ)
	})
		.one('animsition.inStart',function(){
			//console.log('event -> inStart');
	})
		.one('animsition.inEnd',function(){
			//console.log('event -> inEnd');
	});

	$('.animsition').on('animsition.outStart', function(){
		// console.log('outStart');
		$('.cover').addClass('is-end');
	});
	$('.animsition').on('animsition.outEnd', function(){
		// console.log('outEnd');
	});
	$('.animsition').on('animsition.inStart', function(){
		// console.log('inStart');
		if (typeof cmnOpenHandler == 'function') cmnOpenHandler();
		scrollEventHandler();
	});
	$('.animsition').on('animsition.inEnd', function(){
		// console.log('inEnd');
	});


	$('.fadein').on('inview', function(event, isInView){
		if (isInView) {
			$(this).addClass( 'active' );
		}
	});

	//hoverイベント
	//-------------------------------------------
	$('a').on('mouseenter touchstart', function () {
		$(this).addClass('hover');
	}).on('mouseleave touchend', function () {
		$(this).removeClass('hover');
	});

	//メディアクエリ
	//-------------------------------------------
	var mql = window.matchMedia('screen and (max-width: ' + break_s + 'px)');
	function checkMode(mql) {
		if (mql.matches) {
			cmn_mode = "sp";
			// SPの処理
			$('.rep_src').each(function () {
				$(this).attr("src", $(this).attr("src").replace('-pc', '-sp'));
			});

		} else {
			cmn_mode = "pc";
			// PCの処理
			$('.rep_src').each(function () {
				$(this).attr("src", $(this).attr("src").replace('-sp', '-pc'));
			});
		}

		if (typeof cmnBreakHandler == 'function') cmnBreakHandler(cmn_mode);
	}

	mql.addEventListener('change', checkMode);
	checkMode(mql);

	//「init」を追加
	$('.anim-trigger,.anim-trigger-fade').each(function (i, elm) {
		$(this).addClass('init');
	});

	// 修正: 「時間差フェードインが動いたり動かなくなったりする」不具合対策。
	// 背景: 上の「.init 付与」の直後、同一の同期処理内で animsition('in') を
	// 呼ぶと、inStart イベント経由で scrollEventHandler() が同期的に実行され、
	// 画面内に入っている .anim-trigger/.anim-trigger-fade(および txt_split の
	// h3 要素)から即座に .init が外される。ブラウザが一度も「.init が付いた
	// 状態」を描画(スタイル計算)しないまま、付与→除去が同一ティック内で
	// 連続すると、ブラウザ側で2つのスタイル変更が1回の計算にまとめられて
	// しまい、CSSトランジション(opacity/transformのフェードイン)が発火せずに
	// スキップされることがある。scrollEventHandler() 内には .fadein 要素の
	// offset().top 読み取りなど、たまたま強制リフローが発生する処理も混在する
	// ため、ページの要素構成やリソース読込タイミングによって「動く/動かない」が
	// ランダムに変化していた。
	// 対策: .init 付与の直後に document.body.offsetHeight を読み取り、強制的に
	// レイアウト/スタイル計算を確定させることで、.init が付与された状態を
	// ブラウザへ確実に一度認識させる。これにより、この後の .init 解除が
	// 「新しいスタイル変更」として扱われ、トランジションが毎回確実に発火する。
	void document.body.offsetHeight;

	// Member リンク：aboutページなら animsition を無効化してスクロール
	if ($('#aboutus__member').length) {
		$('.navigation-abuout a').removeClass('animsition-link').on('click', function (e) {
			e.preventDefault();
			if (lenis) lenis.scrollTo('#aboutus__member', { duration: 1.2 });
		});
	}

	// フェードイン開始(window.loadを待たずここで実行)。
	// 直前の「.anim-trigger/.anim-trigger-fade へ .init クラスを付与」する
	// 処理より後にこれを呼ぶ必要がある。animsition('in') は inStart イベント経由で
	// scrollEventHandler() を実行し、その中で「画面内に入っている .anim-trigger 要素の
	// .init を外す」判定を行うため、.init 付与がまだ完了していない状態で
	// scrollEventHandler() が走ると何も判定できず、結果としてユーザーが実際に
	// スクロール/リサイズしない限りファーストビューの要素が表示されないままになる
	// (「スクロールさせないとファーストビューの要素が表示されない」不具合の原因)。
	// さらに上記の強制リフローに加え、requestAnimationFrame で1フレーム後に
	// 実行することで、ブラウザの描画サイクルを跨がせ、トランジションの
	// 発火をより確実にする(二重の安全策)。
	requestAnimationFrame(function () {
		$(".animsition").animsition('in');
	});

	// ウォッチドッグ: ファーストビュー表示の保険処理。
	// 背景: 強制リフロー+rAFの対策を入れても、環境(回線速度/フォント読込/
	// 動画メタデータ取得等)によっては、まれに一度も .init 解除処理
	// (cmnOpenHandler経由のopening()、およびscrollEventHandler経由の
	// .anim-trigger判定)が正しいタイミングで走らず、要素が.init付き
	// (非表示)のまま固まってしまうケースが残り得る。
	// 原因を1つに断定するより先に、「表示されないまま固まる」ことを
	// 確実に防ぐため、cmnOpenHandler() / scrollEventHandler() を一定間隔で
	// 繰り返し呼び出すウォッチドッグを設置する。
	// 両関数とも「既に.initが外れている要素には何もしない」冪等な処理のため、
	// 繰り返し呼んでも表示中の要素に悪影響はない(anim-triggerは画面内に
	// 入っている場合のみinitを外す判定なので、画面外の要素を誤って
	// 表示してしまうこともない)。
	(function () {
		var watchdogCount = 0;
		var watchdogMax = 15; // 最大15回(約15秒間)繰り返す
		var watchdogTimer = setInterval(function () {
			watchdogCount++;
			if (typeof cmnOpenHandler === 'function') cmnOpenHandler();
			scrollEventHandler();

			// フェイルセーフ: 「ブラウザで最初にトップページを開いた時だけ
			// ファーストビューが表示されないまま」になる不具合対策。
			// TOPページの初回アクセス時は openingFlg が true のままになる
			// 期間があり、その間 cmnOpenHandler() 内の if(!openingFlg) ガードに
			// より opening()(.init解除本体)の呼び出しがスキップされるため、
			// 上のウォッチドッグ呼び出しだけでは救済できないケースがある。
			// (本来は top.js 側の setTimeout 処理で openingFlg が false に
			// 戻り opening() が呼ばれるが、何らかの理由でその処理が想定通りに
			// 走らなかった場合の保険として、一定回数経過後は openingFlg の
			// 状態に関わらず、残っている .init を強制的に解除する)
			if (watchdogCount >= 8) {
				document.querySelectorAll(
					'.introduction__copy-jp.init, .introduction__bg.init'
				).forEach(function (el) {
					el.classList.remove('init');
				});
			}

			if (watchdogCount >= watchdogMax) {
				clearInterval(watchdogTimer);
			}
		}, 1000);
	})();

});

//Scroll Event
//-------------------------------------------
function scrollEventHandler() {

	let scrpx = $(window).scrollTop();
	let innerH = $(window).innerHeight() ? $(window).innerHeight() : $(window).height();
	let margin = ua.isTouchDevice ? 10 : 100;
	let btmpx = scrpx + innerH;
	let centerpx = scrpx + innerH /2;

	if ( scrpx > 100 ) {
		$('body').addClass( 'is-scroll' );
	} else {
		$('body').removeClass( 'is-scroll' );
	}

	// Header show/hide on scroll direction
	if ( scrpx > lastScrpx && scrpx > 100 ) {
		$('body').addClass('is-hidden');
	} else {
		$('body').removeClass('is-hidden');
	}
	lastScrpx = scrpx;

	$('.fadein').each(function(){
		var targetElement = $(this).offset().top;
		var scroll = $(window).scrollTop();
		var windowHeight = $(window).height();
		if ( scroll > targetElement - windowHeight + 100 ) {
			$(this).css('opacity','1');
			$(this).css('transform','translateY(0)');
		}
	});

	//toTopBtn
	const $fx_btm = $('#bottom_fix');
	if (scrpx >= 50 && !$fx_btm.hasClass('typeB')) {
		$fx_btm.addClass('typeB');
		// $('.fix_parts').addClass('sp_hidden');
	} else if (scrpx < 50 && $fx_btm.hasClass('typeB')) {
		$fx_btm.removeClass('typeB');
		// $('.fix_parts').removeClass('sp_hidden');
	}

	const sections = document.querySelectorAll('.parallax_section');

	sections.forEach(section => {
		const bg = section.querySelector('.parallax_bg');
		const rect = section.getBoundingClientRect();
		const windowHeight = window.innerHeight;
	
		const bgHeight = bg.offsetHeight;
		const sectionHeight = section.offsetHeight;
	
		// 移動できる最大量（bgの方が大きい前提）
		const maxTranslate = Math.max(0, bgHeight - sectionHeight);
	
		// スクロール進行度（セクションが入ってから出るまで 0〜1）
		const start = windowHeight;
		const end = -sectionHeight;
		const progress = (rect.top - end) / (start - end);
		const clampedProgress = Math.max(0, Math.min(1, progress));
	
		// スクロールで少しずつ下に動かす（bottom: 0基準）
		const translateY = - clampedProgress * maxTranslate;
	
		bg.style.transform = `translateY(${translateY}px)`;
	  });

	//「init」を削除
	$('.anim-trigger,.anim-trigger-fade').each(function (i, elm) {
		let $t = $(this);
		const rect = elm.getBoundingClientRect();
		let timing = parseInt($t.attr('data-trigger-timing')) || 0;
		if (rect.top < innerH - (timing + margin) && $t.hasClass('init')) {
			$t.removeClass('init');
		} else if (rect.top >= innerH && !$t.hasClass('init')) {
			$t.addClass('init');
		}
	});

	if (typeof cmnScrollHandler == 'function') cmnScrollHandler();
}


//Resize Event
//-------------------------------------------
let resizeTimer;
function resizeEventHandler() {
	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(function () {
		if (lenis) lenis.reset();
		if (typeof cmnResizeHandler == 'function') cmnResizeHandler();
	}, 50);
}


function eventOnArchive() {
	var objList = [];
	var movieList = document.querySelectorAll('.archiveMovie');

	for (var i = 0; i < movieList.length; i++) {
		var element = movieList[i];
		element.dataset.index = i;

		// インスタンスを取得する
		var tempList = [];

		tempList[2] = element.querySelector('.video');
		objList[i] = tempList;

		// マウスオーバー時に再生する（タッチデバイスは除外）
		if (!ua.isTouchDevice) {
			element.addEventListener('mouseenter', function () {
				var video = objList[+this.dataset.index][2];
				if (!video) return;
				video.currentTime = video.initialTime || 0;
				video.play();
			});

			// マウスアウト時に再生を止める
			element.addEventListener('mouseleave', function () {
				var video = objList[+this.dataset.index][2];
				if (!video) return;
				video.pause();
				video.currentTime = 0;
			});
		}

		// 初回を再生する
		//objList[i].start();
	}
}
window.addEventListener('load', eventOnArchive);


// txt_split: .txt_split クラスの要素のテキストを1文字ずつ <span class="char"> に分割する
function txtSplit() {
	document.querySelectorAll('.txt_split').forEach(function(el) {
		const result = [];
		el.childNodes.forEach(function(node) {
			if (node.nodeType === Node.TEXT_NODE) {
				[...node.textContent].forEach(function(char) {
					result.push('<span>' + (char === ' ' ? '&nbsp;' : char) + '</span>');
				});
			} else if (node.nodeType === Node.ELEMENT_NODE) {
				result.push('<span>' + node.outerHTML + '</span>');
			}
		});
		el.innerHTML = result.join('');
	});
}


// Logomark spin + color cycle (JS-driven: 1s spin → 1s pause → repeat)
(function () {
	const logomark = document.querySelector('.logomark');
	if (!logomark) return;
	const img = logomark.querySelector('img');
	if (!img) return;

	const filters = [
		'brightness(0) saturate(100%)',                                                                                             // #000000
		'brightness(0) saturate(100%) invert(9%) sepia(99%) saturate(7481%) hue-rotate(241deg) brightness(106%) contrast(145%)',   // #0000ff
		'brightness(0) saturate(100%) invert(19%) sepia(97%) saturate(7481%) hue-rotate(296deg) brightness(109%) contrast(108%)',  // #ff00ff
		'brightness(0) saturate(100%) invert(13%) sepia(99%) saturate(7476%) hue-rotate(361deg) brightness(104%) contrast(113%)',  // #ff0000
		'brightness(0) saturate(100%) invert(46%) sepia(97%) saturate(1352%) hue-rotate(362deg) brightness(104%) contrast(104%)', // #ff7d00
		'brightness(0) saturate(100%) invert(93%) sepia(100%) saturate(500%) hue-rotate(370deg) brightness(102%)',                 // #ffff00
		'brightness(0) saturate(100%) invert(48%) sepia(98%) saturate(2763%) hue-rotate(457deg) brightness(120%) contrast(126%)', // #00ff00
	];

	let idx = -1;

	function changeColor() {
		idx = (idx + 1) % filters.length;
		img.style.filter = filters[idx];
	}

	function cycle() {
		let timing = 1000;
		// 回転開始
		logomark.classList.add('is-spinning');
		changeColor();

		// 回転終了 → 色変更 → 1s静止 → 次のサイクル
		setTimeout(function () {
			logomark.classList.remove('is-spinning');
			setTimeout(cycle, timing);
		}, 750);
	}

	cycle();
})();


$(function () {

	var scrollPos;

	/* toggle button & drawer
	================================================*/
	$('#js-toggle').on('click', function() {
		if( $('body').hasClass('is-open') ){
			$('body').removeClass('is-open').css('top',0 + 'px');
			window.scrollTo( 0 , scrollPos );
			if (lenis) lenis.start();
		}else{
			scrollPos = $(window).scrollTop();
			$('body').addClass('is-open').css('top',-scrollPos + 'px');
			if (lenis) lenis.stop();
		}
	});

	/* accordion
	================================================*/
	$(".accordion__toggle .member__button").on( 'click', function() {
		$(this).parent().next().slideToggle();
	});
});


//記事ページのURLをリンク設定
//----------------------------------
function autoLinker(selector) {
  const target = document.querySelector(selector);
  if (!target) return;

  // URLを抽出するための正規表現
  const urlRegex = /(https?:\/\/[^\s<]+)/g;

  // TreeWalkerを使って、要素内のすべてのテキストノードを抽出
  // (既に <a> タグの中にあるテキストは除外する)
  const walk = document.createTreeWalker(
    target,
    NodeFilter.SHOW_TEXT, {
      acceptNode: function(node) {
        // 親要素が <a> タグ、または <a> タグの子孫であればスキップ
        if (node.parentElement.closest('a')) {
          return NodeFilter.FILTER_REJECT;
        }
        return NodeFilter.FILTER_ACCEPT;
      }
    },
    false
  );

  const textNodes = [];
  let currentNode;

  // 走査しながらリストに格納（走査中にDOMを書き換えるとループが狂うため）
  while (currentNode = walk.nextNode()) {
    textNodes.push(currentNode);
  }

  // 各テキストノードに対して置換処理を実行
  textNodes.forEach(node => {
    const text = node.textContent;
    if (urlRegex.test(text)) {
      const span = document.createElement('span');
      // URLを <a> タグに置換して一時的な span に入れる
      span.innerHTML = text.replace(urlRegex, function(url) {
        return `<a href="${url}" target="_blank" rel="noopener noreferrer">${url}</a>`;
      });

      // 元のテキストノードを、リンク化した要素群で差し替える
      while (span.firstChild) {
        node.parentNode.insertBefore(span.firstChild, node);
      }
      node.parentNode.removeChild(node);
    }
  });
}





