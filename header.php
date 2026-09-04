<?php
/**
 * ログインせずに WordPress のページにアクセスするとログイン画面にリダイレクトされます。
 * 公開時に削除するかあるいはコメントアウトしてください。
 */
	if (!is_user_logged_in()) {
		//auth_redirect();
	}
/**
 * head 要素とページのヘッダー部分を表示するテンプレートです。
 *
 * @package DESIGN:D
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<!-- moved -->
<head>
	<?php
		/* カスタムテンプレート「analytics.php」をインクルードします。 */
		get_template_part( 'template-parts/head' );
	?>

		<!-- FAVICON -->
		<link href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/favicon.ico" rel="shortcut icon">
		<meta property="og:image" content="https://designd.jp/wp-content/themes/designd/assets/images/ogp.png">
		<!-- PRECONNECT: 外部ドメインへの接続を事前に確立し、初回リクエストの待ち時間を短縮 -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link rel="preconnect" href="https://www.googletagmanager.com">
		<!-- PRELOAD: 本文で広く使用する日本語サブセットフォントを優先的に読み込み、文字のちらつき(FOUT)を軽減 -->
		<link rel="preload" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/font/ipaexg.woff2" as="font" type="font/woff2" crossorigin>
		<!-- CSS -->
		<!-- 表示速度改善(第二段階): Google Fonts / animsition.min.css / modaal.min.css /
		     structur.css / main.css / 各種@font-faceに分かれていた読み込みを
		     assets/css/app.min.css の1本に統合し、リクエスト数を削減。
		     style.css は WordPress テーマ情報(Theme Name等)保持用として
		     残しているのみで、フロント表示には読み込んでいない。 -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap">
		<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/css/app.min.css?date=20260904">
<?php wp_head(); ?>

<?php
	/* カスタムテンプレート「analytics.php」をインクルードします。 */
	get_template_part( 'template-parts/analytics' );
?>
</head>
<body <?php body_class(); ?>>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PD446QF"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<header class="header" role="banner">
	<div class="header__container">

		<h1 class="header__brand"><a href="<?php echo esc_url( home_url() ); ?>/" class="animsition-link" data-animsition-out-class="fade-out-up-sm"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo.svg" alt="<?php bloginfo( 'name' ); ?>" decoding="async"></a></h1>

		<?php
		/* カスタムテンプレート「navigation.php」をインクルードします。 */
		get_template_part( 'template-parts/navigation' );
		?>

		<div id="js-toggle" class="header__toggle">
			<div class="header__balls">
				<span class="ball ball1"></span>
				<span class="ball ball2"></span>
			</div>
		</div>

	</div>
</header>
<div class="animsition">
