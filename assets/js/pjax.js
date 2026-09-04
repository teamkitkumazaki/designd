/**
 * pjax.js — 表示速度改善(第三段階)
 *
 * これまでの「ページ切り替え時のフェード演出」は animsition.js が担っていましたが、
 * 実際には演出後に window.location.href で「ページ全体を読み込み直す」実装だったため、
 * 見た目はシームレスでも待ち時間は通常のページ遷移と変わりませんでした。
 *
 * このファイルは、
 *   - ヘッダー・フッターは再読込・再描画しない
 *   - #pjax-content の中身だけを fetch() で取得したHTMLに差し替える
 *   - 差し替え前後に既存の fade-out-up-sm / fade-in と同じCSSクラスを使って
 *     見た目の演出は維持する
 *   - <title> やOGP/meta、GTMの仮想ページビューも遷移後に更新する
 *   - ページ種別ごとに必要な専用JS（top.js 等）とそのconfigを
 *     data-page-script / data-page-config から読み取り、遷移後に読み込み直す
 * という「本当のPJAX」を実装します。
 *
 * 段階的移行のため、animsition.js 自体はこの後のPhaseで削除する前提として
 * 現時点ではまだ common.js から呼び出されていますが、
 * 「.animsition-link」のクリックは本ファイルが先にハンドリングし、
 * ページ内リンク（ハッシュのみ・外部リンク・target="_blank" 等）は
 * 通常のブラウザ挙動に委ねます。
 *
 * @package DESIGN:D ver2
 */
