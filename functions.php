<?php
/**
 * 汎用関数、初期設定のテンプレートです。
 *
 * @package DESIGN:D
 */

/* -----------------------------------------------
 * アセットファイルの自動キャッシュバスティング
 * -------------------------------------------- */
// テーマ内のCSS/JSファイルURLに、ファイルの最終更新日時を
// クエリパラメータ(?ver=xxxxxxxxxx)として自動付与するヘルパー関数。
//
// 【背景】静的アセット(assets/js/*.js 等)はサーバー側で
// 「cache-control: max-age=604800」(7日間)というキャッシュ設定が
// 付与されており、URLにバージョン情報が全く無い状態で配信されていた。
// このため、サーバー上のファイル自体は最新に更新されていても、
// 一度ブラウザにキャッシュされたユーザーには最大7日間、
// 古いJS/CSSが配信され続けてしまい、「表示される場合とされない場合が
// ある」「一度表示されたら以降は毎回表示される(≒新しいキャッシュが
// 効いたユーザーはそのまま新しいまま)」という不具合の原因となっていた。
//
// $file: get_template_directory() からの相対パス (例: '/assets/js/top.js')
// 戻り値: get_template_directory_uri() . $file . '?ver=' . filemtime
//         (ファイルが存在しない場合はクエリなしのURLを返す)
function designd_asset_url( $file ) {
	$relative_path = ltrim( $file, '/' );
	$abs_path       = get_template_directory() . '/' . $relative_path;
	$uri            = get_template_directory_uri() . '/' . $relative_path;

	if ( file_exists( $abs_path ) ) {
		$ver = filemtime( $abs_path );
		return esc_url( $uri . '?ver=' . $ver );
	}

	return esc_url( $uri );
}


/* -----------------------------------------------
 * title 要素の出力
 * -------------------------------------------- */
function setup_theme() {
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'setup_theme' );


/* -----------------------------------------------
 * title 要素のセパレータを変更
 * -------------------------------------------- */
function custom_title_separator($sep) {
	$sep = '|';
	return $sep;
}
add_filter( 'document_title_separator', 'custom_title_separator' );


/* -----------------------------------------------
 * 外観 > メニュー カスタムメニューを有効化
 * -------------------------------------------- */
add_theme_support( 'menus' );


/* -----------------------------------------------
 * アイキャッチ画像を有効化
 * -------------------------------------------- */
add_theme_support( 'post-thumbnails' );


/* -----------------------------------------------
 * ログイン時のツールバーの非表示
 * -------------------------------------------- */
add_filter('show_admin_bar', '__return_false');


/* -----------------------------------------------
 * 検索フォームの出力
 * -------------------------------------------- */
function custom_search_form( $form ) {
	$form = '<form role="search" method="get" id="searchform" class="st-form" action="' . esc_url( home_url( '/' ) ) . '" >
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="Search ..." />
	<input type="submit" id="searchsubmit" value="'. esc_attr__( 'Search' ) .'" class="st-button" />
	</form>';

	return $form;
}
add_filter( 'get_search_form', 'custom_search_form' );


/* -----------------------------------------------
 * 外観 > ウィジェット 表示
 * ウィジェットエリアを表示したい箇所に下記コードを記述
 * <?php dynamic_sidebar( 'sidebar' ); ?>
 * -------------------------------------------- */
function custom_widgets_init() {
	register_sidebar( array(
		'name' => 'サイドバー',
		'id' => 'sidebar',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'custom_widgets_init' );


/* -----------------------------------------------
 * ログイン画面をカスタマイズ
 * -------------------------------------------- */
function custom_login_logo() { ?>
	<style>
		.login {
			background-color: #fff;
		}
		.login #login h1 a {
			width: 180px;
			height: 30px;
			background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.svg);
			background-repeat: no-repeat;
			background-position: center center;
			background-size: contain;
		}
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'custom_login_logo' );


/* -----------------------------------------------
 * 概要（抜粋）の文字数変更
 * -------------------------------------------- */
function change_excerpt_mblength( $length ) {
return 50;
}
add_filter('excerpt_length', 'change_excerpt_mblength', 999);


/* -----------------------------------------------
 * 概要（抜粋）の省略文字
 * -------------------------------------------- */
function my_excerpt_more( $more ) {
	return '…';
}
add_filter('excerpt_more', 'my_excerpt_more');


