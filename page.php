<?php
/**
 * 固定ページを表示するためのデフォルトテンプレートです。
 *
 * @package DESIGN:D
 */
get_header(); ?>

	<main class="contents">

		<header class="contents__header">
			<h3 class="contents__header__title"><span class="in-shaffle"><?php the_title(); ?></span></h3>
		</header>

		<section class="contents__container">
<?php
	/**
	 * 本文の自動整形を無効にします。
	 */
	//remove_filter ('the_content', 'wpautop');
	the_content();
?>
		</section>
	</main>

<?php
	/* カスタムテンプレート「recommend.php」をインクルードします。 */
	get_template_part( 'template-parts/recommend' );
?>

<?php get_footer(); ?>