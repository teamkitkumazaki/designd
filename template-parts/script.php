<?php
/**
 * スクリプトファイルを読み込むテンプレートです。
 *
 * @package DESIGN:D
 */
?>

	<script src="<?php echo designd_asset_url( '/assets/vendor/jquery/jquery-3.7.1.min.js' ); ?>" defer></script>
	<script src="<?php echo designd_asset_url( '/assets/vendor/lenis/lenis.min.js' ); ?>" defer></script>
	<script src="<?php echo designd_asset_url( '/assets/vendor/animsition/js/animsition.js' ); ?>" defer></script>
	<script src="<?php echo designd_asset_url( '/assets/vendor/jquery.inview/jquery.inview.min.js' ); ?>" defer></script>
	<script src="<?php echo designd_asset_url( '/assets/vendor/vns/vns.checkAgent.js' ); ?>" defer></script>
	<script src="<?php echo designd_asset_url( '/assets/vendor/shuffle-text/shuffle-text.js' ); ?>" defer></script>
	<script src="<?php echo designd_asset_url( '/assets/js/common.js' ); ?>" defer></script>
	<script src="<?php echo designd_asset_url( '/assets/vendor/modaal/js/modaal.min.js' ); ?>" defer></script>
<?php
	// トップページにのみ読み込むスクリプトファイル設定します。
	if( is_home() || is_front_page() ) {
?>
	<script src="<?php echo designd_asset_url( '/assets/js/top.js' ); ?>" defer></script>
<?php
	}
?>

<?php
	// 固定ページ「About」のみ
	if( is_page( 'about' ) ) {
?>
	<script src="<?php echo designd_asset_url( '/assets/js/about.js' ); ?>" defer></script>
<?php
	}
?>
<?php
	// 「Dialogue」詳細ページのみ
	if( is_singular( 'interview' ) ) {
?>
	<script src="<?php echo designd_asset_url( '/assets/js/dialogue-single.js' ); ?>" defer></script>
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
	<script src="<?php echo designd_asset_url( '/assets/js/works-archive.js' ); ?>" defer></script>
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
	<script src="<?php echo designd_asset_url( '/assets/js/dialogue-archive.js' ); ?>" defer></script>
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
	<script src="<?php echo designd_asset_url( '/assets/js/dlog-archive.js' ); ?>" defer></script>
<?php
	}
?>