/* -----------------------------------------------
 * ショートコード
 * -------------------------------------------- */

function shortcode_ignore($atts, $content = null) {
	return null;
}
add_shortcode( 'ignore', 'shortcode_ignore' );

function shortcode_theme_dir($params = array()) {
	return get_template_directory_uri();
}
add_shortcode( 'theme_dir', 'shortcode_theme_dir' );



/* -----------------------------------------------
 * テンプレートディレクトリへのショートコード
 * -------------------------------------------- */
function shortcode_templateurl() {
	return get_template_directory_uri();
}
add_shortcode('template_url', 'shortcode_templateurl');

// 呼び出し例 [template_url]


/* -----------------------------------------------
 * ホーム URL へのショートコード
 * -------------------------------------------- */
function shortcode_homeurl() {
	return home_url();
}
add_shortcode('home_url', 'shortcode_homeurl');

// 呼び出し例 [home_url]


/* -----------------------------------------------
 * phpファイルの呼び出しショートコード
 * -------------------------------------------- */
function shortcode_file_include($params = array()) {
	extract(shortcode_atts(array(
		'file' => 'default',
		'data' => 'default'
	), $params));
	ob_start();
	include(STYLESHEETPATH . "/$file.php");
	return ob_get_clean();
}
add_shortcode('file_include', 'shortcode_file_include');

// ファイルを呼び出し例
//[file_include file='呼び出すファイル名' data="呼び出すファイルに渡す引数"]


/* -----------------------------------------------
 * NEW マーク
 * -------------------------------------------- */
function newmark() {
	// $days は「NEW」マークの表示（「new」クラスを追加）する期間を設定します。
	$days  = 7;
	$today = date_i18n('U');
	$entry = get_the_time('U');
	$total = date('U',($today - $entry)) / ( 60 * 60 * 24 );
	if ( $days > $total ){
		echo 'new';
	} else {
		echo 'old';
	}
}


/* -----------------------------------------------
 * 最上位の親ページの情報を取得
 * -------------------------------------------- */
function custom_get_ancestors() {
	if ( is_page() ) {
		// 現在ページ（自身）の ID 取得
		$id = $post->ID;
		// get_post_ancestors() 関数で、引数に投稿 ID または投稿オブジェクトを指定すると、指定した投稿の親ページの ID を配列で取得できます。
		$parents = get_post_ancestors( $id );
		// 最上位の親ページの ID 取得
		$top_parent_id = $parents[sizeOf( $parents )-1];
		// 直上の親ページの ID 取得
		//$parent_id = $parents[0];

		$top_parent = [
			'slug'  => get_post( $top_parent_id )->post_name,	// 親スラッグ
			'title' => get_post( $top_parent_id )->post_title,	// 親タイトル
			'url'   => get_permalink( $top_parent_id ),			// 親URL
		];

		return $top_parent;
	}
}


/* -----------------------------------------------
 * アーカイブページの接頭辞を削除する
 * -------------------------------------------- */
