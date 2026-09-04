<?php
/**
 * スクリプトファイルを読み込むテンプレートです。
 *
 * @package DESIGN:D
 */

	// 表示速度改善(第三段階・PJAX対応): ページ種別ごとの専用JS/config判定を
	// functions.php の designd_get_pjax_page_meta() に集約。
	// (PJAX遷移時は同じ関数の結果を header.php が data-page-script/
	//  data-page-config として出力し、assets/js/pjax.js が読み取る)
	$pjax_page_meta = designd_get_pjax_page_meta();
?>

	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/jquery/jquery-3.7.1.min.js" defer></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/lenis/lenis.min.js" defer></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/jquery.inview/jquery.inview.min.js" defer></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/vns/vns.checkAgent.js" defer></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/shuffle-text/shuffle-text.js" defer></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/common.js?v=5" defer></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/vendor/modaal/js/modaal.min.js" defer></script>
<?php
	// 表示速度改善(第三段階): 現在のページに対応する専用JSファイルと
	// インラインconfigを designd_get_pjax_page_meta() の判定結果に基づいて出力する。
	if ( $pjax_page_meta['config_var'] && $pjax_page_meta['config'] ) {
?>
	<script>
		var <?php echo esc_js( $pjax_page_meta['config_var'] ); ?> = <?php echo wp_json_encode( $pjax_page_meta['config'] ); ?>;
	</script>
<?php
	}
	if ( $pjax_page_meta['script'] ) {
?>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/<?php echo esc_attr( $pjax_page_meta['script'] ); ?>.js" defer></script>
<?php
	}
?>

	<!-- 表示速度改善(第三段階): PJAX本体。jQuery/animsition/共通JSより後に
	     読み込むことで、必要なグローバル関数(lenis, cmn_mode 等)が
	     揃った状態でPJAXの初期化を行う。 -->
	<script>
		var pjaxSettings = {
			themeUri: '<?php echo esc_url( get_template_directory_uri() ); ?>'
		};
	</script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/pjax.js" defer></script>
