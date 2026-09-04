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
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">

	<!-- METAS -->
	<meta name="keywords" content="">
	<meta name="description" content="<?php bloginfo('description'); ?>">

	<!-- FAVICON -->
	<link href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/favicon.ico" rel="shortcut icon">
	<meta property="og:image" content="https://designd.jp/wp-content/themes/designd/assets/images/ogp.png">
	<!-- CSS -->
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/modaal/css/modaal.min.css">
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>?date=20240801">
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

		<h1 class="header__brand"><a href="<?php echo esc_url( home_url() ); ?>/" class="animsition-link" data-animsition-out-class="fade-out-up-sm"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo.svg" alt="<?php bloginfo( 'name' ); ?>"></a></h1>
		
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

	