(function () {
	'use strict';

	// このブラウザが PJAX に必要な機能を持っていない場合は何もしない
	// (fetch / DOMParser / history.pushState が使えれば十分)
	if (!window.fetch || !window.DOMParser || !window.history || !window.history.pushState) {
		return;
	}

	var CONTENT_SELECTOR = '#pjax-content';
	var LINK_SELECTOR = '.animsition-link';
	var OUT_CLASS_DEFAULT = 'fade-out-up-sm';
	var IN_CLASS_DEFAULT = 'fade-in';
	var OUT_DURATION = 800;  // ms (animsition の outDuration と揃える)
	var IN_DURATION = 1500;  // ms (animsition の inDuration と揃える)

	var isTransitioning = false;

	// ------------------------------------------------------------
	// ページ専用JS（top.js / works-archive.js 等）の再読込・再実行管理
	// ------------------------------------------------------------
	// 現在ページに読み込まれている専用JSの識別子（''は「専用JSなし」）
	var currentPageScript = null;

	function getContentEl() {
		return document.querySelector(CONTENT_SELECTOR);
	}

	// 指定した専用JSファイルを <script> タグとして動的に追加する。
	// 同名のタグが既にあれば一旦削除してから追加することで再実行させる。
	function loadPageScript(scriptName, configVarName, config) {
		// 前のページ専用JS（works-archive.js 等）が window.addEventListener('popstate', ...)
		// のような「ページを離れても自動では消えないリスナー」を登録している場合に備え、
		// 各専用JSは離脱時のクリーンアップ処理を window.__pjaxPageCleanup に登録する規約とする。
		// 新しい専用JSを読み込む前に必ず呼び出し、リスナーの多重登録・誤発火を防ぐ。
		if (typeof window.__pjaxPageCleanup === 'function') {
			try {
				window.__pjaxPageCleanup();
			} catch (e) {
				console.error('[pjax] __pjaxPageCleanup failed:', e);
			}
		}
		window.__pjaxPageCleanup = null;

		// 専用のグローバルconfigが必要な場合は、JSファイルの読み込みより先にセットする
		if (configVarName) {
			window[configVarName] = config || null;
		}

		if (!scriptName) {
			currentPageScript = '';
			return;
		}

		var src = pjaxSettings.themeUri + '/assets/js/' + scriptName + '.js';

		// 既存の同じ専用JSタグを除去(再度追加して実行させるため)
		document.querySelectorAll('script[data-pjax-page-script]').forEach(function (el) {
			el.remove();
		});

		var script = document.createElement('script');
		script.src = src + '?t=' + Date.now(); // キャッシュを避けて確実に再実行させる
		script.setAttribute('data-pjax-page-script', scriptName);
		document.body.appendChild(script);

		currentPageScript = scriptName;
	}

	// ------------------------------------------------------------
	// head の <title> / OGP / meta description を更新
	// ------------------------------------------------------------
	// template-parts/head.php 側で、更新対象のタグに data-pjax-meta="キー名"
	// を付与している。ここではそのキーを手がかりに新旧のタグを1対1で対応付け、
	// タグの追加・削除の判定をハードコードされたセレクタ列に依存せず行う。
	function updateHeadMeta(newDoc) {
		if (newDoc.title) {
			document.title = newDoc.title;
		}

		var oldTags = document.querySelectorAll('[data-pjax-meta]');
		var newTags = newDoc.querySelectorAll('[data-pjax-meta]');

		var oldMap = {};
		oldTags.forEach(function (el) {
			oldMap[el.getAttribute('data-pjax-meta')] = el;
		});
		var newMap = {};
		newTags.forEach(function (el) {
			newMap[el.getAttribute('data-pjax-meta')] = el;
		});

		Object.keys(newMap).forEach(function (key) {
			var newTag = newMap[key];
			var oldTag = oldMap[key];
			if (oldTag) {
				if (newTag.tagName === 'TITLE') {
					oldTag.textContent = newTag.textContent;
				} else {
					oldTag.setAttribute('content', newTag.getAttribute('content') || '');
				}
			} else {
				document.head.appendChild(newTag.cloneNode(true));
			}
		});

		// 新しいページには存在しないタグ（例: noindexのrobotsタグ）は削除する
		Object.keys(oldMap).forEach(function (key) {
			if (!newMap[key] && oldMap[key].tagName !== 'TITLE') {
				oldMap[key].remove();
			}
		});
	}

	// ------------------------------------------------------------
	// GTM 仮想ページビュー通知（analytics.php 側のGTMコンテナに準拠）
	// ------------------------------------------------------------
	function pushVirtualPageview(url, title) {
		window.dataLayer = window.dataLayer || [];
		window.dataLayer.push({
			event: 'pjax_pageview',
			page_path: url,
			page_title: title
		});
	}

	// ------------------------------------------------------------
	// 各専用JSが持つ cmnOpenHandler / cmnResizeHandler / cmnScrollHandler を
	// 呼び出すためのラッパー（common.js 側の呼び出し規約と同じ）
	// ------------------------------------------------------------
	function callPageHandler(name) {
		if (typeof window[name] === 'function') {
			try {
				window[name]();
			} catch (e) {
				// ページ専用JS側のエラーでPJAX全体を壊さないようにログのみ出す
				console.error('[pjax] ' + name + '() failed:', e);
			}
		}
	}

	// ------------------------------------------------------------
	// メインの遷移処理
	// ------------------------------------------------------------
	function navigate(url, options) {
		options = options || {};
		var isPopstate = !!options.isPopstate;

		if (isTransitioning) return;
		isTransitioning = true;

		var contentEl = getContentEl();
		if (!contentEl) {
			// #pjax-content が無ければPJAXできないので通常遷移にフォールバック
			window.location.href = url;
			return;
		}

		var outClass = (options.outClass) || OUT_CLASS_DEFAULT;

		// --- 離脱アニメーション開始 -----------------------------------
		if (typeof window.lenis !== 'undefined' && window.lenis) {
			window.lenis.stop();
		}
		var cover = document.querySelector('.cover');
		if (cover) cover.classList.add('is-end');

		contentEl.style.animationDuration = (OUT_DURATION + 1) + 'ms';
		contentEl.classList.remove(IN_CLASS_DEFAULT);
		contentEl.classList.add(outClass);

		var fetchPromise = fetch(url, { credentials: 'same-origin' })
			.then(function (res) {
				if (!res.ok) throw new Error('PJAX fetch failed: ' + res.status);
				return res.text();
			});

		var animEndPromise = waitAnimationEnd(contentEl, OUT_DURATION);

		Promise.all([fetchPromise, animEndPromise])
			.then(function (results) {
				var html = results[0];
				applyNewContent(html, url, isPopstate, outClass);
			})
			.catch(function (err) {
				console.error('[pjax] navigation failed, falling back to full reload:', err);
				window.location.href = url;
			});
	}

	// animationend イベントを最大 timeout+300ms 待つ（対応していない環境でも進行できるように）
	function waitAnimationEnd(el, timeout) {
		return new Promise(function (resolve) {
			var done = false;
			var onEnd = function () {
				if (done) return;
				done = true;
				el.removeEventListener('animationend', onEnd);
				el.removeEventListener('webkitAnimationEnd', onEnd);
				resolve();
			};
			el.addEventListener('animationend', onEnd);
			el.addEventListener('webkitAnimationEnd', onEnd);
			setTimeout(onEnd, timeout + 300);
		});
	}

	function applyNewContent(html, url, isPopstate, outClass) {
		var parser = new DOMParser();
		var newDoc = parser.parseFromString(html, 'text/html');
		var newContent = newDoc.querySelector(CONTENT_SELECTOR);
		var contentEl = getContentEl();

		if (!newContent || !contentEl) {
			window.location.href = url;
			return;
		}

		// --- #pjax-content の中身を入れ替え -----------------------------
		contentEl.innerHTML = newContent.innerHTML;
		contentEl.classList.remove(outClass);

		// data-page-* 属性も新しいページのものに更新
		contentEl.setAttribute('data-page-script', newContent.getAttribute('data-page-script') || '');
		contentEl.setAttribute('data-page-config-var', newContent.getAttribute('data-page-config-var') || '');
		contentEl.setAttribute('data-page-config', newContent.getAttribute('data-page-config') || '');

		// body class（is-home 等のページ判定に使われるクラス）も同期
		if (newDoc.body) {
			document.body.className = newDoc.body.className;
		}

		// head（title / OGP / description 等）を更新
		updateHeadMeta(newDoc);

		// 履歴を更新（popstateで呼ばれた場合はpushStateしない）
		if (!isPopstate) {
			window.history.pushState({ pjax: true, url: url }, '', url);
		}

		// GTM 仮想ページビュー通知
		pushVirtualPageview(url, document.title);

		// 「init」クラスなど、common.js のアニメーション判定に使うクラスを再付与
		contentEl.querySelectorAll('.anim-trigger, .anim-trigger-fade').forEach(function (el) {
			el.classList.add('init');
		});

		// スクロール位置をトップへ（popstateで戻る場合はブラウザ標準の復元に任せる）
		if (!isPopstate) {
			window.scrollTo(0, 0);
		}

		// --- 進入アニメーション ---------------------------------------
		contentEl.style.animationDuration = IN_DURATION + 'ms';
		contentEl.classList.add(IN_CLASS_DEFAULT);

		waitAnimationEnd(contentEl, IN_DURATION).then(function () {
			contentEl.classList.remove(IN_CLASS_DEFAULT);
			isTransitioning = false;
		});

		// --- ページ専用JSの再読込・再実行 -------------------------------
		var scriptName = contentEl.getAttribute('data-page-script') || '';
		var configVar = contentEl.getAttribute('data-page-config-var') || '';
		var configRaw = contentEl.getAttribute('data-page-config') || '';
		var config = null;
		if (configRaw) {
			try {
				config = JSON.parse(configRaw);
			} catch (e) {
				config = null;
			}
		}
		loadPageScript(scriptName, configVar, config);

		// --- 共通JS側のフックを再実行 -----------------------------------
		// (top.js等の cmnOpenHandler/cmnResizeHandler/cmnScrollHandler は
		//  上記 loadPageScript() による <script> 再読込が完了した後に
		//  改めてグローバル関数として存在するようになるため、
		//  マイクロタスク後まで待って呼び出す)
		setTimeout(function () {
			if (typeof window.scrollEventHandler === 'function') window.scrollEventHandler();
			if (typeof window.eventOnArchive === 'function') window.eventOnArchive();
			callPageHandler('cmnOpenHandler');
			callPageHandler('cmnResizeHandler');
		}, 0);

		if (typeof window.lenis !== 'undefined' && window.lenis) {
			window.lenis.start();
			window.lenis.scrollTo(0, { immediate: true });
		}

		if (cover_el()) cover_el().classList.remove('is-end');
	}

	function cover_el() {
		return document.querySelector('.cover');
	}

	// ------------------------------------------------------------
	// クリックイベントのハンドリング
	// ------------------------------------------------------------
	document.addEventListener('click', function (event) {
		var link = event.target.closest(LINK_SELECTOR);
		if (!link) return;

		var href = link.getAttribute('href');
		if (!href) return;

		// 新規タブ・修飾キー・外部リンク・ハッシュのみのリンクは通常挙動に委ねる
		if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
		if (link.target === '_blank') return;
		if (href.charAt(0) === '#') return;

		var url;
		try {
			url = new URL(href, window.location.href);
		} catch (e) {
			return;
		}

		// 別オリジンへのリンクは通常遷移
		if (url.origin !== window.location.origin) return;

		// 同一ページ内アンカー（パスが同じでhashだけ違う）は通常のスクロール処理に任せる
		if (url.pathname === window.location.pathname && url.hash) return;

		event.preventDefault();

		var outClass = link.getAttribute('data-animsition-out-class') || OUT_CLASS_DEFAULT;
		navigate(url.href, { outClass: outClass });
	});

	// ------------------------------------------------------------
	// ブラウザの戻る/進むボタン対応
	// ------------------------------------------------------------
	window.addEventListener('popstate', function (event) {
		var state = event.state;
		// PJAXによる遷移で積んだ履歴のみ処理する。
		// (works-archive.js / dlog-archive.js が自前で積む pushState/popstate は
		//  それぞれ state.pjax を持たないフィルター専用のものなので、
		//  ここでは何もせずそれぞれのスクリプトのpopstateハンドラに処理を委ねる)
		if (!state || !state.pjax) return;

		navigate(window.location.href, { isPopstate: true });
	});

	// ------------------------------------------------------------
	// 初期化：現在のページの data-page-script を currentPageScript に記録
	// ------------------------------------------------------------
	function init() {
		var contentEl = getContentEl();
		if (contentEl) {
			currentPageScript = contentEl.getAttribute('data-page-script') || '';
		}
		// 初回ロード時の履歴エントリにも pjax フラグを付けておく
		// (popstateで戻ってきた際にPJAX側の処理対象と判定できるようにするため)
		window.history.replaceState(
			Object.assign({}, window.history.state, { pjax: true, url: window.location.href }),
			'',
			window.location.href
		);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
