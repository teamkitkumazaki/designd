<?php
/**
 * スクリプトファイルを読み込むテンプレートです。
 *
 * @package DESIGN:D
 */
?>

	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/jquery/jquery-3.7.1.min.js"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/lenis/lenis.min.js"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/animsition/js/animsition.js"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/jquery.inview/jquery.inview.min.js"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/vns/vns.checkAgent.js"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/shuffle-text/shuffle-text.js"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/common.js?v=5"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/modaal/js/modaal.min.js"></script>
<?php
	// トップページにのみ読み込むスクリプトファイル設定します。
	if( is_home() || is_front_page() ) {
?>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/top.js"></script>
<?php
	}
?>

<?php
	// 固定ページ「About」のみ
	if( is_page( 'about' ) ) {
?>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/about.js"></script>
<?php
	}
?>
<?php
	// 「Dialogue」詳細ページのみ
	if( is_singular( 'interview' ) ) {
?>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/dialogue-single.js"></script>
<?php
	}
?>
<?php
	// 「Works」アーカイブのみ
	if( is_post_type_archive( 'works' ) ) {
?>
	<script>
		var worksAjax = {
			ajaxurl:    '<?php echo esc_url( admin_url('admin-ajax.php') ); ?>',
			archiveUrl: '<?php echo esc_url( get_post_type_archive_link('works') ); ?>'
		};
	</script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/works-archive.js"></script>
<?php
	}
?>
<?php
	// 「Dialogue」アーカイブのみ
	if( is_post_type_archive( 'dialogue' ) ) {
?>
	<script>
		var dialogueAjax = {
			ajaxurl: '<?php echo esc_url( admin_url('admin-ajax.php') ); ?>'
		};
	</script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/dialogue-archive.js"></script>
<?php
	}
?>
<?php
	// 「D:Log」アーカイブのみ
	if( is_post_type_archive( 'dlog' ) ) {
?>
	<script>
		var dlogAjax = {
			ajaxurl:    '<?php echo esc_url( admin_url('admin-ajax.php') ); ?>',
			archiveUrl: '<?php echo esc_url( get_post_type_archive_link('dlog') ); ?>'
		};
	</script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/dlog-archive.js"></script>
<?php
	}
?>