function custom_archive_title($title) {
	if ( is_category() ) {
		/* translators: Category archive title. 1: Category name */
		$title = sprintf( __( '%s' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		/* translators: Tag archive title. 1: Tag name */
		$title = sprintf( __( '%s' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		/* translators: Author archive title. 1: Author name */
		$title = sprintf( __( '%s' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		/* translators: Yearly archive title. 1: Year */
		$title = sprintf( __( '%s' ), get_the_date( _x( 'Y', 'yearly archives date format' ) ) );
	} elseif ( is_month() ) {
		/* translators: Monthly archive title. 1: Month name and year */
		$title = sprintf( __( '%s' ), get_the_date( _x( 'F Y', 'monthly archives date format' ) ) );
	} elseif ( is_day() ) {
		/* translators: Daily archive title. 1: Date */
		$title = sprintf( __( '%s' ), get_the_date( _x( 'F j, Y', 'daily archives date format' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title' );
		}
	} elseif ( is_post_type_archive() ) {
		/* translators: Post type archive title. 1: Post type name */
		$title = sprintf( __( '%s' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%2$s' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives' );
	}
	$title = $title . '';
	return $title;
};
add_filter( 'get_the_archive_title', 'custom_archive_title');





/* -----------------------------------------------
 * 管理画面メニューのカスタマイズ
 ---------------------------------------------- */
function remove_menus () {
	// 管理者ではない場合
	if (!current_user_can('administrator')) {
		//remove_menu_page( 'index.php' );							// ダッシュボード
		remove_menu_page( 'edit.php' );								// 投稿
		//remove_menu_page( 'upload.php' );							// メディア
		remove_menu_page( 'edit.php?post_type=page' );				// 固定ページ
		remove_menu_page( 'edit-comments.php' );					// コメント
		remove_menu_page( 'themes.php' );							// 外観
		remove_menu_page( 'plugins.php' );							// プラグイン
		remove_menu_page( 'users.php' );							// ユーザー
		remove_menu_page( 'tools.php' );							// ツール
		remove_menu_page( 'options-general.php' );					// 設定
		remove_menu_page( 'edit.php?post_type=mw-wp-form' );		// MW WP Form
		remove_menu_page( 'edit.php?post_type=acf-field-group' );	// カスタムフィールド
		remove_menu_page( 'cptui_main_menu' );						// CPT UI
	}
}
add_action('admin_menu', 'remove_menus');



/* -----------------------------------------------
 * 固定ページのスラッグをbodyのclassに追加
 ---------------------------------------------- */
function add_page_slug_class_name( $classes ) {
	if ( is_page() ) {
		$page = get_post( get_the_ID() );
		$classes[] = $page->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_page_slug_class_name' );



/* -----------------------------------------------
 * 無限スクロール用ページネーション
 ---------------------------------------------- */
function create_pagination() {
	global $wp_query;
	$bignum = 999999999;
	if ( $wp_query->max_num_pages <= 1 )
		return;
	echo '<nav id="ajax_nav" class="pagination">';
	echo paginate_links( array(
		'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
		'format'       => '',
		'current'      => max( 1, get_query_var('paged') ),
		'total'        => $wp_query->max_num_pages,
		'prev_text'    => '&larr;',
		'next_text'    => '&rarr;',
		'type'         => 'list',
		'end_size'     => 3,
		'mid_size'     => 3
	) );
	echo '</nav>';
}
/* -----------------------------------------------
 * アップロード画像の大サイズ生成しきい値
 * ※以前は無効化(元画像そのまま)にしていたが、
 *   表示速度改善のためWordPress既定値(2560px)に戻す
 ---------------------------------------------- */
add_filter( 'big_image_size_threshold', function( $threshold ) {
	return 2560;
} );



/* -----------------------------------------------
 * アップロード画像の圧縮率を変更
 * ※100(無圧縮)だとファイルサイズが肥大化するため、
 *   画質劣化がほぼ視認できない82に戻す
 ---------------------------------------------- */
add_filter( 'jpeg_quality', function( $arg ){ return 82; } );



/* -----------------------------------------------
 * 「投稿者アーカイブ」のアクセスを拒否（非表示）する
 ---------------------------------------------- */
add_filter( 'author_rewrite_rules', '__return_empty_array' );
function disable_author_archive() {
	if( isset($_GET['author']) || preg_match('#/author/.+#', $_SERVER['REQUEST_URI']) ){
		wp_redirect( home_url() );
		exit;
	}
}
add_action('init', 'disable_author_archive');


//カスタム投稿タイプの追加
add_action( 'init', 'create_post_type' );
function create_post_type() {
  $customPostSupports = [  // supports のパラメータを設定する配列（初期値だと title と editor のみ投稿画面で使える）
    'title',  // 記事タイトル,
    'editor',  // 記事本文
    'custom-fields' ,//カスタムフィールド
    'thumbnail',  // アイキャッチ画像*/
  ];

	//カスタム投稿タイプ１（ここから）
	register_post_type(
    'dlog', // カスタム投稿名
    	array(
				'labels' => array(
					'name'          => __( 'D:Log' ),
					'singular_name' => __( 'dlog' ),
				),
        'public'        => true,
        'menu_position' => 5,
        'has_archive'   => true, // アーカイブ有効
        'show_in_rest'  => true,
        //D:Logにエディターが表示されていなかったので修正 @VONS 2026.04
		//'supports'      => array( 'title', 'thumbnail' ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields' ), //
      	// rewrite設定を正しい場所にまとめます
      	'rewrite'       => array(
          'slug'       => 'dlog',
          'with_front' => false,
      	),
	)
	);

	register_post_type(
		'works', // カスタム投稿名
			array(
				'labels' => array(
					'name'          => __( 'Works' ),
					'singular_name' => __( 'works' ),
				),
				'public'        => true,
				'menu_position' => 6,
				'has_archive'   => true, // アーカイブ有効
				'show_in_rest'  => true,
				'supports'      => array( 'title', 'editor', 'thumbnail' ),
				// rewrite設定を正しい場所にまとめます
				'rewrite'       => array(
					'slug'       => 'works',
					'with_front' => false,
				),
			)
		);


	register_post_type(
		'interview', // カスタム投稿名
			array(
				'labels' => array(
					'name'          => __( 'Dialogue' ),
					'singular_name' => __( 'interview' ),
				),
				'public'        => true,
				'menu_position' => 7,
				'has_archive'   => true, // アーカイブ有効
				'show_in_rest'  => true,
				'supports'      => array( 'title', 'thumbnail' ),
				// rewrite設定を正しい場所にまとめます
				'rewrite'       => array(
					'slug'       => 'dialogue',//@VONS interview → dialogue
					'with_front' => false,
				),
			)
		);

	register_post_type(
		'member',  // カスタム投稿名
		array(
			'labels' => array(
				'name' => __( 'Member' ), // 管理画面の左メニューに表示されるテキスト
				'singular_name' => __( 'member' ),
				'rewrite' => array(
    			'slug' => 'member-post',
    			'with_front' => false
				),
			),
			'public' => true,  // 投稿タイプをパブリックにするか否か
			'menu_position' => 8,  // 管理画面上でどこに配置するか ※「5」で「投稿」の下に配置
			'has_archive' => false,  // アーカイブを有効にするか否か
			'show_in_rest' => true,
			'supports' => array(
				'title',
				'thumbnail'
			)
		)
	);

	register_taxonomy(
    'works-category',
    'works',
    array(
      'hierarchical' => true,
      'label' => '職種領域',
      'show_in_rest' => true,
      'public' => true,
      'show_ui' => true,
      // rewrite を配列で詳細に設定
      'rewrite' => array(
          'slug' => 'works-category', // URLの一部になります
          'with_front' => false
      ),
    )
  );

	register_taxonomy(
		'client-category',
		'works',
		array(
			'hierarchical' => true,
			'label' => '業界',
			'show_in_rest' => true,
			'public' => true,
			'show_ui' => true,
			// rewrite を配列で詳細に設定
			'rewrite' => array(
					'slug' => 'client-category', // URLの一部になります
					'with_front' => false
			),
		)
	);

}

function add_custom_post_type_archive_rewrites($rules) {
    $new_rules = array(
        'news/([0-9]{4})/?$' => 'index.php?post_type=news&year=$matches[1]',
    );
    return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'add_custom_post_type_archive_rewrites');


/* -----------------------------------------------
 * Works フィルター AJAX ハンドラ(@VONS)
 * -------------------------------------------- */
function works_filter_ajax() {
	$taxonomy = isset($_POST['taxonomy']) ? sanitize_text_field($_POST['taxonomy']) : '';
	$term     = isset($_POST['term'])     ? sanitize_text_field($_POST['term'])     : '';
	$paged    = isset($_POST['paged'])    ? max(1, intval($_POST['paged']))         : 1;

	$args = array(
		'post_type'      => 'works',
		'posts_per_page' => 12,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
		'paged'          => $paged,
	);

	if ($taxonomy && $term) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term,
			),
		);
	}

	$the_query = new WP_Query($args);
	$html      = '';

	if ($the_query->have_posts()) {
		while ($the_query->have_posts()) {
			$the_query->the_post();
			ob_start();
			get_template_part('template-parts/ajax-works');
			$html .= ob_get_clean();
		}
	}

	wp_reset_postdata();

	$next_count = min(12, max(0, $the_query->found_posts - $paged * 12));

	wp_send_json(array(
		'html'       => $html,
		'has_more'   => $the_query->max_num_pages > $paged,
		'next_count' => $next_count,
	));
}
add_action('wp_ajax_works_filter',        'works_filter_ajax');
add_action('wp_ajax_nopriv_works_filter', 'works_filter_ajax');


/* -----------------------------------------------
 * Dialogue フィルター AJAX ハンドラ
 * -------------------------------------------- */
function dialogue_filter_ajax() {
	$paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;

	$args = array(
		'post_type'      => 'interview',
		'posts_per_page' => 12,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
		'paged'          => $paged,
	);

	$the_query = new WP_Query($args);
	$html      = '';

	if ($the_query->have_posts()) {
		while ($the_query->have_posts()) {
			$the_query->the_post();
			ob_start();
			get_template_part('template-parts/ajax-dialogue');
			$html .= ob_get_clean();
		}
	}

	wp_reset_postdata();

	$next_count = min(12, max(0, $the_query->found_posts - $paged * 12));

	wp_send_json(array(
		'html'       => $html,
		'has_more'   => $the_query->max_num_pages > $paged,
		'next_count' => $next_count,
	));
}
add_action('wp_ajax_dialogue_filter',        'dialogue_filter_ajax');
add_action('wp_ajax_nopriv_dialogue_filter', 'dialogue_filter_ajax');


/* -----------------------------------------------
 * D:Log 年別フィルター AJAX ハンドラ
 * -------------------------------------------- */
function dlog_filter_ajax() {
	$year  = isset($_POST['dlog_year'])  ? intval($_POST['dlog_year'])  : 0;
	$paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;

	$args = array(
		'post_type'      => 'dlog',
		'posts_per_page' => 12,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
		'paged'          => $paged,
	);

	if ($year) {
		$args['year'] = $year;
	}

	$the_query = new WP_Query($args);
	$html      = '';

	if ($the_query->have_posts()) {
		while ($the_query->have_posts()) {
			$the_query->the_post();
			ob_start();
			get_template_part('template-parts/ajax-dlog');
			$html .= ob_get_clean();
		}
	}

	wp_reset_postdata();

	$next_count = min(12, max(0, $the_query->found_posts - $paged * 12));

	wp_send_json(array(
		'html'       => $html,
		'has_more'   => $the_query->max_num_pages > $paged,
		'next_count' => $next_count,
	));
}
add_action('wp_ajax_dlog_filter',        'dlog_filter_ajax');
add_action('wp_ajax_nopriv_dlog_filter', 'dlog_filter_ajax');

function replace_content_urls($content) {
    $search  = 'https://designd.jp/cms/wp-content/';
    $replace = 'https://designd.jp/wp-content/';

    // 投稿本文の内容を置換
    $content = str_replace($search, $replace, $content);
    return $content;
}
add_filter('the_content', 'replace_content_urls');

add_filter( 'ppp_nonce_life', 'my_nonce_life' );
function my_nonce_life() {
    return 60 * 60 * 24 * 14; // 2 weeks
}


/* -----------------------------------------------
 * Speculative Loading (speculationrules) の見直し
 * WordPress 6.8 で標準搭載された先読み機能。
 * 既定では「同一オリジンの全リンク」がprefetch対象になっているが、
 * ・AJAXでフィルタリングされるアーカイブページ(works/dialogue/dlog)
 * ・お問い合わせページ(フォーム誤動作防止のため)
 * ・WordPress標準の除外対象(wp-admin等)以外の管理系
 * は不要な先読みでサーバー負荷やモバイル通信量が増える可能性があるため、
 * 対象パスから除外する。
 * -------------------------------------------- */
add_filter( 'wp_speculation_rules_href_exclude_paths', function( $href_exclude_paths ) {
	$href_exclude_paths[] = '/contact/*';
	$href_exclude_paths[] = '/works/*';
	$href_exclude_paths[] = '/dialogue/*';
	$href_exclude_paths[] = '/dlog/*';
	return $href_exclude_paths;
} );

/* -----------------------------------------------
 * Speculative Loading の積極度(eagerness)を明示的に指定
 * 既定は「conservative」(リンクをクリックしようとした瞬間に先読み)のため、
 * 誤って先読みが多発するリスクは低い。ここでは既定のconservativeを
 * 明示指定しつつ、モードはprefetch(取得のみ・描画はしない)に固定して
 * 予期せぬ画面のちらつき等を避ける。
 * -------------------------------------------- */
add_filter( 'wp_speculation_rules_configuration', function( $config ) {
	if ( is_array( $config ) ) {
		$config['mode']      = 'prefetch';
		$config['eagerness'] = 'conservative';
	}
	return $config;
} );

?>
